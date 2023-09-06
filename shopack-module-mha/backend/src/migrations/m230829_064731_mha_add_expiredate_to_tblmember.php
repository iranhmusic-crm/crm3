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
           , MAX(mbrshpEndDate) AS _mbrshpEndDate
        FROM tbl_MHA_MemberMembership
       WHERE mbrshpStatus = '{$fnGetConst(enuMemberMembershipStatus::Paid)}'
    GROUP BY mbrshpMemberID
             ) tMax
          ON tMax.mbrshpMemberID = tbl_MHA_Member.mbrUserID
         SET mbrExpireDate = tMax._mbrshpEndDate
       WHERE (mbrExpireDate IS NULL
          OR mbrExpireDate < tMax._mbrshpEndDate
             )
;
SQLSTR
        );

        $this->execute("DROP TRIGGER IF EXISTS `trg_tbl_MHA_MemberMembership_after_insert`;");

        $this->execute(<<<SQLSTR
CREATE TRIGGER `trg_tbl_MHA_MemberMembership_after_insert` AFTER INSERT ON `tbl_MHA_MemberMembership` FOR EACH ROW BEGIN
	DECLARE maxDate DATE DEFAULT NULL;

    SELECT MAX(mbrshpEndDate)
      INTO maxDate
      FROM tbl_MHA_MemberMembership
     WHERE mbrshpStatus = 'P' -- Paid
       AND mbrshpMemberID = NEW.mbrshpMemberID
  GROUP BY mbrshpMemberID
           ;

  IF maxDate IS NOT NULL THEN
    UPDATE tbl_MHA_Member
       SET mbrExpireDate = maxDate
     WHERE mbrUserID = NEW.mbrshpMemberID
       AND (mbrExpireDate IS NULL
        OR mbrExpireDate < maxDate
           )
           ;
  END IF;

/*
        UPDATE tbl_MHA_Member
    INNER JOIN (
        SELECT mbrshpMemberID
             , MAX(mbrshpEndDate) AS _mbrshpEndDate
          FROM tbl_MHA_MemberMembership
         WHERE mbrshpStatus = 'P' -- Paid
      GROUP BY mbrshpMemberID
               ) tMax
            ON tMax.mbrshpMemberID = tbl_MHA_Member.mbrUserID
           SET mbrExpireDate = tMax._mbrshpEndDate
         WHERE mbrUserID = NEW.mbrshpMemberID
           AND (mbrExpireDate IS NULL
            OR mbrExpireDate < tMax._mbrshpEndDate
               )
    ;
*/
END
SQLSTR
        );

        $this->execute("DROP TRIGGER IF EXISTS `trg_tbl_MHA_MemberMembership_after_update`;");

        $this->execute(<<<SQLSTR
CREATE TRIGGER `trg_tbl_MHA_MemberMembership_after_update` AFTER UPDATE ON `tbl_MHA_MemberMembership` FOR EACH ROW BEGIN
	DECLARE maxDate DATE DEFAULT NULL;

    SELECT MAX(mbrshpEndDate)
      INTO maxDate
      FROM tbl_MHA_MemberMembership
     WHERE mbrshpStatus = 'P' -- Paid
       AND mbrshpMemberID = NEW.mbrshpMemberID
  GROUP BY mbrshpMemberID
           ;

  IF maxDate IS NOT NULL THEN
    UPDATE tbl_MHA_Member
       SET mbrExpireDate = maxDate
     WHERE mbrUserID = NEW.mbrshpMemberID
       AND (mbrExpireDate IS NULL
        OR mbrExpireDate < maxDate
           )
           ;
  END IF;

/*
        UPDATE tbl_MHA_Member
    INNER JOIN (
        SELECT mbrshpMemberID
             , MAX(mbrshpEndDate) AS _mbrshpEndDate
          FROM tbl_MHA_MemberMembership
         WHERE mbrshpStatus = 'P' -- Paid
      GROUP BY mbrshpMemberID
               ) tMax
            ON tMax.mbrshpMemberID = tbl_MHA_Member.mbrUserID
           SET mbrExpireDate = tMax._mbrshpEndDate
         WHERE mbrUserID = NEW.mbrshpMemberID
           AND (mbrExpireDate IS NULL
            OR mbrExpireDate < tMax._mbrshpEndDate
               )
    ;
*/
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
