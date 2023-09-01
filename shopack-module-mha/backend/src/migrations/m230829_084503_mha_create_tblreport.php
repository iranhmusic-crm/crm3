<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m230829_084503_mha_create_tblreport extends Migration
{
    public function safeUp()
    {
        $this->execute(<<<SQLSTR
CREATE TABLE `tbl_MHA_Report` (
    `rptID` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `rptUUID` VARCHAR(38) NOT NULL COLLATE 'utf8mb4_unicode_ci',
    `rptName` VARCHAR(1024) NOT NULL COLLATE 'utf8mb4_unicode_ci',
    `rptType` CHAR(1) NOT NULL COMMENT 'M:Members, F:Financial' COLLATE 'utf8mb4_unicode_ci',
    `rptInputFields` JSON NOT NULL,
    `rptOutputFields` JSON NOT NULL,
    `rptStatus` CHAR(1) NOT NULL DEFAULT 'A' COMMENT 'A:Active, D:Disable, R:Removed' COLLATE 'utf8mb4_unicode_ci',
    `rptCreatedAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `rptCreatedBy` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
    `rptUpdatedAt` DATETIME NULL DEFAULT NULL,
    `rptUpdatedBy` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
    `rptRemovedAt` INT(10) UNSIGNED NOT NULL DEFAULT '0',
    `rptRemovedBy` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
    PRIMARY KEY (`rptID`) USING BTREE,
    UNIQUE INDEX `rptUUID` (`rptUUID`) USING BTREE,
    INDEX `FK_tbl_MHA_Report_tbl_AAA_User_creator` (`rptCreatedBy`) USING BTREE,
    INDEX `FK_tbl_MHA_Report_tbl_AAA_User_modifier` (`rptUpdatedBy`) USING BTREE,
    INDEX `FK_tbl_MHA_Report_tbl_AAA_User_remover` (`rptRemovedBy`) USING BTREE,
    CONSTRAINT `FK_tbl_MHA_Report_tbl_AAA_User_creator` FOREIGN KEY (`rptCreatedBy`) REFERENCES `tbl_AAA_User` (`usrID`) ON UPDATE NO ACTION ON DELETE NO ACTION,
    CONSTRAINT `FK_tbl_MHA_Report_tbl_AAA_User_modifier` FOREIGN KEY (`rptUpdatedBy`) REFERENCES `tbl_AAA_User` (`usrID`) ON UPDATE NO ACTION ON DELETE NO ACTION,
    CONSTRAINT `FK_tbl_MHA_Report_tbl_AAA_User_remover` FOREIGN KEY (`rptRemovedBy`) REFERENCES `tbl_AAA_User` (`usrID`) ON UPDATE NO ACTION ON DELETE NO ACTION
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
;
SQLSTR
        );

        $this->execute("DROP TRIGGER IF EXISTS trg_updatelog_tbl_MHA_Report;");

        $this->execute(<<<SQLSTR
CREATE TRIGGER trg_updatelog_tbl_MHA_Report AFTER UPDATE ON tbl_MHA_Report FOR EACH ROW BEGIN
  DECLARE Changes JSON DEFAULT JSON_OBJECT();

  IF ISNULL(OLD.rptUUID) != ISNULL(NEW.rptUUID) OR OLD.rptUUID != NEW.rptUUID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("rptUUID", IF(ISNULL(OLD.rptUUID), NULL, OLD.rptUUID))); END IF;
  IF ISNULL(OLD.rptName) != ISNULL(NEW.rptName) OR OLD.rptName != NEW.rptName THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("rptName", IF(ISNULL(OLD.rptName), NULL, OLD.rptName))); END IF;
  IF ISNULL(OLD.rptType) != ISNULL(NEW.rptType) OR OLD.rptType != NEW.rptType THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("rptType", IF(ISNULL(OLD.rptType), NULL, OLD.rptType))); END IF;
  IF ISNULL(OLD.rptInputFields) != ISNULL(NEW.rptInputFields) OR OLD.rptInputFields != NEW.rptInputFields THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("rptInputFields", IF(ISNULL(OLD.rptInputFields), NULL, OLD.rptInputFields))); END IF;
  IF ISNULL(OLD.rptOutputFields) != ISNULL(NEW.rptOutputFields) OR OLD.rptOutputFields != NEW.rptOutputFields THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("rptOutputFields", IF(ISNULL(OLD.rptOutputFields), NULL, OLD.rptOutputFields))); END IF;
  IF ISNULL(OLD.rptStatus) != ISNULL(NEW.rptStatus) OR OLD.rptStatus != NEW.rptStatus THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("rptStatus", IF(ISNULL(OLD.rptStatus), NULL, OLD.rptStatus))); END IF;

  IF JSON_LENGTH(Changes) > 0 THEN
--    IF ISNULL(NEW.rptUpdatedBy) THEN
--      SIGNAL SQLSTATE "45401"
--         SET MESSAGE_TEXT = "UpdatedBy is not set";
--    END IF;

    INSERT INTO tbl_SYS_ActionLogs
        SET atlBy     = NEW.rptUpdatedBy
          , atlAction = "UPDATE"
          , atlTarget = "tbl_MHA_Report"
          , atlInfo   = JSON_OBJECT("rptID", OLD.rptID, "old", Changes);
  END IF;
END
SQLSTR
        );

    }

    public function safeDown()
    {
        echo "m230829_084503_mha_create_tblreport cannot be reverted.\n";
        return false;
    }

}
