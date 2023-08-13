<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

/**
 * Class m230512_131800_mha_add_bdef_to_tblkanoon
 */
class m230512_131800_mha_add_bdef_to_tblkanoon extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->execute(<<<SQLSTR
ALTER TABLE `tbl_MHA_Kanoon`
	ADD COLUMN `knnDescFieldType` VARCHAR(64) NULL AFTER `knnName`,
	ADD COLUMN `knnDescFieldLabel` VARCHAR(64) NULL DEFAULT NULL AFTER `knnDescFieldType`;
SQLSTR
		);

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		echo "m230512_131800_mha_add_bdef_to_tblkanoon cannot be reverted.\n";
		return false;
	}

}
