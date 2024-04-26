<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m231112_151014_mha_add_unique_to_mbrknn extends Migration
{
	public function safeUp()
	{
		$this->execute(<<<SQL
ALTER TABLE `tbl_MHA_Member_Kanoon`
	ADD UNIQUE INDEX `mbrknnMemberID_mbrknnKanoonID_mbrknnStatus` (`mbrknnMemberID`, `mbrknnKanoonID`, `mbrknnStatus`);
SQL
    );

	}

	public function safeDown()
	{
		echo "m231112_151014_mha_add_unique_to_mbrknn cannot be reverted.\n";
		return false;
	}

}
