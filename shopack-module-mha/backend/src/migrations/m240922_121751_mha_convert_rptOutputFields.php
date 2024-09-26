<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m240922_121751_mha_convert_rptOutputFields extends Migration
{
	public function safeUp()
	{
		$this->execute(<<<SQL
UPDATE tbl_MHA_Report
SET rptOutputFields = REPLACE(rptOutputFields, '"mbrUserID"', '"user.usrID"')
SQL
		);

		$this->execute(<<<SQL
UPDATE tbl_MHA_Report
SET rptOutputFields = REPLACE(rptOutputFields, '"usr', '"user.usr')
SQL
		);

		$this->execute(<<<SQL
UPDATE tbl_MHA_Report
SET rptOutputFields = REPLACE(rptOutputFields, '"hasPassword"', '"user.hasPassword"')
SQL
		);

		$this->execute(<<<SQL
UPDATE tbl_MHA_Report
SET rptOutputFields = REPLACE(rptOutputFields, '"knnName"', '"kanoonNames"')
SQL
		);

		$this->execute(<<<SQL
UPDATE tbl_MHA_Report
SET rptOutputFields = REPLACE(rptOutputFields, '"mbrknnMembershipDegree"', '"kanoonDegrees"')
SQL
		);

		//convert to array
		$this->execute(<<<SQL
UPDATE tbl_MHA_Report
SET rptOutputFields = JSON_KEYS(rptOutputFields)
WHERE LEFT(rptOutputFields, 1) = '{'
SQL
		);

	}

	public function safeDown()
	{
		echo "m240922_121751_mha_convert_rptOutputFields cannot be reverted.\n";
		return false;
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{
	}

	public function down()
	{
		echo "m240922_121751_mha_convert_rptOutputFields cannot be reverted.\n";
		return false;
	}
	*/

}
