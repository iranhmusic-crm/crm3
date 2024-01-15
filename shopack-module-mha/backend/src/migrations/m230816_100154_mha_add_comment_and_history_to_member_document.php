<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m230816_100154_mha_add_comment_and_history_to_member_document extends Migration
{
    public function safeUp()
    {
        $this->execute(<<<SQL
ALTER TABLE `tbl_MHA_Member_Document`
    ADD COLUMN `mbrdocComment` TEXT NULL AFTER `mbrdocFileID`,
    ADD COLUMN `mbrdocHistory` JSON NULL AFTER `mbrdocComment`;
SQL
        );
        $this->alterColumn('tbl_MHA_Member_Document', 'mbrdocHistory', $this->json());

        $this->execute("DROP TRIGGER IF EXISTS `trg_tbl_MHA_Member_Document_before_insert`;");
        $this->execute(<<<SQL
CREATE TRIGGER `trg_tbl_MHA_Member_Document_before_insert` BEFORE INSERT ON `tbl_MHA_Member_Document` FOR EACH ROW BEGIN
    DECLARE Changes JSON;

    SET Changes = JSON_OBJECT(
        'at', UNIX_TIMESTAMP(),
        'status', NEW.mbrdocStatus
    );

    IF NEW.mbrdocComment IS NOT NULL THEN
        SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT('comment', NEW.mbrdocComment));
    END IF;

    SET NEW.mbrdocHistory = JSON_MERGE_PRESERVE(COALESCE(NEW.mbrdocHistory, '[]'), Changes);
END
SQL
        );

        $this->execute("DROP TRIGGER IF EXISTS `trg_tbl_MHA_Member_Document_before_update`;");
        $this->execute(<<<SQL
CREATE TRIGGER `trg_tbl_MHA_Member_Document_before_update` BEFORE UPDATE ON `tbl_MHA_Member_Document` FOR EACH ROW BEGIN
    DECLARE Changes JSON;

    IF (IFNULL(NEW.mbrdocStatus, '') != IFNULL(OLD.mbrdocStatus, '')
        OR IFNULL(NEW.mbrdocComment, '') != IFNULL(OLD.mbrdocComment, ''))
    THEN
        SET Changes = JSON_OBJECT(
            'at', UNIX_TIMESTAMP(),
            'status', NEW.mbrdocStatus
        );

        IF NEW.mbrdocComment IS NOT NULL THEN
            SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT('comment', NEW.mbrdocComment));
        END IF;

        SET NEW.mbrdocHistory = JSON_MERGE_PRESERVE(COALESCE(OLD.mbrdocHistory, '[]'),
            Changes);
    END IF;
END
SQL
        );

        $this->execute("DROP TRIGGER IF EXISTS trg_updatelog_tbl_MHA_Member_Document;");
        $this->execute(<<<SQL
CREATE TRIGGER trg_updatelog_tbl_MHA_Member_Document AFTER UPDATE ON tbl_MHA_Member_Document FOR EACH ROW BEGIN
  DECLARE Changes JSON DEFAULT JSON_OBJECT();

  IF ISNULL(OLD.mbrdocUUID) != ISNULL(NEW.mbrdocUUID) OR OLD.mbrdocUUID != NEW.mbrdocUUID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrdocUUID", IF(ISNULL(OLD.mbrdocUUID), NULL, OLD.mbrdocUUID))); END IF;
  IF ISNULL(OLD.mbrdocMemberID) != ISNULL(NEW.mbrdocMemberID) OR OLD.mbrdocMemberID != NEW.mbrdocMemberID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrdocMemberID", IF(ISNULL(OLD.mbrdocMemberID), NULL, OLD.mbrdocMemberID))); END IF;
  IF ISNULL(OLD.mbrdocDocumentID) != ISNULL(NEW.mbrdocDocumentID) OR OLD.mbrdocDocumentID != NEW.mbrdocDocumentID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrdocDocumentID", IF(ISNULL(OLD.mbrdocDocumentID), NULL, OLD.mbrdocDocumentID))); END IF;
  IF ISNULL(OLD.mbrdocTitle) != ISNULL(NEW.mbrdocTitle) OR OLD.mbrdocTitle != NEW.mbrdocTitle THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrdocTitle", IF(ISNULL(OLD.mbrdocTitle), NULL, OLD.mbrdocTitle))); END IF;
  IF ISNULL(OLD.mbrdocFileID) != ISNULL(NEW.mbrdocFileID) OR OLD.mbrdocFileID != NEW.mbrdocFileID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrdocFileID", IF(ISNULL(OLD.mbrdocFileID), NULL, OLD.mbrdocFileID))); END IF;
  IF ISNULL(OLD.mbrdocComment) != ISNULL(NEW.mbrdocComment) OR OLD.mbrdocComment != NEW.mbrdocComment THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrdocComment", IF(ISNULL(OLD.mbrdocComment), NULL, OLD.mbrdocComment))); END IF;
  IF ISNULL(OLD.mbrdocHistory) != ISNULL(NEW.mbrdocHistory) OR OLD.mbrdocHistory != NEW.mbrdocHistory THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrdocHistory", IF(ISNULL(OLD.mbrdocHistory), NULL, OLD.mbrdocHistory))); END IF;
  IF ISNULL(OLD.mbrdocStatus) != ISNULL(NEW.mbrdocStatus) OR OLD.mbrdocStatus != NEW.mbrdocStatus THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrdocStatus", IF(ISNULL(OLD.mbrdocStatus), NULL, OLD.mbrdocStatus))); END IF;

  IF JSON_LENGTH(Changes) > 0 THEN
--    IF ISNULL(NEW.mbrdocUpdatedBy) THEN
--      SIGNAL SQLSTATE "45401"
--         SET MESSAGE_TEXT = "UpdatedBy is not set";
--    END IF;

    INSERT INTO tbl_SYS_ActionLogs
        SET atlBy     = NEW.mbrdocUpdatedBy
          , atlAction = "UPDATE"
          , atlTarget = "tbl_MHA_Member_Document"
          , atlInfo   = JSON_OBJECT("mbrdocID", OLD.mbrdocID, "old", Changes);
  END IF;
END
SQL
        );

    }

    public function safeDown()
    {
        echo "m230816_100154_mha_add_comment_and_history_to_member_document cannot be reverted.\n";
        return false;
    }

}
