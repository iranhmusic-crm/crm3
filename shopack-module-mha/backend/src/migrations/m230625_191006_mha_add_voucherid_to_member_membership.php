<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m230625_191006_mha_add_voucherid_to_member_membership extends Migration
{
	public function safeUp()
	{
		$this->execute(<<<SQLSTR
ALTER TABLE `tbl_MHA_MemberMembership`
	ADD COLUMN `mbrshpVoucherID` BIGINT UNSIGNED NULL DEFAULT NULL AFTER `mbrshpMembershipID`,
	ADD CONSTRAINT `FK_tbl_MHA_MemberMembership_tbl_AAA_Voucher` FOREIGN KEY (`mbrshpVoucherID`) REFERENCES `tbl_AAA_Voucher` (`vchID`) ON UPDATE NO ACTION ON DELETE NO ACTION;
SQLSTR
		);

	}

	public function safeDown()
	{
		echo "m230625_191006_mha_add_voucherid_to_member_membership cannot be reverted.\n";
		return false;
	}

}
