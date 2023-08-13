<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use Yii;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuMemberStatus;
use shopack\aaa\backend\models\UserModel;

class MemberModel extends MhaActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\MemberModelTrait;

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

	public function getUser() {
		return $this->hasOne(UserModel::class, ['usrID' => 'mbrUserID']);
	}

	public static function AssignRegistrationCode($id, $regcode=null)
	{
		$code = (empty($regcode)
			? "COALESCE((SELECT tmp._max FROM (SELECT MAX(mbrRegisterCode) AS _max FROM tbl_MHA_Member) AS tmp), 0) + 1"
			: $regcode
		);

		$qry =<<<SQL
  UPDATE tbl_MHA_Member
     SET mbrRegisterCode = {$code}
       , mbrAcceptedAt = NOW()
   WHERE mbrUserID = {$id}
	   AND mbrRegisterCode IS NULL
SQL;
		$rowsCount = Yii::$app->db->createCommand($qry)->execute();

		return $rowsCount == 1;
	}

}
