<?php

/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use Yii;
use shopack\aaa\backend\models\UserModel;
use iranhmusic\shopack\mha\common\enums\enuMemberStatus;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;

class MemberModel extends MhaActiveRecord
{
    use \iranhmusic\shopack\mha\common\models\MemberModelTrait;

    // public $filter_mode = 0;

    use \shopack\base\common\db\SoftDeleteActiveRecordTrait;
    public function initSoftDelete()
    {
        $this->softdelete_RemovedStatus  = enuMemberStatus::Removed;
        // $this->softdelete_StatusField    = 'mbrStatus';
        $this->softdelete_RemovedAtField = 'mbrRemovedAt';
        $this->softdelete_RemovedByField = 'mbrRemovedBy';
    }

    public static function tableName()
    {
        return '{{%MHA_Member}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
                'createdAtAttribute' => 'mbrCreatedAt',
                'createdByAttribute' => 'mbrCreatedBy',
                'updatedAtAttribute' => 'mbrUpdatedAt',
                'updatedByAttribute' => 'mbrUpdatedBy',
            ],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(UserModel::class, ['usrID' => 'mbrUserID']);
    }

    public static function AssignRegistrationCode($id, $regcode = null, $mbrAcceptedAt = null)
    {
        $code = (empty($regcode)
            ? "COALESCE((SELECT tmp._max FROM (SELECT MAX(mbrRegisterCode) AS _max FROM tbl_MHA_Member) AS tmp), 0) + 1"
            : $regcode
        );

        if (empty($mbrAcceptedAt))
            $mbrAcceptedAt = 'NOW()';
        else {
            if (strpos($mbrAcceptedAt, '-') === false)
                $format = '%Y/%m/%d';
            else
                $format = '%Y-%m-%d';

            $mbrAcceptedAt = "STR_TO_DATE('{$mbrAcceptedAt}', '{$format}')";
        }

        $qry = <<<SQL
    UPDATE	tbl_MHA_Member
         SET	mbrRegisterCode = IFNULL(mbrRegisterCode, {$code})
             ,	mbrAcceptedAt = LEAST(IFNULL(mbrAcceptedAt, {$mbrAcceptedAt}), {$mbrAcceptedAt})
     WHERE	mbrUserID = {$id}
SQL;
        //  AND	(mbrRegisterCode IS NULL
        // 	OR	mbrAcceptedAt IS NULL)

        $rowsCount = Yii::$app->db->createCommand($qry)->execute();

        return $rowsCount == 1;
    }
}
