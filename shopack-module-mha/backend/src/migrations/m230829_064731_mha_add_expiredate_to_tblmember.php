<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;
use iranhmusic\shopack\mha\common\enums\enuMemberMembershipStatus;

class m230829_064731_mha_add_expiredate_to_tblmember extends Migration
{
    public function safeUp()
    {
        $fnGetConst = function($value) { return $value; };

        $this->execute(<<<SQLSTR
ALTER TABLE `tbl_MHA_Member`
    ADD COLUMN `mbrExpireDate` DATE NULL DEFAULT NULL AFTER `mbrAcceptedAt`;
SQLSTR
        );

        $this->execute(<<<SQLSTR
UPDATE tbl_MHA_Member
    INNER JOIN (
        SELECT mbrshpMemberID
             , mbrshpEndDate
          FROM (
        SELECT *
          FROM tbl_MHA_MemberMembership
         WHERE mbrshpStatus = '{$fnGetConst(enuMemberMembershipStatus::Paid)}'
      ORDER BY mbrshpEndDate DESC
               ) t1
      GROUP BY mbrshpMemberID
               ) t2
            ON t2.mbrshpMemberID = tbl_MHA_Member.mbrUserID
           SET mbrExpireDate = t2.mbrshpEndDate
         WHERE (mbrExpireDate IS NULL
            OR mbrExpireDate < t2.mbrshpEndDate
               )
;
SQLSTR
        );

        $this->execute("DROP TRIGGER IF EXISTS `trg_tbl_MHA_MemberMembership_after_insert`;");

        $this->execute(<<<SQLSTR
CREATE TRIGGER `trg_tbl_MHA_MemberMembership_after_insert` AFTER INSERT ON `tbl_MHA_MemberMembership` FOR EACH ROW BEGIN
        UPDATE tbl_MHA_Member
    INNER JOIN (
        SELECT mbrshpMemberID
             , mbrshpEndDate
          FROM (
        SELECT *
          FROM tbl_MHA_MemberMembership
         WHERE mbrshpStatus = 'P' -- Paid
      ORDER BY mbrshpEndDate DESC
               ) t1
      GROUP BY mbrshpMemberID
               ) t2
            ON t2.mbrshpMemberID = tbl_MHA_Member.mbrUserID
           SET mbrExpireDate = t2.mbrshpEndDate
         WHERE mbrUserID = NEW.mbrshpMemberID
           AND (mbrExpireDate IS NULL
            OR mbrExpireDate < t2.mbrshpEndDate
               )
    ;
END
SQLSTR
        );

        $this->execute("DROP TRIGGER IF EXISTS `trg_tbl_MHA_MemberMembership_after_update`;");

        $this->execute(<<<SQLSTR
CREATE TRIGGER `trg_tbl_MHA_MemberMembership_after_update` AFTER UPDATE ON `tbl_MHA_MemberMembership` FOR EACH ROW BEGIN
        UPDATE tbl_MHA_Member
    INNER JOIN (
        SELECT mbrshpMemberID
             , mbrshpEndDate
          FROM (
        SELECT *
          FROM tbl_MHA_MemberMembership
         WHERE mbrshpStatus = 'P' -- Paid
      ORDER BY mbrshpEndDate DESC
               ) t1
      GROUP BY mbrshpMemberID
               ) t2
            ON t2.mbrshpMemberID = tbl_MHA_Member.mbrUserID
           SET mbrExpireDate = t2.mbrshpEndDate
         WHERE mbrUserID = NEW.mbrshpMemberID
           AND (mbrExpireDate IS NULL
            OR mbrExpireDate < t2.mbrshpEndDate
               )
    ;
END
SQLSTR
        );

    }

    public function safeDown()
    {
        echo "m230829_064731_mha_add_expiredate_to_tblmember cannot be reverted.\n";
        return false;
    }

}
