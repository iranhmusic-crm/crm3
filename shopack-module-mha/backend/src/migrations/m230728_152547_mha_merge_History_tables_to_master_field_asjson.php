<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m230728_152547_mha_merge_History_tables_to_master_field_asjson extends Migration
{
    public function safeUp()
    {
        $this->execute("DROP TRIGGER IF EXISTS `trg_tbl_MHA_MemberMasterInsDoc_after_insert`;");
        $this->execute("DROP TRIGGER IF EXISTS `trg_tbl_MHA_MemberMasterInsDoc_after_update`;");

        $this->execute("DROP TRIGGER IF EXISTS `trg_tbl_MHA_MemberSupplementaryInsDoc_after_insert`;");
        $this->execute("DROP TRIGGER IF EXISTS `trg_tbl_MHA_MemberSupplementaryInsDoc_after_update`;");

        $this->execute("DROP TRIGGER IF EXISTS `trg_updatelog_tbl_MHA_MemberMasterInsDocHistory`;");
        $this->execute("DROP TRIGGER IF EXISTS `trg_updatelog_tbl_MHA_MemberSupplementaryInsDocHistory`;");

        $this->execute(<<<SQL
DELETE
    FROM tbl_SYS_ActionLogs
    WHERE atlTarget IN ('tbl_MHA_MemberMasterInsDocHistory', 'tbl_MHA_MemberSupplementaryInsDoc')
;
SQL
        );

        $this->execute(<<<SQL
ALTER TABLE `tbl_MHA_MemberMasterInsDoc`
    ADD COLUMN `mbrminsdocHistory` JSON NULL AFTER `mbrminsdocDocDate`;
SQL
        );
        $this->alterColumn('tbl_MHA_MemberMasterInsDoc', 'mbrminsdocHistory', $this->json());

        $this->execute(<<<SQL
ALTER TABLE `tbl_MHA_MemberSupplementaryInsDoc`
    ADD COLUMN `mbrsinsdocHistory` JSON NULL AFTER `mbrsinsdocDocDate`;
SQL
        );
        $this->alterColumn('tbl_MHA_MemberSupplementaryInsDoc', 'mbrsinsdocHistory', $this->json());

        $this->execute(<<<SQL
        UPDATE tbl_MHA_MemberMasterInsDoc
    INNER JOIN (
        SELECT mbrminsdochstMasterInsDocID
             , JSON_ARRAYAGG(
                 JSON_OBJECT(
                   'at', UNIX_TIMESTAMP(mbrminsdochstCreatedAt),
                   'status', mbrminsdochstAction
                 )
               ) AS history
          FROM tbl_MHA_MemberMasterInsDocHistory
      GROUP BY mbrminsdochstMasterInsDocID
               ) t1
            ON t1.mbrminsdochstMasterInsDocID = tbl_MHA_MemberMasterInsDoc.mbrminsdocID
           SET mbrminsdocHistory = t1.history
SQL
        );

        $this->execute(<<<SQL
        UPDATE tbl_MHA_MemberSupplementaryInsDoc
    INNER JOIN (
        SELECT mbrsinsdochstSupplementaryInsDocID
             , JSON_ARRAYAGG(
                 JSON_OBJECT(
                   'at', UNIX_TIMESTAMP(mbrsinsdochstCreatedAt),
                   'status', mbrsinsdochstAction
                 )
               ) AS history
          FROM tbl_MHA_MemberSupplementaryInsDocHistory
      GROUP BY mbrsinsdochstSupplementaryInsDocID
               ) t1
            ON t1.mbrsinsdochstSupplementaryInsDocID = tbl_MHA_MemberSupplementaryInsDoc.mbrsinsdocID
           SET mbrsinsdocHistory = t1.history
SQL
        );

        $this->execute("DROP TRIGGER IF EXISTS `trg_tbl_MHA_MemberMasterInsDoc_before_insert`;");
        $this->execute(<<<SQL
CREATE TRIGGER `trg_tbl_MHA_MemberMasterInsDoc_before_insert` BEFORE INSERT ON `tbl_MHA_MemberMasterInsDoc` FOR EACH ROW BEGIN
    SET NEW.mbrminsdocHistory = JSON_ARRAY(
        JSON_OBJECT(
            'at', UNIX_TIMESTAMP(),
            'status', NEW.mbrminsdocStatus
        )
    );
END
SQL
        );

        $this->execute("DROP TRIGGER IF EXISTS `trg_tbl_MHA_MemberMasterInsDoc_before_update`;");
        $this->execute(<<<SQL
CREATE TRIGGER `trg_tbl_MHA_MemberMasterInsDoc_before_update` BEFORE UPDATE ON `tbl_MHA_MemberMasterInsDoc` FOR EACH ROW BEGIN
    IF IFNULL(NEW.mbrminsdocStatus, '') != IFNULL(OLD.mbrminsdocStatus, '')
    THEN
        SET NEW.mbrminsdocHistory = JSON_MERGE_PRESERVE(COALESCE(OLD.mbrminsdocHistory, '[]'),
            JSON_OBJECT(
                'at', UNIX_TIMESTAMP(),
                'status', NEW.mbrminsdocStatus
            )
        );
    END IF;
END
SQL
        );

        $this->execute("DROP TRIGGER IF EXISTS `trg_tbl_MHA_MemberSupplementaryInsDoc_before_insert`;");
        $this->execute(<<<SQL
CREATE TRIGGER `trg_tbl_MHA_MemberSupplementaryInsDoc_before_insert` BEFORE INSERT ON `tbl_MHA_MemberSupplementaryInsDoc` FOR EACH ROW BEGIN
    SET NEW.mbrsinsdocHistory = JSON_ARRAY(
        JSON_OBJECT(
            'at', UNIX_TIMESTAMP(),
            'status', NEW.mbrsinsdocStatus
        )
    );
END
SQL
        );

        $this->execute("DROP TRIGGER IF EXISTS `trg_tbl_MHA_MemberSupplementaryInsDoc_before_update`;");
        $this->execute(<<<SQL
CREATE TRIGGER `trg_tbl_MHA_MemberSupplementaryInsDoc_before_update` BEFORE UPDATE ON `tbl_MHA_MemberSupplementaryInsDoc` FOR EACH ROW BEGIN
    IF IFNULL(NEW.mbrsinsdocStatus, '') != IFNULL(OLD.mbrsinsdocStatus, '')
    THEN
        SET NEW.mbrsinsdocHistory = JSON_MERGE_PRESERVE(COALESCE(OLD.mbrsinsdocHistory, '[]'),
            JSON_OBJECT(
                'at', UNIX_TIMESTAMP(),
                'status', NEW.mbrsinsdocStatus
            )
        );
    END IF;
END
SQL
        );

        $this->execute(<<<SQL
ALTER TABLE `tbl_MHA_MemberMasterInsDocHistory`
	DROP FOREIGN KEY `FK_tbl_MHA_MemberMasterInsDocHistory_tbl_AAA_User_creator`,
	DROP FOREIGN KEY `FK_tbl_MHA_MemberMasterInsDocHistory_tbl_MHA_MemberMasterInsDoc`;
SQL
        );
        $this->execute("RENAME TABLE `tbl_MHA_MemberMasterInsDocHistory` TO `DELETED_tbl_MHA_MemberMasterInsDocHistory`;");

        $this->execute(<<<SQL
ALTER TABLE `tbl_MHA_MemberSupplementaryInsDocHistory`
	DROP FOREIGN KEY `FK_tbl_MHA_MemberSuppInsDocHistory_tbl_AAA_User_creator`,
	DROP FOREIGN KEY `FK_tbl_MHA_MemberSuppInsDocHistory_tbl_MHA_MemberSuppInsDoc`;
SQL
        );
        $this->execute("RENAME TABLE `tbl_MHA_MemberSupplementaryInsDocHistory` TO `DELETED_tbl_MHA_MemberSupplementaryInsDocHistory`;");

        $this->execute("DROP TRIGGER IF EXISTS `trg_updatelog_tbl_MHA_MemberMasterInsDoc`;");
        $this->execute(<<<SQL
CREATE TRIGGER trg_updatelog_tbl_MHA_MemberMasterInsDoc AFTER UPDATE ON tbl_MHA_MemberMasterInsDoc FOR EACH ROW BEGIN
  DECLARE Changes JSON DEFAULT JSON_OBJECT();

  IF ISNULL(OLD.mbrminsdocUUID) != ISNULL(NEW.mbrminsdocUUID) OR OLD.mbrminsdocUUID != NEW.mbrminsdocUUID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrminsdocUUID", IF(ISNULL(OLD.mbrminsdocUUID), NULL, OLD.mbrminsdocUUID))); END IF;
  IF ISNULL(OLD.mbrminsdocMemberID) != ISNULL(NEW.mbrminsdocMemberID) OR OLD.mbrminsdocMemberID != NEW.mbrminsdocMemberID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrminsdocMemberID", IF(ISNULL(OLD.mbrminsdocMemberID), NULL, OLD.mbrminsdocMemberID))); END IF;
  IF ISNULL(OLD.mbrminsdocDocNumber) != ISNULL(NEW.mbrminsdocDocNumber) OR OLD.mbrminsdocDocNumber != NEW.mbrminsdocDocNumber THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrminsdocDocNumber", IF(ISNULL(OLD.mbrminsdocDocNumber), NULL, OLD.mbrminsdocDocNumber))); END IF;
  IF ISNULL(OLD.mbrminsdocDocDate) != ISNULL(NEW.mbrminsdocDocDate) OR OLD.mbrminsdocDocDate != NEW.mbrminsdocDocDate THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrminsdocDocDate", IF(ISNULL(OLD.mbrminsdocDocDate), NULL, OLD.mbrminsdocDocDate))); END IF;
  IF ISNULL(OLD.mbrminsdocHistory) != ISNULL(NEW.mbrminsdocHistory) OR OLD.mbrminsdocHistory != NEW.mbrminsdocHistory THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrminsdocHistory", IF(ISNULL(OLD.mbrminsdocHistory), NULL, OLD.mbrminsdocHistory))); END IF;
  IF ISNULL(OLD.mbrminsdocStatus) != ISNULL(NEW.mbrminsdocStatus) OR OLD.mbrminsdocStatus != NEW.mbrminsdocStatus THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrminsdocStatus", IF(ISNULL(OLD.mbrminsdocStatus), NULL, OLD.mbrminsdocStatus))); END IF;

  IF JSON_LENGTH(Changes) > 0 THEN
--    IF ISNULL(NEW.mbrminsdocUpdatedBy) THEN
--      SIGNAL SQLSTATE "45401"
--         SET MESSAGE_TEXT = "UpdatedBy is not set";
--    END IF;

    INSERT INTO tbl_SYS_ActionLogs
        SET atlBy     = NEW.mbrminsdocUpdatedBy
          , atlAction = "UPDATE"
          , atlTarget = "tbl_MHA_MemberMasterInsDoc"
          , atlInfo   = JSON_OBJECT("mbrminsdocID", OLD.mbrminsdocID, "old", Changes);
  END IF;
END
SQL
        );

        $this->execute("DROP TRIGGER IF EXISTS `trg_updatelog_tbl_MHA_MemberSupplementaryInsDoc`;");
        $this->execute(<<<SQL
CREATE TRIGGER trg_updatelog_tbl_MHA_MemberSupplementaryInsDoc AFTER UPDATE ON tbl_MHA_MemberSupplementaryInsDoc FOR EACH ROW BEGIN
  DECLARE Changes JSON DEFAULT JSON_OBJECT();

  IF ISNULL(OLD.mbrsinsdocUUID) != ISNULL(NEW.mbrsinsdocUUID) OR OLD.mbrsinsdocUUID != NEW.mbrsinsdocUUID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrsinsdocUUID", IF(ISNULL(OLD.mbrsinsdocUUID), NULL, OLD.mbrsinsdocUUID))); END IF;
  IF ISNULL(OLD.mbrsinsdocMemberID) != ISNULL(NEW.mbrsinsdocMemberID) OR OLD.mbrsinsdocMemberID != NEW.mbrsinsdocMemberID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrsinsdocMemberID", IF(ISNULL(OLD.mbrsinsdocMemberID), NULL, OLD.mbrsinsdocMemberID))); END IF;
  IF ISNULL(OLD.mbrsinsdocSupplementaryInsurerID) != ISNULL(NEW.mbrsinsdocSupplementaryInsurerID) OR OLD.mbrsinsdocSupplementaryInsurerID != NEW.mbrsinsdocSupplementaryInsurerID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrsinsdocSupplementaryInsurerID", IF(ISNULL(OLD.mbrsinsdocSupplementaryInsurerID), NULL, OLD.mbrsinsdocSupplementaryInsurerID))); END IF;
  IF ISNULL(OLD.mbrsinsdocDocNumber) != ISNULL(NEW.mbrsinsdocDocNumber) OR OLD.mbrsinsdocDocNumber != NEW.mbrsinsdocDocNumber THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrsinsdocDocNumber", IF(ISNULL(OLD.mbrsinsdocDocNumber), NULL, OLD.mbrsinsdocDocNumber))); END IF;
  IF ISNULL(OLD.mbrsinsdocDocDate) != ISNULL(NEW.mbrsinsdocDocDate) OR OLD.mbrsinsdocDocDate != NEW.mbrsinsdocDocDate THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrsinsdocDocDate", IF(ISNULL(OLD.mbrsinsdocDocDate), NULL, OLD.mbrsinsdocDocDate))); END IF;
  IF ISNULL(OLD.mbrsinsdocHistory) != ISNULL(NEW.mbrsinsdocHistory) OR OLD.mbrsinsdocHistory != NEW.mbrsinsdocHistory THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrsinsdocHistory", IF(ISNULL(OLD.mbrsinsdocHistory), NULL, OLD.mbrsinsdocHistory))); END IF;
  IF ISNULL(OLD.mbrsinsdocStatus) != ISNULL(NEW.mbrsinsdocStatus) OR OLD.mbrsinsdocStatus != NEW.mbrsinsdocStatus THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrsinsdocStatus", IF(ISNULL(OLD.mbrsinsdocStatus), NULL, OLD.mbrsinsdocStatus))); END IF;

  IF JSON_LENGTH(Changes) > 0 THEN
--    IF ISNULL(NEW.mbrsinsdocUpdatedBy) THEN
--      SIGNAL SQLSTATE "45401"
--         SET MESSAGE_TEXT = "UpdatedBy is not set";
--    END IF;

    INSERT INTO tbl_SYS_ActionLogs
        SET atlBy     = NEW.mbrsinsdocUpdatedBy
          , atlAction = "UPDATE"
          , atlTarget = "tbl_MHA_MemberSupplementaryInsDoc"
          , atlInfo   = JSON_OBJECT("mbrsinsdocID", OLD.mbrsinsdocID, "old", Changes);
  END IF;
END
SQL
        );

    }

    public function safeDown()
    {
        echo "m230728_152547_mha_merge_History_tables_to_master_field_asjson cannot be reverted.\n";
        return false;
    }

}
