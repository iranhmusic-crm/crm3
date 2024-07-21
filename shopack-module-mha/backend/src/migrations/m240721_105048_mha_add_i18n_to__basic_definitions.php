<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m240721_105048_mha_add_i18n_to__basic_definitions extends Migration
{
	public function safeUp()
	{
    $this->execute(<<<SQL
ALTER TABLE `tbl_MHA_BasicDefinition`
	ADD COLUMN `bdfI18NData` JSON NULL AFTER `bdfName`;
SQL
		);

    $this->alterColumn('tbl_MHA_BasicDefinition', 'bdfI18NData', $this->json());

		$this->execute("DROP TRIGGER IF EXISTS `trg_updatelog_tbl_MHA_BasicDefinition`;");
    $this->execute(<<<SQL
CREATE TRIGGER trg_updatelog_tbl_MHA_BasicDefinition AFTER UPDATE ON tbl_MHA_BasicDefinition FOR EACH ROW BEGIN
  DECLARE Changes JSON DEFAULT JSON_OBJECT();

  IF ISNULL(OLD.bdfUUID) != ISNULL(NEW.bdfUUID) OR OLD.bdfUUID != NEW.bdfUUID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("bdfUUID", IF(ISNULL(OLD.bdfUUID), NULL, OLD.bdfUUID))); END IF;
  IF ISNULL(OLD.bdfType) != ISNULL(NEW.bdfType) OR OLD.bdfType != NEW.bdfType THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("bdfType", IF(ISNULL(OLD.bdfType), NULL, OLD.bdfType))); END IF;
  IF ISNULL(OLD.bdfName) != ISNULL(NEW.bdfName) OR OLD.bdfName != NEW.bdfName THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("bdfName", IF(ISNULL(OLD.bdfName), NULL, OLD.bdfName))); END IF;
  IF ISNULL(OLD.bdfI18NData) != ISNULL(NEW.bdfI18NData) OR OLD.bdfI18NData != NEW.bdfI18NData THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("bdfI18NData", IF(ISNULL(OLD.bdfI18NData), NULL, OLD.bdfI18NData))); END IF;
  IF ISNULL(OLD.bdfStatus) != ISNULL(NEW.bdfStatus) OR OLD.bdfStatus != NEW.bdfStatus THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("bdfStatus", IF(ISNULL(OLD.bdfStatus), NULL, OLD.bdfStatus))); END IF;

  IF JSON_LENGTH(Changes) > 0 THEN
--    IF ISNULL(NEW.bdfUpdatedBy) THEN
--      SIGNAL SQLSTATE "45401"
--         SET MESSAGE_TEXT = "UpdatedBy is not set";
--    END IF;

    INSERT INTO tbl_SYS_ActionLogs
        SET atlBy     = NEW.bdfUpdatedBy
          , atlAction = "UPDATE"
          , atlTarget = "tbl_MHA_BasicDefinition"
          , atlInfo   = JSON_OBJECT("bdfID", OLD.bdfID, "old", Changes);
  END IF;
END
SQL
		);

	}

	public function safeDown()
	{
		echo "m240721_105048_mha_add_i18n_to__basic_definitions cannot be reverted.\n";
		return false;
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{
	}

	public function down()
	{
		echo "m240721_105048_mha_add_i18n_to__basic_definitions cannot be reverted.\n";
		return false;
	}
	*/

}
