#!/usr/bin/env bash
set -Eeuo pipefail

SCRIPT_DIR="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd -- "$SCRIPT_DIR/.." && pwd)"
CONFIG_FILE="$SCRIPT_DIR/deploy.conf"

DEPLOY_DIR="$SCRIPT_DIR"
REFERENCE_DIR="$DEPLOY_DIR/reference"
PACKAGE_DIR="$DEPLOY_DIR/packages"

BUILD_TMP=""
CONFIRM_TMP=""

cleanup() {
    [[ -z "${BUILD_TMP:-}" ]] || [[ ! -d "$BUILD_TMP" ]] || rm -rf -- "$BUILD_TMP"
    [[ -z "${CONFIRM_TMP:-}" ]] || [[ ! -d "$CONFIRM_TMP" ]] || rm -rf -- "$CONFIRM_TMP"
}
trap cleanup EXIT

die() {
    echo "ERROR: $*" >&2
    exit 1
}

usage() {
    cat <<EOF
Usage:
    $0 build
    $0 install
    $0 confirm
    $0 status
EOF
}

[[ -f "$CONFIG_FILE" ]] || die "Configuration file not found: $CONFIG_FILE"
# shellcheck source=/dev/null
source "$CONFIG_FILE"

validate_config() {
    [[ ${#PROJECTS[@]} -gt 0 ]] || die "PROJECTS is empty"
    [[ ${#INCLUDE_PATTERNS[@]} -gt 0 ]] || die "INCLUDE_PATTERNS is empty"

    for project in "${PROJECTS[@]}"; do
        [[ "$project" =~ ^[a-zA-Z0-9_]+$ ]] || die "Invalid project name: $project"

        [[ -d "$PROJECT_ROOT/$project" ]] ||
            die "Project directory not found: $PROJECT_ROOT/$project"

        local var="INSTALL_DIR_$project"
        local dir="${!var:-}"

        [[ -n "$dir" ]] || die "$var is not defined"
        [[ "$dir" != /* ]] || die "$var must be relative"

        case "$dir" in
            "."|../*|*/../*|..)
                die "$var contains an invalid relative path: $dir"
                ;;
        esac
    done
}

is_timestamp_dir() {
    [[ "$1" =~ ^[0-9]{8}_[0-9]{6}$ ]]
}

new_timestamp() {
    local ts
    while :; do
        ts="$(date '+%Y%m%d_%H%M%S')"
        [[ ! -e "$PACKAGE_DIR/$ts" && ! -e "$PACKAGE_DIR/.tmp_$ts" ]] && {
            echo "$ts"
            return
        }
        sleep 1
    done
}

latest_reference() {
    [[ -d "$REFERENCE_DIR" ]] || return 0
    find "$REFERENCE_DIR" -mindepth 1 -maxdepth 1 -type d -printf '%f
' |
        while read -r n; do is_timestamp_dir "$n" && echo "$n"; done |
        sort | tail -n 1
}

latest_ready_package() {
    [[ -d "$PACKAGE_DIR" ]] || return 0
    find "$PACKAGE_DIR" -mindepth 1 -maxdepth 1 -type d -printf '%f
' |
        while read -r n; do
            is_timestamp_dir "$n" || continue
            [[ -f "$PACKAGE_DIR/$n/manifest.txt" ]] || continue
            grep -q '^status=ready$' "$PACKAGE_DIR/$n/manifest.txt" || continue
            echo "$n"
        done |
        sort | tail -n 1
}

build_rsync_filters() {
    RSYNC_FILTERS=()

    local pattern
    for pattern in "${EXCLUDE_PATTERNS[@]}"; do
        [[ "$pattern" == */** ]] && pattern="${pattern%/**}/***"
        RSYNC_FILTERS+=(--filter="- $pattern")
    done

    RSYNC_FILTERS+=(--filter="+ */")

    for pattern in "${INCLUDE_PATTERNS[@]}"; do
        RSYNC_FILTERS+=(--filter="+ $pattern")
    done

    RSYNC_FILTERS+=(--filter="- *")
}

count_files() {
    [[ -d "$1" ]] || { echo 0; return; }
    find "$1" \( -type f -o -type l \) | wc -l | tr -d ' '
}

remove_empty_dirs() {
    [[ -d "$1" ]] && find "$1" -depth -type d -empty -delete
}

find_deleted_files() {
    local source="$1" reference="$2" output="$3"
    : > "$output"
    [[ -d "$reference" ]] || return 0

    local deleted
    deleted="$(
        rsync -rcn --delete --itemize-changes "${RSYNC_FILTERS[@]}"             "$source/" "$reference/" |
        awk '$1=="*deleting" { $1=""; sub(/^[ \t]+/,""); print }'
    )"

    while IFS= read -r path; do
        [[ -n "$path" ]] || continue
        case "$path" in
            /*|../*|*/../*|..) die "Unsafe deleted path: $path" ;;
        esac
        if [[ -e "$reference/$path" || -L "$reference/$path" ]] &&
           [[ ! -d "$reference/$path" ]]; then
            printf '%s\n' "$path" >> "$output"
        fi
    done <<< "$deleted"
}

validate_package() {
    local package="$1"
    [[ -d "$package" ]] || die "Package not found: $package"
    [[ -f "$package/manifest.txt" ]] || die "Package manifest missing"
    [[ -f "$package/deleted-files.txt" ]] || die "deleted-files.txt missing"

    local project
    for project in "${PROJECTS[@]}"; do
        [[ -d "$package/files/$project" ]] ||
            die "Project missing from package: $project"
    done
}

build() {
    validate_config
    mkdir -p "$REFERENCE_DIR" "$PACKAGE_DIR"

    local ready
    ready="$(latest_ready_package)"
    [[ -z "$ready" ]] ||
        die "Package $ready is still ready. Deploy and confirm it first."

    local reference release final_package
    reference="$(latest_reference)"
    release="$(new_timestamp)"
    final_package="$PACKAGE_DIR/$release"
    BUILD_TMP="$PACKAGE_DIR/.tmp_$release"

    mkdir -p "$BUILD_TMP/files"
    build_rsync_filters

    echo "Building release: $release"
    echo "Reference: ${reference:-none}"
    echo

    local project source package_project reference_project project_deleted
    for project in "${PROJECTS[@]}"; do
        source="$PROJECT_ROOT/$project"
        package_project="$BUILD_TMP/files/$project"
        mkdir -p "$package_project"

        echo "Processing: $project"

        if [[ -n "$reference" ]]; then
            reference_project="$REFERENCE_DIR/$reference/$project"
            mkdir -p "$reference_project"
            rsync -a --checksum "${RSYNC_FILTERS[@]}"                 --compare-dest="$reference_project"                 "$source/" "$package_project/"
        else
            rsync -a --checksum "${RSYNC_FILTERS[@]}"                 "$source/" "$package_project/"
        fi

        remove_empty_dirs "$package_project"
        echo "  Changed/new files: $(count_files "$package_project")"
    done

    : > "$BUILD_TMP/deleted-files.txt"

    if [[ -n "$reference" ]]; then
        for project in "${PROJECTS[@]}"; do
            source="$PROJECT_ROOT/$project"
            reference_project="$REFERENCE_DIR/$reference/$project"
            project_deleted="$(mktemp)"

            find_deleted_files "$source" "$reference_project" "$project_deleted"

            while IFS= read -r path; do
                [[ -n "$path" ]] &&
                    printf '%s|%s\n' "$project" "$path" >> "$BUILD_TMP/deleted-files.txt"
            done < "$project_deleted"

            rm -f "$project_deleted"
        done
    fi

    {
        echo "timestamp=$release"
        echo "status=ready"
        echo "reference=${reference:-none}"
        echo "created_at=$(date --iso-8601=seconds)"
        echo "project_count=${#PROJECTS[@]}"
        echo "deleted_files=$(wc -l < "$BUILD_TMP/deleted-files.txt" | tr -d ' ')"
        echo
        for project in "${PROJECTS[@]}"; do
            echo "project=$project"
            echo "changed_files=$(count_files "$BUILD_TMP/files/$project")"
            echo
        done
    } > "$BUILD_TMP/manifest.txt"

    mv -- "$BUILD_TMP" "$final_package"
    BUILD_TMP=""

    echo
    echo "Build completed: $final_package"
}

install_package() {
    validate_config

    local release package install_dir zip_file
    release="$(latest_ready_package)"
    [[ -n "$release" ]] || die "No ready package found."

    package="$PACKAGE_DIR/$release"
    validate_package "$package"

    install_dir="$package/install"
    rm -rf -- "$install_dir"
    mkdir -p "$install_dir"

    echo "Creating install artifact: $release"
    echo

    local project var relative_target destination
    for project in "${PROJECTS[@]}"; do
        var="INSTALL_DIR_$project"
        relative_target="${!var}"
        destination="$install_dir/$relative_target"

        echo "Project : $project"
        echo "Target  : $relative_target"

        mkdir -p "$destination"
        rsync -a "$package/files/$project/" "$destination/"
        echo
    done

    #
    # This file is relative.
    #
    local source_deleted="$package/deleted-files.txt"
    local install_deleted="$install_dir/deleted-files.txt"
    : > "$install_deleted"

    if [[ -f "$source_deleted" ]]; then
        local relative_path
        while IFS='|' read -r project relative_path; do
            [[ -n "$project" && -n "$relative_path" ]] || continue

            var="INSTALL_DIR_$project"
            relative_target="${!var}"

            case "$relative_path" in
                /*|../*|*/../*|..) die "Unsafe deleted path: $relative_path" ;;
            esac

            printf '%s/%s\n' "${relative_target%/}" "$relative_path"                 >> "$install_deleted"
        done < "$source_deleted"
    fi

    zip_file="$package/${release}.zip"
    rm -f -- "$zip_file"

    (
        cd "$install_dir"
        zip -qr "$zip_file" .
    )

    echo "Install directory:"
    echo "  $install_dir"
    echo
    echo "ZIP:"
    echo "  $zip_file"
    echo
    echo "Deleted files:"
    echo "  $install_deleted"
}

confirm() {
    validate_config

    local release package reference final_reference
    release="$(latest_ready_package)"
    [[ -n "$release" ]] || die "No ready package found."

    package="$PACKAGE_DIR/$release"
    validate_package "$package"
    reference="$(latest_reference)"
    final_reference="$REFERENCE_DIR/$release"

    echo "Release: $release"
    echo "Previous reference: ${reference:-none}"
    echo

    read -r -p "Confirm this release? [y/N] " answer
    case "$answer" in y|Y|yes|YES) ;; *) echo "Cancelled."; return 0 ;; esac

    [[ ! -e "$final_reference" ]] ||
        die "Reference already exists: $final_reference"

    CONFIRM_TMP="$REFERENCE_DIR/.tmp_$release"
    mkdir -p "$CONFIRM_TMP"

    local project path
    if [[ -n "$reference" ]]; then
        for project in "${PROJECTS[@]}"; do
            mkdir -p "$CONFIRM_TMP/$project"
            rsync -a "$REFERENCE_DIR/$reference/$project/"                 "$CONFIRM_TMP/$project/"
        done
    else
        for project in "${PROJECTS[@]}"; do
            mkdir -p "$CONFIRM_TMP/$project"
        done
    fi

    for project in "${PROJECTS[@]}"; do
        rsync -a "$package/files/$project/" "$CONFIRM_TMP/$project/"
    done

    while IFS='|' read -r project path; do
        [[ -n "$project" && -n "$path" ]] || continue
        case "$path" in
            /*|../*|*/../*|..) die "Unsafe deleted path: $path" ;;
        esac
        rm -f -- "$CONFIRM_TMP/$project/$path"
    done < "$package/deleted-files.txt"

    for project in "${PROJECTS[@]}"; do
        remove_empty_dirs "$CONFIRM_TMP/$project"
    done

    {
        echo "timestamp=$release"
        echo "status=confirmed"
        echo "source_package=$release"
        echo "created_at=$(date --iso-8601=seconds)"
        echo "project_count=${#PROJECTS[@]}"
    } > "$CONFIRM_TMP/manifest.txt"

    mv -- "$CONFIRM_TMP" "$final_reference"
    CONFIRM_TMP=""

    local manifest="$package/manifest.txt"
    local new_manifest="$manifest.tmp"

    sed 's/^status=ready$/status=confirmed/' "$manifest" > "$new_manifest"
    printf 'confirmed_at=%s\n' "$(date --iso-8601=seconds)" >> "$new_manifest"
    mv -- "$new_manifest" "$manifest"

    echo
    echo "Release confirmed: $release"
    echo "New reference: $final_reference"
}

status() {
    validate_config

    local reference ready
    reference="$(latest_reference)"
    ready="$(latest_ready_package)"

    echo "Deployment status"
    echo "-----------------"
    echo "Latest reference : ${reference:-none}"
    echo "Ready package    : ${ready:-none}"

    if [[ -n "$ready" ]]; then
        echo
        cat "$PACKAGE_DIR/$ready/manifest.txt"
    fi
}

main() {
    case "${1:-}" in
        build)   build ;;
        install) install_package ;;
        confirm) confirm ;;
        status)  status ;;
        -h|--help|help) usage ;;
        *) usage; exit 1 ;;
    esac
}

main "$@"
