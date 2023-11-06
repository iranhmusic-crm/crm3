<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m231103_061223_mha_create_membergroup extends Migration
{
	public function safeUp()
	{
		throw new \Exception("not finished yet");








		$this->execute(<<<SQLSTR
CREATE TABLE `tbl_MHA_MemberGroup` (
	`mgpID` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`mgpUUID` VARCHAR(38) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`mgpName` VARCHAR(64) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`mgpMembershipDiscountAmount` DOUBLE UNSIGNED NULL DEFAULT NULL,
	`mgpMembershipDiscountType` CHAR(1) NULL DEFAULT NULL COMMENT '%:Percent, $:Price' COLLATE 'utf8mb4_unicode_ci',
	`mgpMembershipCardDiscountAmount` DOUBLE UNSIGNED NULL DEFAULT NULL,
	`mgpMembershipCardDiscountType` CHAR(1) NULL DEFAULT NULL COMMENT '%:Percent, $:Price' COLLATE 'utf8mb4_unicode_ci',
	`mgpDeliveryDiscountAmount` DOUBLE UNSIGNED NULL DEFAULT NULL,
	`mgpDeliveryDiscountType` CHAR(1) NULL DEFAULT NULL COMMENT '%:Percent, $:Price' COLLATE 'utf8mb4_unicode_ci',
	`mgpI18NData` JSON NULL DEFAULT NULL,
	`mgpStatus` CHAR(1) NOT NULL DEFAULT 'A' COMMENT 'A:Active, R:Removed' COLLATE 'utf8mb4_unicode_ci',
	`mgpCreatedAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`mgpCreatedBy` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
	`mgpUpdatedAt` DATETIME NULL DEFAULT NULL,
	`mgpUpdatedBy` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
	`mgpRemovedAt` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`mgpRemovedBy` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
	PRIMARY KEY (`mgpID`) USING BTREE,
	UNIQUE INDEX `mgpUUID` (`mgpUUID`) USING BTREE
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
;
SQLSTR
    );
    $this->alterColumn('tbl_MHA_MemberGroup', 'mgpI18NData', $this->json());

    $this->execute(<<<SQLSTR
CREATE TABLE `tbl_MHA_Member_MemberGroup` (
	`mbrmgpID` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`mbrmgpUUID` VARCHAR(38) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`mbrmgpMemberID` BIGINT(20) UNSIGNED NOT NULL,
	`mbrmgpMemberGroupID` INT(10) UNSIGNED NOT NULL,
	`mbrmgpStartAt` DATETIME NULL DEFAULT NULL,
	`mbrmgpEndAt` DATETIME NULL DEFAULT NULL,
	`mbrmgpCreatedAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`mbrmgpCreatedBy` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
	`mbrmgpUpdatedAt` DATETIME NULL DEFAULT NULL,
	`mbrmgpUpdatedBy` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
	`mbrmgpRemovedAt` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`mbrmgpRemovedBy` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
	PRIMARY KEY (`mbrmgpID`) USING BTREE,
	UNIQUE INDEX `mbrmgpUUID` (`mbrmgpUUID`) USING BTREE,
	INDEX `FK_tbl_MHA_Member_MemberGroup_tbl_MHA_Member` (`mbrmgpMemberID`) USING BTREE,
	INDEX `FK_tbl_MHA_Member_MemberGroup_tbl_MHA_MemberGroup` (`mbrmgpMemberGroupID`) USING BTREE,
	CONSTRAINT `FK_tbl_MHA_Member_MemberGroup_tbl_MHA_Member` FOREIGN KEY (`mbrmgpMemberID`) REFERENCES `tbl_MHA_Member` (`mbrUserID`) ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT `FK_tbl_MHA_Member_MemberGroup_tbl_MHA_MemberGroup` FOREIGN KEY (`mbrmgpMemberGroupID`) REFERENCES `tbl_MHA_MemberGroup` (`mgpID`) ON UPDATE NO ACTION ON DELETE NO ACTION
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
;
SQLSTR
    );

    $this->execute("DROP TRIGGER IF EXISTS ???????????????????????;");
    $this->execute(<<<SQLSTR
SQLSTR
    );

	}

	public function safeDown()
	{
		echo "m231103_061223_mha_create_membergroup cannot be reverted.\n";
		return false;
	}

}
