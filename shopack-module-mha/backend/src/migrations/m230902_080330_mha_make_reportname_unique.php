<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m230902_080330_mha_make_reportname_unique extends Migration
{
    public function safeUp()
    {
        $this->execute(<<<SQLSTR
ALTER TABLE `tbl_MHA_Report`
	CHANGE COLUMN `rptName` `rptName` VARCHAR(512) NOT NULL COLLATE 'utf8mb4_unicode_ci' AFTER `rptUUID`,
	ADD UNIQUE INDEX `rptName_rptRemovedAt` (`rptName`, `rptRemovedAt`);
SQLSTR
        );
    }

    public function safeDown()
    {
        echo "m230902_080330_mha_make_reportname_unique cannot be reverted.\n";
        return false;
    }

}
