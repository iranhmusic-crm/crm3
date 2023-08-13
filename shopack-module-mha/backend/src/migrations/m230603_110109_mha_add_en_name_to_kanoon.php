<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

/**
 * Class m230603_110109_mha_add_en_name_to_kanoon
 */
class m230603_110109_mha_add_en_name_to_kanoon extends Migration
{
	public function safeUp()
	{
		$this->execute(<<<SQLSTR
ALTER TABLE `tbl_MHA_Kanoon`
	ADD COLUMN `knnNameEn` VARCHAR(128) NULL AFTER `knnName`;
SQLSTR
		);

	}

	public function safeDown()
	{
		echo "m230603_110109_mha_add_en_name_to_kanoon cannot be reverted.\n";
		return false;
	}

}
