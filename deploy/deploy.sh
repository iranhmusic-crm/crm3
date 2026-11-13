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
    find "$REFERENCE_DIR" -mindepth 1 -maxdepth 1 -type d -printf '%f\n' |
        while read -r n; do
            is_timestamp_dir "$n" || continue
            local found=0 project
            for project in "${PROJECTS[@]}"; do
                [[ -f "$REFERENCE_DIR/$n/$project.sha256" ]] || { found=1; break; }
            done
            [[ "$found" -eq 0 ]] && echo "$n"
        done |
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

# Manifest format (tab-separated): type, digest/target, escaped relative path.
# Escape backslash and control characters so every record remains one line and
# paths can be inspected/edited without Base64.
create_manifest() {
    local root="$1" output="$2"
    : > "$output"

    # Hash regular files in batches. Python only performs byte-safe escaping;
    # it does not hash or read file contents.
    (
        cd "$root"
        find . -type f -print0 |
            xargs -0 -r sha256sum --zero --
    ) | python3 -c '
import sys

def esc(value):
    out = bytearray()
    for b in value:
        if b == 0x5c: out += b"\\\\"
        elif b == 0x09: out += b"\\t"
        elif b == 0x0a: out += b"\\n"
        elif b == 0x0d: out += b"\\r"
        elif b < 0x20 or b == 0x7f: out += (b"\\x%02x" % b)
        else: out.append(b)
    return bytes(out)

for record in sys.stdin.buffer.read().split(b"\0"):
    if not record: continue
    digest, path = record[:64], record[66:]
    if path.startswith(b"./"): path = path[2:]
    sys.stdout.buffer.write(b"F\t" + digest + b"\t" + esc(path) + b"\n")
' > "$output"

    # Keep symlink entries distinct from their targets. The target and path
    # are escaped using the same byte-level rules as regular-file paths.
    (cd "$root" && find . -type l -printf '%P\0%l\0') |
        python3 -c '
import sys

def esc(value):
    out = bytearray()
    for b in value:
        if b == 0x5c: out += b"\\\\"
        elif b == 0x09: out += b"\\t"
        elif b == 0x0a: out += b"\\n"
        elif b == 0x0d: out += b"\\r"
        elif b < 0x20 or b == 0x7f: out += (b"\\x%02x" % b)
        else: out.append(b)
    return bytes(out)

items = sys.stdin.buffer.read().split(b"\0")
for i in range(0, len(items) - 1, 2):
    path, target = items[i], items[i + 1]
    if not path: continue
    sys.stdout.buffer.write(b"L\t" + esc(target) + b"\t" + esc(path) + b"\n")
' >> "$output"
}

copy_changed_files() {
    local snapshot="$1" package_project="$2" current_manifest="$3"
    local previous_manifest="$4" deleted_output="$5" project="$6"
    local type value escaped_path path signature
    declare -A CURRENT=() PREVIOUS=()

    # Escaped paths are safe associative-array keys and contain no literal
    # tabs or newlines. Decode only when accessing the filesystem.
    if [[ -f "$previous_manifest" ]]; then
        while IFS=$'\t' read -r type value escaped_path; do
            [[ -n "$escaped_path" ]] || continue
            PREVIOUS["$escaped_path"]="$type:$value"
        done < "$previous_manifest"
    fi

    while IFS=$'\t' read -r type value escaped_path; do
        [[ -n "$escaped_path" ]] || continue
        signature="$type:$value"
        CURRENT["$escaped_path"]="$signature"
        if [[ "${PREVIOUS[$escaped_path]-}" != "$signature" ]]; then
            printf -v path '%b' "$escaped_path"
            (cd "$snapshot" && cp -a --parents -- "$path" "$package_project/")
        fi
    done < "$current_manifest"

    for escaped_path in "${!PREVIOUS[@]}"; do
        [[ ${CURRENT[$escaped_path]+present} ]] ||
            printf '%s\t%s\n' "$project" "$escaped_path" >> "$deleted_output"
    done
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
    command -v rsync >/dev/null 2>&1 || die "rsync is required"
    command -v sha256sum >/dev/null 2>&1 || die "sha256sum is required"
    command -v python3 >/dev/null 2>&1 || die "python3 is required"

    mkdir -p "$REFERENCE_DIR" "$PACKAGE_DIR"

    local ready
    ready="$(latest_ready_package)"
    [[ -z "$ready" ]] || die "Package $ready is still ready. Deploy and confirm it first."

    local reference release final_package
    reference="$(latest_reference)"
    release="$(new_timestamp)"
    final_package="$PACKAGE_DIR/$release"
    BUILD_TMP="$PACKAGE_DIR/.tmp_$release"

    mkdir -p "$BUILD_TMP/files" "$BUILD_TMP/manifests" "$BUILD_TMP/snapshot"
    build_rsync_filters

    echo "Building release: $release"
    echo "Reference: ${reference:-none}"
    echo

    local project source snapshot package_project current_manifest previous_manifest project_deleted
    local changed_count deleted_count total_changed=0
    : > "$BUILD_TMP/deleted-files.txt"

    for project in "${PROJECTS[@]}"; do
        source="$PROJECT_ROOT/$project"
        snapshot="$BUILD_TMP/snapshot/$project"
        package_project="$BUILD_TMP/files/$project"
        current_manifest="$BUILD_TMP/manifests/$project.sha256"
        previous_manifest=""
        project_deleted="$BUILD_TMP/deleted-$project.txt"

        mkdir -p "$package_project"
        : > "$project_deleted"

        [[ -z "$reference" ]] || previous_manifest="$REFERENCE_DIR/$reference/$project.sha256"

        echo "Processing: $project"

        if [[ -z "$reference" ]]; then
            # First build: there is no previous manifest to compare against.
            # Let rsync copy the filtered tree directly into the package.
            rsync -a --copy-dirlinks "${RSYNC_FILTERS[@]}" "$source/" "$package_project/"
            create_manifest "$package_project" "$current_manifest"
        else
            mkdir -p "$snapshot"

            # With a reference, stage the filtered source tree for manifest comparison.
            rsync -a --copy-dirlinks "${RSYNC_FILTERS[@]}" "$source/" "$snapshot/"
            create_manifest "$snapshot" "$current_manifest"
            copy_changed_files "$snapshot" "$package_project" "$current_manifest" \
                "$previous_manifest" "$project_deleted" "$project"
            rm -rf -- "$snapshot"
        fi

        if [[ -s "$project_deleted" ]]; then
            cat -- "$project_deleted" >> "$BUILD_TMP/deleted-files.txt"
        fi

        changed_count="$(count_files "$package_project")"
        total_changed=$((total_changed + changed_count))
        echo "  Changed/new files: $changed_count"

        rm -rf -- "$snapshot" "$project_deleted"
    done

    rm -rf -- "$BUILD_TMP/snapshot"

    deleted_count="$(wc -l < "$BUILD_TMP/deleted-files.txt" | tr -d ' ')"

    # Do not publish an empty package. A release is needed only when at least
    # one file is new/changed or at least one previous file was deleted.
    if (( total_changed == 0 && deleted_count == 0 )); then
        rm -rf -- "$BUILD_TMP"
        BUILD_TMP=""
        echo
        echo "No changes detected. No package was created."
        return 0
    fi

    {
        echo "timestamp=$release"
        echo "status=ready"
        echo "reference=${reference:-none}"
        echo "created_at=$(date --iso-8601=seconds)"
        echo "project_count=${#PROJECTS[@]}"
        echo "deleted_files=$deleted_count"
        echo "hash_algorithm=SHA-256"
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
        local escaped_path relative_path
        while IFS=$'\t' read -r project escaped_path; do
            [[ -n "$project" && -n "$escaped_path" ]] || continue

            var="INSTALL_DIR_$project"
            relative_target="${!var}"
            printf -v relative_path '%b' "$escaped_path"

            case "$relative_path" in
                /*|../*|*/../*|..) die "Unsafe deleted path: $relative_path" ;;
            esac

            printf '%s/%s\n' "${relative_target%/}" "$escaped_path" >> "$install_deleted"
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

    local release package final_reference
    release="$(latest_ready_package)"
    [[ -n "$release" ]] || die "No ready package found."

    package="$PACKAGE_DIR/$release"
    validate_package "$package"
    final_reference="$REFERENCE_DIR/$release"

    echo "Release: $release"
    echo "Previous reference: $(latest_reference || true)"
    echo

    read -r -p "Confirm this release? [y/N] " answer
    case "$answer" in y|Y|yes|YES) ;; *) echo "Cancelled."; return 0 ;; esac

    [[ ! -e "$final_reference" ]] || die "Reference already exists: $final_reference"

    CONFIRM_TMP="$REFERENCE_DIR/.tmp_$release"
    mkdir -p "$CONFIRM_TMP"

    local project
    for project in "${PROJECTS[@]}"; do
        [[ -f "$package/manifests/$project.sha256" ]] ||
            die "SHA-256 manifest missing for project: $project"
        cp -- "$package/manifests/$project.sha256" "$CONFIRM_TMP/$project.sha256"
    done

    {
        echo "timestamp=$release"
        echo "status=confirmed"
        echo "source_package=$release"
        echo "created_at=$(date --iso-8601=seconds)"
        echo "project_count=${#PROJECTS[@]}"
        echo "hash_algorithm=SHA-256"
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
