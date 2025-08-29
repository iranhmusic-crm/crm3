<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

/* this will be applied after m231219_000000_accounting_convert_uasVoucherItemInfo@mha */
class m231219_151044_mha_convert_uasVoucherItemInfo extends Migration
{
  public function safeUp()
  {
    //nothing to do
  }

  public function safeDown()
  {
    echo "m231219_151044_mha_convert_uasVoucherItemInfo cannot be reverted.\n";
    return false;
  }

}
