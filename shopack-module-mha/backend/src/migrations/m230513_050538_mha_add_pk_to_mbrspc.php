<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

/**
 * Class m230513_050538_mha_add_pk_to_mbrspc
 */
class m230513_050538_mha_add_pk_to_mbrspc extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->execute(<<<SQLSTR
ALTER TABLE `tbl_MHA_Member_Specialty`
	ADD COLUMN `mbrspcID` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT FIRST,
	DROP PRIMARY KEY,
	ADD PRIMARY KEY (`mbrspcID`) USING BTREE;
SQLSTR
		);

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		echo "m230513_050538_mha_add_pk_to_mbrspc cannot be reverted.\n";
		return false;
	}

}
