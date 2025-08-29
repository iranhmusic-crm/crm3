<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

/* this will be applied after m230910_000000_accounting_create_accounting@mha */
class m230910_064534_mha_create_accounting extends Migration
{
  public function safeUp()
  {
    $this->execute(<<<SQL
ALTER TABLE `tbl_M_H_A_Accounting_Product`
  ADD COLUMN `prdMhaType` CHAR(1) NOT NULL COMMENT 'M:Membership, C:Card Print, P:Post Packet' COLLATE 'utf8mb4_unicode_ci' AFTER `prdRemovedBy`;
SQL
    );
  }

  public function safeDown()
  {
    echo "m230910_064534_mha_create_accounting cannot be reverted.\n";
    return false;
  }

}
