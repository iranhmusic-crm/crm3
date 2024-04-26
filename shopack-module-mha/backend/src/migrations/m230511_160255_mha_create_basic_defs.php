<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

/**
 * Class m230511_160255_mha_create_basic_defs
 */
class m230511_160255_mha_create_basic_defs extends Migration
{
	public function safeUp()
	{
		$this->execute(<<<SQL
CREATE TABLE `tbl_MHA_BasicDefinition` (
	`bdfID` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`bdfType` CHAR(1) NOT NULL COMMENT 'I:Instrument, S:Sing, R:Research' COLLATE 'utf8mb4_unicode_ci',
	`bdfName` VARCHAR(64) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`bdfStatus` CHAR(1) NOT NULL DEFAULT 'A' COMMENT 'A:Active, D:Disable, R:Removed' COLLATE 'utf8mb4_unicode_ci',
	`bdfCreatedAt` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
	`bdfCreatedBy` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
	`bdfUpdatedAt` DATETIME NULL DEFAULT NULL,
	`bdfUpdatedBy` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
	`bdfRemovedAt` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`bdfRemovedBy` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
	PRIMARY KEY (`bdfID`) USING BTREE,
	INDEX `bdfCreatedAt` (`bdfCreatedAt`) USING BTREE
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
;
SQL
		);

		$this->execute(<<<SQL
ALTER TABLE `tbl_MHA_Member_Kanoon`
	ADD COLUMN `mbrknnDesc` JSON NULL AFTER `mbrknnKanoonID`;
SQL
		);
		$this->alterColumn('tbl_MHA_Member_Kanoon', 'mbrknnDesc', $this->json());

}

	public function safeDown()
	{
		echo "m230511_160255_mha_create_basic_defs cannot be reverted.\n";
		return false;
	}

}
