<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m231103_061223_mha_create_membergroup extends Migration
{
	public function safeUp()
	{
		$this->execute(<<<SQLSTR
CREATE TABLE `tbl_MHA_MemberGroup` (
	`mgpID` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`mgpUUID` VARCHAR(38) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`mgpName` VARCHAR(64) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`mgpMembershipDiscountAmount` DOUBLE UNSIGNED NULL DEFAULT NULL,
	`mgpMembershipDiscountType` CHAR(1) NULL DEFAULT NULL COMMENT '%:Percent, $:Price' COLLATE 'utf8mb4_unicode_ci',
	`mgpMembershipCardDiscountAmount` DOUBLE UNSIGNED NULL DEFAULT NULL,
	`mgpMembershipCardDiscountType` CHAR(1) NULL DEFAULT NULL COMMENT '%:Percent, $:Price' COLLATE 'utf8mb4_unicode_ci',
	`mgpDeliveryDiscountAmount` DOUBLE UNSIGNED NULL DEFAULT NULL,
	`mgpDeliveryDiscountType` CHAR(1) NULL DEFAULT NULL COMMENT '%:Percent, $:Price' COLLATE 'utf8mb4_unicode_ci',
	`mgpI18NData` JSON NULL DEFAULT NULL,
	`mgpStatus` CHAR(1) NOT NULL DEFAULT 'A' COMMENT 'A:Active, R:Removed' COLLATE 'utf8mb4_unicode_ci',
	`mgpCreatedAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`mgpCreatedBy` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
	`mgpUpdatedAt` DATETIME NULL DEFAULT NULL,
	`mgpUpdatedBy` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
	`mgpRemovedAt` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`mgpRemovedBy` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
	PRIMARY KEY (`mgpID`) USING BTREE,
	UNIQUE INDEX `mgpUUID` (`mgpUUID`) USING BTREE
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
;
SQLSTR
    );
    $this->alterColumn('tbl_MHA_MemberGroup', 'mgpI18NData', $this->json());

    $this->execute(<<<SQLSTR
CREATE TABLE `tbl_MHA_Member_MemberGroup` (
	`mbrmgpID` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`mbrmgpUUID` VARCHAR(38) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`mbrmgpMemberID` BIGINT(20) UNSIGNED NOT NULL,
	`mbrmgpMemberGroupID` INT(10) UNSIGNED NOT NULL,
	`mbrmgpStartAt` DATETIME NULL DEFAULT NULL,
	`mbrmgpEndAt` DATETIME NULL DEFAULT NULL,
	`mbrmgpCreatedAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`mbrmgpCreatedBy` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
	`mbrmgpUpdatedAt` DATETIME NULL DEFAULT NULL,
	`mbrmgpUpdatedBy` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
	`mbrmgpRemovedAt` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`mbrmgpRemovedBy` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
	PRIMARY KEY (`mbrmgpID`) USING BTREE,
	UNIQUE INDEX `mbrmgpUUID` (`mbrmgpUUID`) USING BTREE,
	INDEX `FK_tbl_MHA_Member_MemberGroup_tbl_MHA_Member` (`mbrmgpMemberID`) USING BTREE,
	INDEX `FK_tbl_MHA_Member_MemberGroup_tbl_MHA_MemberGroup` (`mbrmgpMemberGroupID`) USING BTREE,
	CONSTRAINT `FK_tbl_MHA_Member_MemberGroup_tbl_MHA_Member` FOREIGN KEY (`mbrmgpMemberID`) REFERENCES `tbl_MHA_Member` (`mbrUserID`) ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT `FK_tbl_MHA_Member_MemberGroup_tbl_MHA_MemberGroup` FOREIGN KEY (`mbrmgpMemberGroupID`) REFERENCES `tbl_MHA_MemberGroup` (`mgpID`) ON UPDATE NO ACTION ON DELETE NO ACTION
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
;
SQLSTR
    );

    $this->execute("DROP TRIGGER IF EXISTS trg_updatelog_tbl_MHA_MemberGroup;");
    $this->execute(<<<SQLSTR
CREATE TRIGGER trg_updatelog_tbl_MHA_MemberGroup AFTER UPDATE ON tbl_MHA_MemberGroup FOR EACH ROW BEGIN
  DECLARE Changes JSON DEFAULT JSON_OBJECT();

  IF ISNULL(OLD.mgpDeliveryDiscountAmount) != ISNULL(NEW.mgpDeliveryDiscountAmount) OR OLD.mgpDeliveryDiscountAmount != NEW.mgpDeliveryDiscountAmount THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mgpDeliveryDiscountAmount", IF(ISNULL(OLD.mgpDeliveryDiscountAmount), NULL, OLD.mgpDeliveryDiscountAmount))); END IF;
  IF ISNULL(OLD.mgpDeliveryDiscountType) != ISNULL(NEW.mgpDeliveryDiscountType) OR OLD.mgpDeliveryDiscountType != NEW.mgpDeliveryDiscountType THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mgpDeliveryDiscountType", IF(ISNULL(OLD.mgpDeliveryDiscountType), NULL, OLD.mgpDeliveryDiscountType))); END IF;
  IF ISNULL(OLD.mgpI18NData) != ISNULL(NEW.mgpI18NData) OR OLD.mgpI18NData != NEW.mgpI18NData THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mgpI18NData", IF(ISNULL(OLD.mgpI18NData), NULL, OLD.mgpI18NData))); END IF;
  IF ISNULL(OLD.mgpMembershipCardDiscountAmount) != ISNULL(NEW.mgpMembershipCardDiscountAmount) OR OLD.mgpMembershipCardDiscountAmount != NEW.mgpMembershipCardDiscountAmount THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mgpMembershipCardDiscountAmount", IF(ISNULL(OLD.mgpMembershipCardDiscountAmount), NULL, OLD.mgpMembershipCardDiscountAmount))); END IF;
  IF ISNULL(OLD.mgpMembershipCardDiscountType) != ISNULL(NEW.mgpMembershipCardDiscountType) OR OLD.mgpMembershipCardDiscountType != NEW.mgpMembershipCardDiscountType THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mgpMembershipCardDiscountType", IF(ISNULL(OLD.mgpMembershipCardDiscountType), NULL, OLD.mgpMembershipCardDiscountType))); END IF;
  IF ISNULL(OLD.mgpMembershipDiscountAmount) != ISNULL(NEW.mgpMembershipDiscountAmount) OR OLD.mgpMembershipDiscountAmount != NEW.mgpMembershipDiscountAmount THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mgpMembershipDiscountAmount", IF(ISNULL(OLD.mgpMembershipDiscountAmount), NULL, OLD.mgpMembershipDiscountAmount))); END IF;
  IF ISNULL(OLD.mgpMembershipDiscountType) != ISNULL(NEW.mgpMembershipDiscountType) OR OLD.mgpMembershipDiscountType != NEW.mgpMembershipDiscountType THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mgpMembershipDiscountType", IF(ISNULL(OLD.mgpMembershipDiscountType), NULL, OLD.mgpMembershipDiscountType))); END IF;
  IF ISNULL(OLD.mgpName) != ISNULL(NEW.mgpName) OR OLD.mgpName != NEW.mgpName THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mgpName", IF(ISNULL(OLD.mgpName), NULL, OLD.mgpName))); END IF;
  IF ISNULL(OLD.mgpStatus) != ISNULL(NEW.mgpStatus) OR OLD.mgpStatus != NEW.mgpStatus THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mgpStatus", IF(ISNULL(OLD.mgpStatus), NULL, OLD.mgpStatus))); END IF;
  IF ISNULL(OLD.mgpUUID) != ISNULL(NEW.mgpUUID) OR OLD.mgpUUID != NEW.mgpUUID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mgpUUID", IF(ISNULL(OLD.mgpUUID), NULL, OLD.mgpUUID))); END IF;

  IF JSON_LENGTH(Changes) > 0 THEN
--    IF ISNULL(NEW.mgpUpdatedBy) THEN
--      SIGNAL SQLSTATE "45401"
--         SET MESSAGE_TEXT = "UpdatedBy is not set";
--    END IF;

    INSERT INTO tbl_SYS_ActionLogs
        SET atlBy     = NEW.mgpUpdatedBy
          , atlAction = "UPDATE"
          , atlTarget = "tbl_MHA_MemberGroup"
          , atlInfo   = JSON_OBJECT("mgpID", OLD.mgpID, "old", Changes);
  END IF;
END
SQLSTR
    );

    $this->execute("DROP TRIGGER IF EXISTS trg_updatelog_tbl_MHA_Member_MemberGroup;");
    $this->execute(<<<SQLSTR
CREATE TRIGGER trg_updatelog_tbl_MHA_Member_MemberGroup AFTER UPDATE ON tbl_MHA_Member_MemberGroup FOR EACH ROW BEGIN
  DECLARE Changes JSON DEFAULT JSON_OBJECT();

  IF ISNULL(OLD.mbrmgpEndAt) != ISNULL(NEW.mbrmgpEndAt) OR OLD.mbrmgpEndAt != NEW.mbrmgpEndAt THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrmgpEndAt", IF(ISNULL(OLD.mbrmgpEndAt), NULL, OLD.mbrmgpEndAt))); END IF;
  IF ISNULL(OLD.mbrmgpMemberGroupID) != ISNULL(NEW.mbrmgpMemberGroupID) OR OLD.mbrmgpMemberGroupID != NEW.mbrmgpMemberGroupID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrmgpMemberGroupID", IF(ISNULL(OLD.mbrmgpMemberGroupID), NULL, OLD.mbrmgpMemberGroupID))); END IF;
  IF ISNULL(OLD.mbrmgpMemberID) != ISNULL(NEW.mbrmgpMemberID) OR OLD.mbrmgpMemberID != NEW.mbrmgpMemberID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrmgpMemberID", IF(ISNULL(OLD.mbrmgpMemberID), NULL, OLD.mbrmgpMemberID))); END IF;
  IF ISNULL(OLD.mbrmgpStartAt) != ISNULL(NEW.mbrmgpStartAt) OR OLD.mbrmgpStartAt != NEW.mbrmgpStartAt THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrmgpStartAt", IF(ISNULL(OLD.mbrmgpStartAt), NULL, OLD.mbrmgpStartAt))); END IF;
  IF ISNULL(OLD.mbrmgpUUID) != ISNULL(NEW.mbrmgpUUID) OR OLD.mbrmgpUUID != NEW.mbrmgpUUID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrmgpUUID", IF(ISNULL(OLD.mbrmgpUUID), NULL, OLD.mbrmgpUUID))); END IF;

  IF JSON_LENGTH(Changes) > 0 THEN
--    IF ISNULL(NEW.mbrmgpUpdatedBy) THEN
--      SIGNAL SQLSTATE "45401"
--         SET MESSAGE_TEXT = "UpdatedBy is not set";
--    END IF;

    INSERT INTO tbl_SYS_ActionLogs
        SET atlBy     = NEW.mbrmgpUpdatedBy
          , atlAction = "UPDATE"
          , atlTarget = "tbl_MHA_Member_MemberGroup"
          , atlInfo   = JSON_OBJECT("mbrmgpID", OLD.mbrmgpID, "old", Changes);
  END IF;
END
SQLSTR
    );

	}

	public function safeDown()
	{
		echo "m231103_061223_mha_create_membergroup cannot be reverted.\n";
		return false;
	}

}
