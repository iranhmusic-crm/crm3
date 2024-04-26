<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m230604_070010_mha_add_some_fields_to_member extends Migration
{
	public function safeUp()
	{
		$this->execute(<<<SQL
ALTER TABLE `tbl_MHA_Member`
	ADD COLUMN `mbrOwnOrgName` VARCHAR(1024) NULL DEFAULT NULL AFTER `mbrMusicEducationHistory`;
SQL
		);

	}

	public function safeDown()
	{
		echo "m230604_070010_mha_add_some_fields_to_member cannot be reverted.\n";
		return false;
	}

}
