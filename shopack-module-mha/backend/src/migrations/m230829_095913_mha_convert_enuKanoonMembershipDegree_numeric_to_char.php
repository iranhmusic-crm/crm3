<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;

class m230829_095913_mha_convert_enuKanoonMembershipDegree_numeric_to_char extends Migration
{
    public function safeUp()
    {
        $this->execute(<<<SQLSTR
UPDATE tbl_MHA_Member_Kanoon
	SET mbrknnMembershipDegree = 'D'
	WHERE mbrknnMembershipDegree = '1'
;
SQLSTR
        );

        $this->execute(<<<SQLSTR
UPDATE tbl_MHA_Member_Kanoon
	SET mbrknnMembershipDegree = 'E'
	WHERE mbrknnMembershipDegree = '2'
;
SQLSTR
        );

        $this->execute(<<<SQLSTR
UPDATE tbl_MHA_Member_Kanoon
	SET mbrknnMembershipDegree = 'O'
	WHERE mbrknnMembershipDegree = '5'
;
SQLSTR
        );

    }

    public function safeDown()
    {
        echo "m230829_095913_mha_convert_enuKanoonMembershipDegree_numeric_to_char cannot be reverted.\n";

        return false;
    }

}
