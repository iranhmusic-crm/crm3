<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m231113_162951_mha_rename_coupon_to_discount extends Migration
{
	public function safeUp()
	{
		$this->execute(<<<SQLSTR
RENAME TABLE `tbl_MHA_Accounting_Coupon` TO `tbl_MHA_Accounting_Discount`;
SQLSTR
    );

		$this->execute(<<<SQLSTR
ALTER TABLE `tbl_MHA_Accounting_Discount`
	CHANGE COLUMN `cpnID` `dscID` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT FIRST,
	CHANGE COLUMN `cpnUUID` `dscUUID` VARCHAR(38) NOT NULL COLLATE 'utf8mb4_unicode_ci' AFTER `dscID`,
	CHANGE COLUMN `cpnCode` `dscCode` VARCHAR(32) NOT NULL COLLATE 'utf8mb4_unicode_ci' AFTER `dscUUID`,
	CHANGE COLUMN `cpnName` `dscName` VARCHAR(64) NOT NULL COLLATE 'utf8mb4_unicode_ci' AFTER `dscCode`,
	CHANGE COLUMN `cpnPrimaryCount` `dscPrimaryCount` INT(10) UNSIGNED NOT NULL AFTER `dscName`,
	CHANGE COLUMN `cpnTotalMaxAmount` `dscTotalMaxAmount` INT(10) UNSIGNED NOT NULL AFTER `dscPrimaryCount`,
	CHANGE COLUMN `cpnPerUserMaxCount` `dscPerUserMaxCount` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `dscTotalMaxAmount`,
	CHANGE COLUMN `cpnPerUserMaxAmount` `dscPerUserMaxAmount` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `dscPerUserMaxCount`,
	CHANGE COLUMN `cpnValidFrom` `dscValidFrom` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `dscPerUserMaxAmount`,
	CHANGE COLUMN `cpnValidTo` `dscValidTo` DATETIME NULL DEFAULT NULL AFTER `dscValidFrom`,
	CHANGE COLUMN `cpnAmount` `dscAmount` INT(10) UNSIGNED NOT NULL AFTER `dscValidTo`,
	CHANGE COLUMN `cpnAmountType` `dscAmountType` CHAR(1) NOT NULL DEFAULT '%' COMMENT '%:Percent, $:Amount, Z:Free' COLLATE 'utf8mb4_unicode_ci' AFTER `dscAmount`,
	CHANGE COLUMN `cpnMaxAmount` `dscMaxAmount` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `dscAmountType`,
	CHANGE COLUMN `cpnSaleableBasedMultiplier` `dscSaleableBasedMultiplier` JSON NULL DEFAULT NULL AFTER `dscMaxAmount`,
	CHANGE COLUMN `cpnTotalUsedCount` `dscTotalUsedCount` INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER `dscSaleableBasedMultiplier`,
	CHANGE COLUMN `cpnTotalUsedAmount` `dscTotalUsedAmount` INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER `dscTotalUsedCount`,
	CHANGE COLUMN `cpnI18NData` `dscI18NData` JSON NULL DEFAULT NULL AFTER `dscTotalUsedAmount`,
	CHANGE COLUMN `cpnStatus` `dscStatus` CHAR(1) NOT NULL DEFAULT 'A' COMMENT 'A:Active, R:Removed' COLLATE 'utf8mb4_unicode_ci' AFTER `dscI18NData`,
	CHANGE COLUMN `cpnCreatedAt` `dscCreatedAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `dscStatus`,
	CHANGE COLUMN `cpnCreatedBy` `dscCreatedBy` BIGINT(20) UNSIGNED NULL DEFAULT NULL AFTER `dscCreatedAt`,
	CHANGE COLUMN `cpnUpdatedAt` `dscUpdatedAt` DATETIME NULL DEFAULT NULL AFTER `dscCreatedBy`,
	CHANGE COLUMN `cpnUpdatedBy` `dscUpdatedBy` BIGINT(20) UNSIGNED NULL DEFAULT NULL AFTER `dscUpdatedAt`,
	CHANGE COLUMN `cpnRemovedAt` `dscRemovedAt` INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER `dscUpdatedBy`,
	CHANGE COLUMN `cpnRemovedBy` `dscRemovedBy` BIGINT(20) UNSIGNED NULL DEFAULT NULL AFTER `dscRemovedAt`,
	DROP PRIMARY KEY,
	ADD PRIMARY KEY (`dscID`) USING BTREE,
	DROP INDEX `cpnUUID`,
	ADD UNIQUE INDEX `dscUUID` (`dscUUID`) USING BTREE,
	DROP INDEX `cpnCode_cpnRemovedAt`,
	ADD UNIQUE INDEX `dscCode_dscRemovedAt` (`dscCode`, `dscRemovedAt`) USING BTREE,
	DROP INDEX `cpnValidTo`,
	ADD INDEX `dscValidTo` (`dscValidTo`) USING BTREE,
	DROP INDEX `cpnCreatedBy`,
	ADD INDEX `dscCreatedBy` (`dscCreatedBy`) USING BTREE,
	DROP INDEX `cpnCreatedAt`,
	ADD INDEX `dscCreatedAt` (`dscCreatedAt`) USING BTREE,
	DROP INDEX `cpnUpdatedBy`,
	ADD INDEX `dscUpdatedBy` (`dscUpdatedBy`) USING BTREE,
	DROP INDEX `cpnStatus`,
	ADD INDEX `dscStatus` (`dscStatus`) USING BTREE,
	DROP INDEX `cpnValidFrom`,
	ADD INDEX `dscValidFrom` (`dscValidFrom`) USING BTREE,
	DROP INDEX `cpnType`,
	ADD INDEX `dscType` (`dscAmountType`) USING BTREE;
SQLSTR
    );

		$this->execute(<<<SQLSTR
ALTER TABLE `tbl_MHA_Accounting_Discount`
	CHANGE COLUMN `dscName` `dscName` VARCHAR(64) NOT NULL COLLATE 'utf8mb4_unicode_ci' AFTER `dscUUID`,
	CHANGE COLUMN `dscCode` `dscCode` VARCHAR(32) NULL COLLATE 'utf8mb4_unicode_ci' AFTER `dscName`,
	CHANGE COLUMN `dscPrimaryCount` `dscPrimaryCount` INT(10) UNSIGNED NULL AFTER `dscCode`,
	CHANGE COLUMN `dscTotalMaxAmount` `dscTotalMaxAmount` INT(10) UNSIGNED NULL AFTER `dscPrimaryCount`;
SQLSTR
    );

		$this->execute(<<<SQLSTR
ALTER TABLE `tbl_MHA_Accounting_UserAsset`
	DROP INDEX `FK_tbl_MHA_Accounting_UserAsset_tbl_Coupon`,
	DROP FOREIGN KEY `FK_tbl_MHA_Accounting_UserAsset_tbl_Coupon`;
SQLSTR
    );

		$this->execute(<<<SQLSTR
ALTER TABLE `tbl_MHA_Accounting_UserAsset`
	CHANGE COLUMN `uasCouponID` `uasDiscountID` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `uasVoucherItemInfo`,
	ADD CONSTRAINT `FK_tbl_MHA_Accounting_UserAsset_tbl_MHA_Accounting_Discount` FOREIGN KEY (`uasDiscountID`) REFERENCES `tbl_MHA_Accounting_Discount` (`dscID`) ON UPDATE NO ACTION ON DELETE NO ACTION;
SQLSTR
    );

		$this->execute(<<<SQLSTR
ALTER TABLE `tbl_MHA_Accounting_Discount`
	CHANGE COLUMN `dscPrimaryCount` `dscTotalMaxCount` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `dscCode`;
SQLSTR
    );

		$this->execute(<<<SQLSTR
ALTER TABLE `tbl_MHA_Accounting_Discount`
	CHANGE COLUMN `dscValidFrom` `dscValidFrom` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `dscCode`,
	CHANGE COLUMN `dscValidTo` `dscValidTo` DATETIME NULL DEFAULT NULL AFTER `dscValidFrom`,
	ADD COLUMN `dscTargetUserIDs` JSON NULL AFTER `dscPerUserMaxAmount`,
	ADD COLUMN `dscTargetMemberGroupIDs` JSON NULL AFTER `dscRemovedBy`,
	ADD COLUMN `dscTargetKanoonIDs` JSON NULL AFTER `dscTargetMemberGroupIDs`;
SQLSTR
    );
		///JSON

		$this->execute(<<<SQLSTR
ALTER TABLE `tbl_MHA_Accounting_Discount`
	ADD COLUMN `dscTargetProductIDs` JSON NULL DEFAULT NULL AFTER `dscTargetUserIDs`,
	ADD COLUMN `dscTargetSaleableIDs` JSON NULL DEFAULT NULL AFTER `dscTargetProductIDs`,
	CHANGE COLUMN `dscSaleableBasedMultiplier` `dscSaleableBasedMultiplier` JSON NULL DEFAULT NULL AFTER `dscTargetSaleableIDs`,
	CHANGE COLUMN `dscAmount` `dscAmount` INT(10) UNSIGNED NOT NULL AFTER `dscSaleableBasedMultiplier`,
	CHANGE COLUMN `dscAmountType` `dscAmountType` CHAR(1) NOT NULL DEFAULT '%' COMMENT '%:Percent, $:Amount, Z:Free' COLLATE 'utf8mb4_unicode_ci' AFTER `dscAmount`,
	CHANGE COLUMN `dscMaxAmount` `dscMaxAmount` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `dscAmountType`,
	ADD COLUMN `dscTargetProductMhaTypes` JSON NULL DEFAULT NULL AFTER `dscTargetKanoonIDs`;
SQLSTR
    );
		///JSON

		$this->execute(<<<SQLSTR
ALTER TABLE `tbl_MHA_Accounting_Discount`
	CHANGE COLUMN `dscTotalMaxAmount` `dscTotalMaxPrice` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `dscTotalMaxCount`,
	CHANGE COLUMN `dscPerUserMaxAmount` `dscPerUserMaxPrice` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `dscPerUserMaxCount`,
	CHANGE COLUMN `dscAmount` `dscAmount` DOUBLE UNSIGNED NOT NULL DEFAULT 0 AFTER `dscSaleableBasedMultiplier`,
	CHANGE COLUMN `dscMaxAmount` `dscMaxAmount` DOUBLE UNSIGNED NULL DEFAULT NULL AFTER `dscAmountType`,
	CHANGE COLUMN `dscTotalUsedAmount` `dscTotalUsedPrice` INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER `dscTotalUsedCount`;
SQLSTR
    );

		$this->execute(<<<SQLSTR

SQLSTR
    );

		$this->execute(<<<SQLSTR

SQLSTR
    );

	}

	public function safeDown()
	{
		echo "m231113_162951_mha_rename_coupon_to_discount cannot be reverted.\n";
		return false;
	}

}
