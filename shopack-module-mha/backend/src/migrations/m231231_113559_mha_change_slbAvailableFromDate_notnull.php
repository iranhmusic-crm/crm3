<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

/* this will be applied after m231231_000000_accounting_change_slbAvailableFromDate_notnull@mha */
class m231231_113559_mha_change_slbAvailableFromDate_notnull extends Migration
{
  public function safeUp()
  {
    //nothing to do
  }

  public function safeDown()
  {
    echo "m231231_113559_mha_change_slbAvailableFromDate_notnull cannot be reverted.\n";
    return false;
  }

}
