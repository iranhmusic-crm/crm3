<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\models;

use Yii;
use shopack\base\frontend\common\rest\RestClientActiveRecord;
// use shopack\aaa\common\enums\enuMemberMemberGroupStatus;

class MemberMemberGroupModel extends RestClientActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\MemberMemberGroupModelTrait;

	public static $resourceName = 'mha/member-member-group';

	public function attributeLabels()
	{
		return [
			'mbrmgpID'               => Yii::t('app', 'ID'),
			'mbrmgpMemberID'         => Yii::t('mha', 'Member'),
			'mbrmgpMemberGroupID'    => Yii::t('mha', 'Member Group'),
			'mbrmgpStartAt'          => Yii::t('aaa', 'Start At'),
			'mbrmgpEndAt'            => Yii::t('aaa', 'End At'),
			'mbrmgpCreatedAt'        => Yii::t('app', 'Created At'),
			'mbrmgpCreatedBy'        => Yii::t('app', 'Created By'),
			'mbrmgpCreatedBy_User'   => Yii::t('app', 'Created By'),
			'mbrmgpUpdatedAt'        => Yii::t('app', 'Updated At'),
			'mbrmgpUpdatedBy'        => Yii::t('app', 'Updated By'),
			'mbrmgpUpdatedBy_User'   => Yii::t('app', 'Updated By'),
			'mbrmgpRemovedAt'        => Yii::t('app', 'Removed At'),
			'mbrmgpRemovedBy'        => Yii::t('app', 'Removed By'),
			'mbrmgpRemovedBy_User'   => Yii::t('app', 'Removed By'),
		];
	}

	public function isSoftDeleted()
  {
    return false; //($this->mbrmgpStatus == enuMemberMemberGroupStatus::Removed);
  }

	public static function canCreate() {
		return true;
	}

	public function canUpdate() {
		return true; //($this->mbrmgpStatus != enuMemberMemberGroupStatus::Removed);
	}

	public function canDelete() {
		return true; //($this->mbrmgpStatus != enuMemberMemberGroupStatus::Removed);
	}

	public function canUndelete() {
		return false; //($this->mbrmgpStatus == enuMemberMemberGroupStatus::Removed);
	}

}
