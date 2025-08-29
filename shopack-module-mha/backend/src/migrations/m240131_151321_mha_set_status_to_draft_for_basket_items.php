<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

/* this will be applied after m240131_000000_accounting_set_status_to_draft_for_basket_items@mha */
class m240131_151321_mha_set_status_to_draft_for_basket_items extends Migration
{
  public function safeUp()
  {
    //nothing to do
  }

  public function safeDown()
  {
    echo "m240131_151321_mha_set_status_to_draft_for_basket_items cannot be reverted.\n";
    return false;
  }

}
