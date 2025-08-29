<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

/* this will be applied after m240619_000000_accounting_fix_saleable_beforeinsert_trigger@mha */
class m240619_070637_mha_fix_saleable_beforeinsert_trigger extends Migration
{
	public function safeUp()
	{
    //nothing to do
	}

	public function safeDown()
	{
		echo "m240619_070637_mha_fix_saleable_beforeinsert_trigger cannot be reverted.\n";
		return false;
	}

}
