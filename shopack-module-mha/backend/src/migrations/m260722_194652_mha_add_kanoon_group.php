<?php

/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m260722_194652_mha_add_kanoon_group extends Migration
{
    public function safeUp()
    {
        $this->execute(
            <<<SQL
ALTER TABLE `tbl_MHA_Kanoon`
    ADD COLUMN `knnGroupID` TINYINT UNSIGNED NOT NULL DEFAULT 1 AFTER `knnTalkerMemberID`;
SQL
        );

        $this->execute(
            <<<SQL
UPDATE `tbl_MHA_Kanoon`
    SET knnGroupID = 2
    WHERE knnNameEn = 'Instructors';
SQL
        );
    }

    public function safeDown()
    {
        $this->execute(
            <<<SQL
ALTER TABLE `tbl_MHA_Kanoon`
    DROP COLUMN knnGroupID;
SQL
        );

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
    }

    public function down()
    {
        echo "m260722_194652_mha_add_kanoon_group cannot be reverted.\n";
        return false;
    }
    */
}
