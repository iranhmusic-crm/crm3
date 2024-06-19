<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m240619_070637_mha_fix_saleable_beforeinsert_trigger extends Migration
{
	public function safeUp()
	{
    $this->execute("DROP TRIGGER IF EXISTS trg_tbl_MHA_Accounting_Saleable_before_insert;");
    $this->execute(<<<SQL
CREATE TRIGGER `trg_tbl_MHA_Accounting_Saleable_before_insert` BEFORE INSERT ON `tbl_MHA_Accounting_Saleable` FOR EACH ROW BEGIN
	IF IFNULL(NEW.slbCode, '') = '' THEN
		SET NEW.slbCode = UUID();
	END IF;
END
SQL
    );
	}

	public function safeDown()
	{
		echo "m240619_070637_mha_fix_saleable_beforeinsert_trigger cannot be reverted.\n";
		return false;
	}

}
