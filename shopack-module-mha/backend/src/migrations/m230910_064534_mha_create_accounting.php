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
ALTER TABLE `tbl_MHA_Accounting_Product`
  ADD COLUMN `prdMhaType` CHAR(1) NOT NULL COMMENT 'M:Membership, C:Card Print, P:Post Packet' COLLATE 'utf8mb4_unicode_ci' AFTER `prdRemovedBy`;
SQL
    );

    $this->execute(<<<SQL
ALTER TABLE `tbl_MHA_MemberMembership`
	DROP FOREIGN KEY `FK_tbl_MHA_MemberMembership_tbl_MHA_Membership`;
SQL
    );

	$this->execute("DROP TRIGGER IF EXISTS trg_updatelog_tbl_MHA_Membership;");

    $this->execute(<<<SQL
RENAME TABLE `tbl_MHA_Membership` TO `DELETED_tbl_MHA_Membership`;
SQL
    );

    $this->execute(<<<SQL
ALTER TABLE `tbl_MHA_MemberMembership`
	DROP FOREIGN KEY `FK_tbl_MHA_MemberMembership_tbl_MHA_Member`,
	DROP FOREIGN KEY `FK_tbl_MHA_MemberMembership_tbl_AAA_Voucher`;
SQL
    );

    $this->execute("DROP TRIGGER IF EXISTS trg_tbl_MHA_MemberMembership_after_insert;");
    $this->execute("DROP TRIGGER IF EXISTS trg_tbl_MHA_MemberMembership_after_update;");
		$this->execute("DROP TRIGGER IF EXISTS trg_updatelog_tbl_MHA_MemberMembership;");

    $this->execute(<<<SQL
RENAME TABLE `tbl_MHA_MemberMembership` TO `DELETED_tbl_MHA_MemberMembership`;
SQL
    );

  }

  public function safeDown()
  {
    echo "m230910_064534_mha_create_accounting cannot be reverted.\n";
    return false;
  }

}
