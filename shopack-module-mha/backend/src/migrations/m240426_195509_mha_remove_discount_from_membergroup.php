<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m240426_195509_mha_remove_discount_from_membergroup extends Migration
{
	public function safeUp()
	{
		$this->execute(<<<SQL
ALTER TABLE `tbl_MHA_MemberGroup`
	DROP COLUMN `mgpMembershipDiscountAmount`,
	DROP COLUMN `mgpMembershipDiscountType`,
	DROP COLUMN `mgpMembershipCardDiscountAmount`,
	DROP COLUMN `mgpMembershipCardDiscountType`,
	DROP COLUMN `mgpDeliveryDiscountAmount`,
	DROP COLUMN `mgpDeliveryDiscountType`;
SQL
		);

		$this->execute("DROP TRIGGER IF EXISTS trg_updatelog_tbl_MHA_MemberGroup;");
		$this->execute(<<<SQL
CREATE TRIGGER trg_updatelog_tbl_MHA_MemberGroup AFTER UPDATE ON tbl_MHA_MemberGroup FOR EACH ROW BEGIN
  DECLARE Changes JSON DEFAULT JSON_OBJECT();

  IF ISNULL(OLD.mgpI18NData) != ISNULL(NEW.mgpI18NData) OR OLD.mgpI18NData != NEW.mgpI18NData THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("mgpI18NData", IF(ISNULL(OLD.mgpI18NData), NULL, OLD.mgpI18NData))); END IF;
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
SQL
		);

	}

	public function safeDown()
	{
		echo "m240426_195509_mha_remove_discount_from_membergroup cannot be reverted.\n";
		return false;
	}

}
