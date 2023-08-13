<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

/**
 * Class m230529_130257_mha_add_guid_column_to_all
 */
class m230529_130257_mha_add_guid_column_to_all extends Migration
{
	public function addUUIDTo($tableName, $prefix, $idFieldSuffix = 'ID')
	{
		$this->execute(<<<SQLSTR
ALTER TABLE `{$tableName}`
	ADD COLUMN `{$prefix}UUID` VARCHAR(38) NULL AFTER `{$prefix}{$idFieldSuffix}`;
SQLSTR
		);

		$this->execute(<<<SQLSTR
UPDATE `{$tableName}`
	SET `{$prefix}UUID` = LOWER(UUID())
	WHERE `{$prefix}UUID` IS NULL;
SQLSTR
		);

		$this->execute(<<<SQLSTR
ALTER TABLE `{$tableName}`
	CHANGE COLUMN `{$prefix}UUID` `{$prefix}UUID` VARCHAR(38) NOT NULL COLLATE 'utf8mb4_unicode_ci' AFTER `{$prefix}{$idFieldSuffix}`,
	ADD UNIQUE INDEX `{$prefix}UUID` (`{$prefix}UUID`);
SQLSTR
		);
	}

	public function safeUp()
	{
		$this->addUUIDTo('tbl_MHA_BasicDefinition',										'bdf');
		$this->addUUIDTo('tbl_MHA_Document',													'doc');
		$this->addUUIDTo('tbl_MHA_Kanoon',														'knn');
		$this->addUUIDTo('tbl_MHA_MasterInsurer',											'mins');
		$this->addUUIDTo('tbl_MHA_MasterInsurerType',									'minstyp');
		$this->addUUIDTo('tbl_MHA_Member',														'mbr', 'UserID');
		$this->addUUIDTo('tbl_MHA_MemberMasterInsDoc',								'mbrminsdoc');
		$this->addUUIDTo('tbl_MHA_MemberMasterInsDocHistory',					'mbrminsdochst');
		$this->addUUIDTo('tbl_MHA_MemberMasterInsuranceHistory',			'mbrminshst');
		$this->addUUIDTo('tbl_MHA_MemberMembership',									'mbrshp');
		$this->addUUIDTo('tbl_MHA_Membership',												'mshp');
		$this->addUUIDTo('tbl_MHA_MemberSponsorship',									'mbrsps');
		$this->addUUIDTo('tbl_MHA_MemberSupplementaryInsDoc',					'mbrsinsdoc');
		$this->addUUIDTo('tbl_MHA_MemberSupplementaryInsDocHistory',	'mbrsinsdochst');
		$this->addUUIDTo('tbl_MHA_Member_Document',										'mbrdoc');
		$this->addUUIDTo('tbl_MHA_Member_Kanoon',											'mbrknn');
		$this->addUUIDTo('tbl_MHA_Member_Specialty',									'mbrspc');
		$this->addUUIDTo('tbl_MHA_Specialty',													'spc');
		$this->addUUIDTo('tbl_MHA_SupplementaryInsurer',							'sins');
	}

	public function safeDown()
	{
		echo "m230529_130257_mha_add_guid_column_to_all cannot be reverted.\n";
		return false;
	}

}
