<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m231219_151044_mha_convert_uasVoucherItemInfo extends Migration
{
  public function safeUp()
  {
    $this->execute(<<<SQLSTR
ALTER TABLE `tbl_MHA_Accounting_UserAsset`
  CHANGE COLUMN `uasVoucherItemInfo` `OLD_uasVoucherItemInfo` JSON NULL DEFAULT NULL AFTER `uasVoucherID`;
SQLSTR
    );

    $this->execute(<<<SQLSTR
ALTER TABLE `tbl_MHA_Accounting_UserAsset`
  ADD COLUMN `uasVoucherItemInfo` JSON NULL DEFAULT NULL AFTER `OLD_uasVoucherItemInfo`;
SQLSTR
    );
    $this->alterColumn('tbl_MHA_Accounting_UserAsset', 'uasVoucherItemInfo', $this->json());

    $this->execute(<<<SQLSTR

SQLSTR
    );

  }

  public function safeDown()
  {
    echo "m231219_151044_mha_convert_uasVoucherItemInfo cannot be reverted.\n";
    return false;
  }

}
