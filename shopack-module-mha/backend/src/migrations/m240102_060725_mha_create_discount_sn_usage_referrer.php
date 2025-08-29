<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

/* this will be applied after m240102_000000_accounting_create_discount_sn_usage_referrer@mha */
class m240102_060725_mha_create_discount_sn_usage_referrer extends Migration
{
	public function safeUp()
	{
    //nothing to do
	}

	public function safeDown()
	{
		echo "m240102_060725_mha_create_discount_sn_usage_referrer cannot be reverted.\n";
		return false;
	}

}
