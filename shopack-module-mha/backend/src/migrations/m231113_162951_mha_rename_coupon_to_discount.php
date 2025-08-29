<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

/* this will be applied after m231113_000000_accounting_rename_coupon_to_discount@mha */
class m231113_162951_mha_rename_coupon_to_discount extends Migration
{
	public function safeUp()
	{
		$this->execute(<<<SQL
ALTER TABLE `tbl_MHA_Accounting_Discount`
	ADD COLUMN `dscTargetMemberGroupIDs` JSON NULL AFTER `dscRemovedBy`,
	ADD COLUMN `dscTargetKanoonIDs` JSON NULL AFTER `dscTargetMemberGroupIDs`,
	ADD COLUMN `dscTargetProductMhaTypes` JSON NULL DEFAULT NULL AFTER `dscTargetKanoonIDs`;
SQL
    );
		///JSON
    $this->alterColumn('tbl_MHA_Accounting_Discount', 'dscTargetMemberGroupIDs', $this->json());
    $this->alterColumn('tbl_MHA_Accounting_Discount', 'dscTargetKanoonIDs', $this->json());
    $this->alterColumn('tbl_MHA_Accounting_Discount', 'dscTargetProductMhaTypes', $this->json());
	}

	public function safeDown()
	{
		echo "m231113_162951_mha_rename_coupon_to_discount cannot be reverted.\n";
		return false;
	}

}
