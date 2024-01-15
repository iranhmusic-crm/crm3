<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m230331_063403_mha_make_mbrRegisterCode_nullable extends Migration
{
	public function safeUp()
	{
		$this->execute(<<<SQL
ALTER TABLE `tbl_MHA_Member`
	CHANGE COLUMN `mbrRegisterCode` `mbrRegisterCode` BIGINT(20) UNSIGNED NULL AFTER `mbrUserID`;
SQL
		);
}

	public function safeDown()
	{
		echo "m230331_063403_mha_make_mbrRegisterCode_nullable cannot be reverted.\n";
		return false;
	}

}
