<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m230605_075911_mha_add_title_to_mbr_doc extends Migration
{
	public function safeUp()
	{
		$this->execute(<<<SQL
ALTER TABLE `tbl_MHA_Member_Document`
	ADD COLUMN `mbrdocTitle` VARCHAR(256) NULL AFTER `mbrdocDocumentID`;
SQL
		);

	}

	public function safeDown()
	{
		echo "m230605_075911_mha_add_title_to_mbr_doc cannot be reverted.\n";
		return false;
	}

}
