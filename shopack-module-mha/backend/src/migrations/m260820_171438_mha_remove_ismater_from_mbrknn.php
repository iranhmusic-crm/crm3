<?php

/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m260820_171438_mha_remove_ismater_from_mbrknn extends Migration
{
    public function safeUp()
    {
        $this->execute(
            <<<SQL
ALTER TABLE `tbl_MHA_Member_Kanoon`
	DROP COLUMN `mbrknnIsMaster`;
SQL
        );
    }

    public function safeDown()
    {
        echo "m260820_171438_mha_remove_ismater_from_mbrknn cannot be reverted.\n";
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
    }

    public function down()
    {
        echo "m260820_171438_mha_remove_ismater_from_mbrknn cannot be reverted.\n";
        return false;
    }
    */
}
