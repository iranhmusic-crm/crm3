<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m240102_060725_mha_create_discountgroup_and_referrer extends Migration
{
	public function safeUp()
	{
    $this->execute(<<<SQLSTR
CREATE TABLE `tbl_MHA_Accounting_DiscountGroup` (
	`dscgrpID` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	`dscgrpUUID` VARCHAR(38) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`dscgrpName` VARCHAR(128) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`dscgrpCreatedAt` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
	`dscgrpCreatedBy` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
	`dscgrpUpdatedAt` DATETIME NULL DEFAULT NULL,
	`dscgrpUpdatedBy` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
	`dscgrpRemovedAt` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`dscgrpRemovedBy` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
	PRIMARY KEY (`dscgrpID`) USING BTREE,
	UNIQUE INDEX `dscgrpUUID` (`dscgrpUUID`) USING BTREE,
	UNIQUE INDEX `dscgrpName` (`dscgrpName`) USING BTREE,
	INDEX `dscgrpCreatedAt` (`dscgrpCreatedAt`) USING BTREE
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
;
SQLSTR
    );

    $this->execute(<<<SQLSTR
ALTER TABLE `tbl_MHA_Accounting_Discount`
  ADD COLUMN `dscDiscountGroupID` SMALLINT UNSIGNED NULL AFTER `dscType`;
SQLSTR
    );

    $this->execute(<<<SQLSTR
ALTER TABLE `tbl_MHA_Accounting_Discount`
	ADD CONSTRAINT `FK_tbl_MHA_Accounting_Discount_tbl_MHA_Accounting_DiscountGroup` FOREIGN KEY (`dscDiscountGroupID`) REFERENCES `tbl_MHA_Accounting_DiscountGroup` (`dscgrpID`) ON UPDATE NO ACTION ON DELETE NO ACTION;
SQLSTR
    );

    $this->execute(<<<SQLSTR
SQLSTR
    );

    $this->execute(<<<SQLSTR
SQLSTR
    );

    $this->execute(<<<SQLSTR
SQLSTR
    );







    $this->execute(<<<SQLSTR
ALTER TABLE `tbl_MHA_Accounting_Discount`
	ADD COLUMN `dscReferrers` JSON NULL DEFAULT NULL AFTER `dscTargetSaleableIDs`;
SQLSTR
    );
    $this->alterColumn('tbl_MHA_Accounting_Discount', 'dscReferrers', $this->json());

    $this->execute("DROP TRIGGER IF EXISTS trg_updatelog_tbl_MHA_Accounting_Discount;");
    $this->execute(<<<SQLSTR
CREATE TRIGGER trg_updatelog_tbl_MHA_Accounting_Discount AFTER UPDATE ON tbl_MHA_Accounting_Discount FOR EACH ROW BEGIN
  DECLARE Changes JSON DEFAULT JSON_OBJECT();

  IF ISNULL(OLD.dscAmount) != ISNULL(NEW.dscAmount) OR OLD.dscAmount != NEW.dscAmount THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("dscAmount", IF(ISNULL(OLD.dscAmount), NULL, OLD.dscAmount))); END IF;
  IF ISNULL(OLD.dscAmountType) != ISNULL(NEW.dscAmountType) OR OLD.dscAmountType != NEW.dscAmountType THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("dscAmountType", IF(ISNULL(OLD.dscAmountType), NULL, OLD.dscAmountType))); END IF;
  IF ISNULL(OLD.dscCodeHasSerial) != ISNULL(NEW.dscCodeHasSerial) OR OLD.dscCodeHasSerial != NEW.dscCodeHasSerial THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("dscCodeHasSerial", IF(ISNULL(OLD.dscCodeHasSerial), NULL, OLD.dscCodeHasSerial))); END IF;
  IF ISNULL(OLD.dscCodeSerialCount) != ISNULL(NEW.dscCodeSerialCount) OR OLD.dscCodeSerialCount != NEW.dscCodeSerialCount THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("dscCodeSerialCount", IF(ISNULL(OLD.dscCodeSerialCount), NULL, OLD.dscCodeSerialCount))); END IF;
  IF ISNULL(OLD.dscCodeSerialLength) != ISNULL(NEW.dscCodeSerialLength) OR OLD.dscCodeSerialLength != NEW.dscCodeSerialLength THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("dscCodeSerialLength", IF(ISNULL(OLD.dscCodeSerialLength), NULL, OLD.dscCodeSerialLength))); END IF;
  IF ISNULL(OLD.dscCodeString) != ISNULL(NEW.dscCodeString) OR OLD.dscCodeString != NEW.dscCodeString THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("dscCodeString", IF(ISNULL(OLD.dscCodeString), NULL, OLD.dscCodeString))); END IF;
  IF ISNULL(OLD.dscI18NData) != ISNULL(NEW.dscI18NData) OR OLD.dscI18NData != NEW.dscI18NData THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("dscI18NData", IF(ISNULL(OLD.dscI18NData), NULL, OLD.dscI18NData))); END IF;
  IF ISNULL(OLD.dscMaxAmount) != ISNULL(NEW.dscMaxAmount) OR OLD.dscMaxAmount != NEW.dscMaxAmount THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("dscMaxAmount", IF(ISNULL(OLD.dscMaxAmount), NULL, OLD.dscMaxAmount))); END IF;
  IF ISNULL(OLD.dscName) != ISNULL(NEW.dscName) OR OLD.dscName != NEW.dscName THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("dscName", IF(ISNULL(OLD.dscName), NULL, OLD.dscName))); END IF;
  IF ISNULL(OLD.dscPerUserMaxCount) != ISNULL(NEW.dscPerUserMaxCount) OR OLD.dscPerUserMaxCount != NEW.dscPerUserMaxCount THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("dscPerUserMaxCount", IF(ISNULL(OLD.dscPerUserMaxCount), NULL, OLD.dscPerUserMaxCount))); END IF;
  IF ISNULL(OLD.dscPerUserMaxPrice) != ISNULL(NEW.dscPerUserMaxPrice) OR OLD.dscPerUserMaxPrice != NEW.dscPerUserMaxPrice THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("dscPerUserMaxPrice", IF(ISNULL(OLD.dscPerUserMaxPrice), NULL, OLD.dscPerUserMaxPrice))); END IF;
  IF ISNULL(OLD.dscReferrers) != ISNULL(NEW.dscReferrers) OR OLD.dscReferrers != NEW.dscReferrers THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("dscReferrers", IF(ISNULL(OLD.dscReferrers), NULL, OLD.dscReferrers))); END IF;
  IF ISNULL(OLD.dscSaleableBasedMultiplier) != ISNULL(NEW.dscSaleableBasedMultiplier) OR OLD.dscSaleableBasedMultiplier != NEW.dscSaleableBasedMultiplier THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("dscSaleableBasedMultiplier", IF(ISNULL(OLD.dscSaleableBasedMultiplier), NULL, OLD.dscSaleableBasedMultiplier))); END IF;
  IF ISNULL(OLD.dscStatus) != ISNULL(NEW.dscStatus) OR OLD.dscStatus != NEW.dscStatus THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("dscStatus", IF(ISNULL(OLD.dscStatus), NULL, OLD.dscStatus))); END IF;
  IF ISNULL(OLD.dscTargetKanoonIDs) != ISNULL(NEW.dscTargetKanoonIDs) OR OLD.dscTargetKanoonIDs != NEW.dscTargetKanoonIDs THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("dscTargetKanoonIDs", IF(ISNULL(OLD.dscTargetKanoonIDs), NULL, OLD.dscTargetKanoonIDs))); END IF;
  IF ISNULL(OLD.dscTargetMemberGroupIDs) != ISNULL(NEW.dscTargetMemberGroupIDs) OR OLD.dscTargetMemberGroupIDs != NEW.dscTargetMemberGroupIDs THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("dscTargetMemberGroupIDs", IF(ISNULL(OLD.dscTargetMemberGroupIDs), NULL, OLD.dscTargetMemberGroupIDs))); END IF;
  IF ISNULL(OLD.dscTargetProductIDs) != ISNULL(NEW.dscTargetProductIDs) OR OLD.dscTargetProductIDs != NEW.dscTargetProductIDs THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("dscTargetProductIDs", IF(ISNULL(OLD.dscTargetProductIDs), NULL, OLD.dscTargetProductIDs))); END IF;
  IF ISNULL(OLD.dscTargetProductMhaTypes) != ISNULL(NEW.dscTargetProductMhaTypes) OR OLD.dscTargetProductMhaTypes != NEW.dscTargetProductMhaTypes THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("dscTargetProductMhaTypes", IF(ISNULL(OLD.dscTargetProductMhaTypes), NULL, OLD.dscTargetProductMhaTypes))); END IF;
  IF ISNULL(OLD.dscTargetSaleableIDs) != ISNULL(NEW.dscTargetSaleableIDs) OR OLD.dscTargetSaleableIDs != NEW.dscTargetSaleableIDs THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("dscTargetSaleableIDs", IF(ISNULL(OLD.dscTargetSaleableIDs), NULL, OLD.dscTargetSaleableIDs))); END IF;
  IF ISNULL(OLD.dscTargetUserIDs) != ISNULL(NEW.dscTargetUserIDs) OR OLD.dscTargetUserIDs != NEW.dscTargetUserIDs THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("dscTargetUserIDs", IF(ISNULL(OLD.dscTargetUserIDs), NULL, OLD.dscTargetUserIDs))); END IF;
  IF ISNULL(OLD.dscTotalMaxCount) != ISNULL(NEW.dscTotalMaxCount) OR OLD.dscTotalMaxCount != NEW.dscTotalMaxCount THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("dscTotalMaxCount", IF(ISNULL(OLD.dscTotalMaxCount), NULL, OLD.dscTotalMaxCount))); END IF;
  IF ISNULL(OLD.dscTotalMaxPrice) != ISNULL(NEW.dscTotalMaxPrice) OR OLD.dscTotalMaxPrice != NEW.dscTotalMaxPrice THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("dscTotalMaxPrice", IF(ISNULL(OLD.dscTotalMaxPrice), NULL, OLD.dscTotalMaxPrice))); END IF;
  IF ISNULL(OLD.dscTotalUsedCount) != ISNULL(NEW.dscTotalUsedCount) OR OLD.dscTotalUsedCount != NEW.dscTotalUsedCount THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("dscTotalUsedCount", IF(ISNULL(OLD.dscTotalUsedCount), NULL, OLD.dscTotalUsedCount))); END IF;
  IF ISNULL(OLD.dscTotalUsedPrice) != ISNULL(NEW.dscTotalUsedPrice) OR OLD.dscTotalUsedPrice != NEW.dscTotalUsedPrice THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("dscTotalUsedPrice", IF(ISNULL(OLD.dscTotalUsedPrice), NULL, OLD.dscTotalUsedPrice))); END IF;
  IF ISNULL(OLD.dscType) != ISNULL(NEW.dscType) OR OLD.dscType != NEW.dscType THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("dscType", IF(ISNULL(OLD.dscType), NULL, OLD.dscType))); END IF;
  IF ISNULL(OLD.dscUUID) != ISNULL(NEW.dscUUID) OR OLD.dscUUID != NEW.dscUUID THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("dscUUID", IF(ISNULL(OLD.dscUUID), NULL, OLD.dscUUID))); END IF;
  IF ISNULL(OLD.dscValidFrom) != ISNULL(NEW.dscValidFrom) OR OLD.dscValidFrom != NEW.dscValidFrom THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("dscValidFrom", IF(ISNULL(OLD.dscValidFrom), NULL, OLD.dscValidFrom))); END IF;
  IF ISNULL(OLD.dscValidTo) != ISNULL(NEW.dscValidTo) OR OLD.dscValidTo != NEW.dscValidTo THEN SET Changes = JSON_MERGE_PRESERVE(Changes, JSON_OBJECT("dscValidTo", IF(ISNULL(OLD.dscValidTo), NULL, OLD.dscValidTo))); END IF;

  IF JSON_LENGTH(Changes) > 0 THEN
--    IF ISNULL(NEW.dscUpdatedBy) THEN
--      SIGNAL SQLSTATE "45401"
--         SET MESSAGE_TEXT = "UpdatedBy is not set";
--    END IF;

    INSERT INTO tbl_SYS_ActionLogs
        SET atlBy     = NEW.dscUpdatedBy
          , atlAction = "UPDATE"
          , atlTarget = "tbl_MHA_Accounting_Discount"
          , atlInfo   = JSON_OBJECT("dscID", OLD.dscID, "old", Changes);
  END IF;
END
SQLSTR
    );

	}

	public function safeDown()
	{
		echo "m240102_060725_mha_create_discountgroup_and_referrer cannot be reverted.\n";
		return false;
	}

}
