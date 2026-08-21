<?php

/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m260820_171438_mha_remove_ismater_from_mbrknn extends Migration
{
    public function safeUp()
    {
        $this->execute(
            <<<SQL
ALTER TABLE `tbl_MHA_Member_Kanoon`
	DROP COLUMN `mbrknnIsMaster`;
SQL
        );

        $this->execute("DROP TRIGGER IF EXISTS trg_updatelog_tbl_MHA_Member_Kanoon;");
        $this->execute(
            <<<SQL
CREATE TRIGGER trg_updatelog_tbl_MHA_Member_Kanoon AFTER UPDATE ON tbl_MHA_Member_Kanoon FOR EACH ROW BEGIN
  DECLARE Changes JSON DEFAULT JSON_OBJECT();

  IF ISNULL(OLD.mbrknnAcceptedAt) != ISNULL(NEW.mbrknnAcceptedAt) OR OLD.mbrknnAcceptedAt != NEW.mbrknnAcceptedAt THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrknnAcceptedAt", IF(ISNULL(OLD.mbrknnAcceptedAt), NULL, OLD.mbrknnAcceptedAt))); END IF;
  IF ISNULL(OLD.mbrknnComment) != ISNULL(NEW.mbrknnComment) OR OLD.mbrknnComment != NEW.mbrknnComment THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrknnComment", IF(ISNULL(OLD.mbrknnComment), NULL, OLD.mbrknnComment))); END IF;
  IF ISNULL(OLD.mbrknnHistory) != ISNULL(NEW.mbrknnHistory) OR OLD.mbrknnHistory != NEW.mbrknnHistory THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrknnHistory", IF(ISNULL(OLD.mbrknnHistory), NULL, OLD.mbrknnHistory))); END IF;
  IF ISNULL(OLD.mbrknnKanoonID) != ISNULL(NEW.mbrknnKanoonID) OR OLD.mbrknnKanoonID != NEW.mbrknnKanoonID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrknnKanoonID", IF(ISNULL(OLD.mbrknnKanoonID), NULL, OLD.mbrknnKanoonID))); END IF;
  IF ISNULL(OLD.mbrknnMemberID) != ISNULL(NEW.mbrknnMemberID) OR OLD.mbrknnMemberID != NEW.mbrknnMemberID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrknnMemberID", IF(ISNULL(OLD.mbrknnMemberID), NULL, OLD.mbrknnMemberID))); END IF;
  IF ISNULL(OLD.mbrknnMembershipDegree) != ISNULL(NEW.mbrknnMembershipDegree) OR OLD.mbrknnMembershipDegree != NEW.mbrknnMembershipDegree THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrknnMembershipDegree", IF(ISNULL(OLD.mbrknnMembershipDegree), NULL, OLD.mbrknnMembershipDegree))); END IF;
  IF ISNULL(OLD.mbrknnParams) != ISNULL(NEW.mbrknnParams) OR OLD.mbrknnParams != NEW.mbrknnParams THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrknnParams", IF(ISNULL(OLD.mbrknnParams), NULL, OLD.mbrknnParams))); END IF;
  IF ISNULL(OLD.mbrknnStatus) != ISNULL(NEW.mbrknnStatus) OR OLD.mbrknnStatus != NEW.mbrknnStatus THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrknnStatus", IF(ISNULL(OLD.mbrknnStatus), NULL, OLD.mbrknnStatus))); END IF;
  IF ISNULL(OLD.mbrknnUUID) != ISNULL(NEW.mbrknnUUID) OR OLD.mbrknnUUID != NEW.mbrknnUUID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mbrknnUUID", IF(ISNULL(OLD.mbrknnUUID), NULL, OLD.mbrknnUUID))); END IF;

  IF JSON_LENGTH(Changes) > 0 THEN
--    IF ISNULL(NEW.mbrknnUpdatedBy) THEN
--      SIGNAL SQLSTATE "45401"
--         SET MESSAGE_TEXT = "UpdatedBy is not set";
--    END IF;

    INSERT INTO tbl_SYS_ActionLogs
        SET atlBy     = NEW.mbrknnUpdatedBy
          , atlAction = "UPDATE"
          , atlTarget = "tbl_MHA_Member_Kanoon"
          , atlInfo   = JSON_OBJECT("mbrknnID", OLD.mbrknnID, "old", Changes);
  END IF;
END
SQL
        );
    }

    public function safeDown()
    {
        echo "m260820_171438_mha_remove_ismater_from_mbrknn cannot be reverted.\n";
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
    }

    public function down()
    {
        echo "m260820_171438_mha_remove_ismater_from_mbrknn cannot be reverted.\n";
        return false;
    }
    */
}
