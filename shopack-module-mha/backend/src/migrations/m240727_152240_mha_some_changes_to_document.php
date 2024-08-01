<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m240727_152240_mha_some_changes_to_document extends Migration
{
	public function safeUp()
	{
		throw new \Exception('not completed yet!');





		//tbl_MHA_BasicDefinition
    $this->execute(<<<SQL
ALTER TABLE `tbl_MHA_BasicDefinition`
	ADD UNIQUE INDEX `bdfType_bdfName` (`bdfType`, `bdfName`);
SQL
		);

    $this->execute(<<<SQL
ALTER TABLE `tbl_MHA_BasicDefinition`
	CHANGE COLUMN `bdfType` `bdfType` CHAR(1) NOT NULL COMMENT 'I:Instrument, S:Sing, R:Research, D:Member Document Reject Reason' COLLATE 'utf8mb4_unicode_ci' AFTER `bdfUUID`;
SQL
		);

    $this->execute(<<<SQL
INSERT IGNORE INTO `tbl_MHA_BasicDefinition` (bdfUUID, bdfType, bdfName, bdfI18NData)
     VALUES (UUID(), 'D', 'تصویر اشتباه است',      '{"en": {"bdfName": "Invalid Image"}}')
          , (UUID(), 'D', 'تاریخ صدور اشتباه است', '{"en": {"bdfName": "Invalid Issue Date"}}')
;
SQL
		);

		//tbl_MHA_Document
    $this->execute(<<<SQL
ALTER TABLE `tbl_MHA_Document`
	ADD COLUMN `docExtraParamsSchema` JSON NULL AFTER `docType`;
SQL
		);
    $this->alterColumn('tbl_MHA_Document', 'docExtraParamsSchema', $this->json());

		//tbl_MHA_Member_Document
    $this->execute(<<<SQL
ALTER TABLE `tbl_MHA_Member_Document`
	ADD COLUMN `mbrdocExtraParams` JSON NULL AFTER `mbrdocFileID`,
	ADD COLUMN `mbrdocRejectReasonIDs` JSON NULL AFTER `mbrdocComment`;
SQL
		);
    $this->alterColumn('tbl_MHA_Member_Document', 'mbrdocExtraParams', $this->json());
    $this->alterColumn('tbl_MHA_Member_Document', 'mbrdocRejectReasonIDs', $this->json());

	}

	public function safeDown()
	{
		echo "m240727_152240_mha_some_changes_to_document cannot be reverted.\n";
		return false;
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{
	}

	public function down()
	{
		echo "m240727_152240_mha_some_changes_to_document cannot be reverted.\n";
		return false;
	}
	*/

}
