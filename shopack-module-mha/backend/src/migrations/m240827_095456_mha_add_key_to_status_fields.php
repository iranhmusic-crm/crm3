<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m240827_095456_mha_add_key_to_status_fields extends Migration
{
	public function safeUp()
	{
		$this->execute(<<<SQL
ALTER TABLE `tbl_MHA_BasicDefinition`
	ADD INDEX `bdfStatus` (`bdfStatus`);
SQL
		);

		$this->execute(<<<SQL
ALTER TABLE `tbl_MHA_Document`
	ADD INDEX `docStatus` (`docStatus`);
SQL
		);

		$this->execute(<<<SQL
ALTER TABLE `tbl_MHA_Kanoon`
	ADD INDEX `knnStatus` (`knnStatus`);
SQL
		);

		$this->execute(<<<SQL
ALTER TABLE `tbl_MHA_MasterInsurer`
	ADD INDEX `minsStatus` (`minsStatus`);
SQL
		);

		$this->execute(<<<SQL
ALTER TABLE `tbl_MHA_MasterInsurerType`
	ADD INDEX `minstypStatus` (`minstypStatus`);
SQL
		);

		$this->execute(<<<SQL
ALTER TABLE `tbl_MHA_Member`
	ADD INDEX `mbrStatus` (`mbrStatus`);
SQL
		);

		$this->execute(<<<SQL
ALTER TABLE `tbl_MHA_MemberGroup`
	ADD INDEX `mgpStatus` (`mgpStatus`);
SQL
		);

		$this->execute(<<<SQL
ALTER TABLE `tbl_MHA_MemberMasterInsDoc`
	ADD INDEX `mbrminsdocStatus` (`mbrminsdocStatus`);
SQL
		);

		$this->execute(<<<SQL
ALTER TABLE `tbl_MHA_MemberSupplementaryInsDoc`
	ADD INDEX `mbrsinsdocStatus` (`mbrsinsdocStatus`);
SQL
		);

		$this->execute(<<<SQL
ALTER TABLE `tbl_MHA_Member_Document`
	ADD INDEX `mbrdocStatus` (`mbrdocStatus`);
SQL
		);

		$this->execute(<<<SQL
ALTER TABLE `tbl_MHA_Member_Kanoon`
	ADD INDEX `mbrknnStatus` (`mbrknnStatus`);
SQL
		);

		$this->execute(<<<SQL
ALTER TABLE `tbl_MHA_Report`
	ADD INDEX `rptStatus` (`rptStatus`);
SQL
		);

		$this->execute(<<<SQL
ALTER TABLE `tbl_MHA_Specialty`
	ADD INDEX `spcStatus` (`spcStatus`);
SQL
		);

		$this->execute(<<<SQL
ALTER TABLE `tbl_MHA_SupplementaryInsurer`
	ADD INDEX `sinsStatus` (`sinsStatus`);
SQL
		);

	}

	public function safeDown()
	{
		echo "m240827_095456_mha_add_key_to_status_fields cannot be reverted.\n";
		return false;
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{
	}

	public function down()
	{
		echo "m240827_095456_mha_add_key_to_status_fields cannot be reverted.\n";
		return false;
	}
	*/

}
