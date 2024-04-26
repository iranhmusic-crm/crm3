<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m240131_151321_mha_set_status_to_draft_for_basket_items extends Migration
{
  public function safeUp()
  {
    $this->execute(<<<SQL
ALTER TABLE `tbl_MHA_Accounting_UserAsset`
	CHANGE COLUMN `uasStatus` `uasStatus` CHAR(1) NOT NULL COMMENT 'D:Draft (Basket), P:Pending, A:Active, R:Removed, B:Blocked' COLLATE 'utf8mb4_unicode_ci' AFTER `uasBreakedAt`;
SQL
    );

    $this->execute(<<<SQL
UPDATE tbl_MHA_Accounting_UserAsset
  INNER JOIN tbl_AAA_Voucher
  ON tbl_AAA_Voucher.vchID = tbl_MHA_Accounting_UserAsset.uasVoucherID
  SET uasStatus = 'D'
  WHERE uasStatus = 'P'
  AND vchStatus = 'N';
SQL
    );

  }

  public function safeDown()
  {
    echo "m240131_151321_mha_set_status_to_draft_for_basket_items cannot be reverted.\n";
    return false;
  }

}
