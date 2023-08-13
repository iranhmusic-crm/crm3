<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

/**
 * Class m230511_063849_mha_add_desc_to_tblspecialty
 */
class m230511_063849_mha_add_desc_to_tblspecialty extends Migration
{
	public function safeUp()
	{
		$this->execute(<<<SQLSTR
ALTER TABLE `tbl_MHA_Specialty`
	ADD COLUMN `spcDescFieldType` VARCHAR(64) NULL DEFAULT NULL AFTER `spcImage`,
	ADD COLUMN `spcDescFieldLabel` VARCHAR(64) NULL DEFAULT NULL AFTER `spcDescFieldType`;
SQLSTR
		);

		$this->execute(<<<SQLSTR
UPDATE tbl_MHA_Member_Specialty
	SET mbrspcDesc = JSON_OBJECT('desc', mbrspcDesc)
	WHERE mbrspcDesc IS NOT NULL
	;
SQLSTR
		);

		$this->execute(<<<SQLSTR
ALTER TABLE `tbl_MHA_Member_Specialty`
	CHANGE COLUMN `mbrspcDesc` `mbrspcDesc` JSON NULL AFTER `mbrspcSpecialtyID`;
SQLSTR
		);
		$this->alterColumn('tbl_MHA_Member_Specialty', 'mbrspcDesc', $this->json());

}

	public function safeDown()
	{
		echo "m230511_063849_mha_add_desc_to_tblspecialty cannot be reverted.\n";
		return false;
	}

}
