<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\models;

use Yii;
use shopack\base\frontend\common\rest\RestClientActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuKanoonStatus;

class KanoonModel extends RestClientActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\KanoonModelTrait;

	public static $resourceName = 'mha/kanoon';

	public function attributeLabels()
	{
		return [
			'knnID'                    => Yii::t('app', 'ID'),
			'knnName'                  => Yii::t('app', 'Name'),
			'knnNameEn'                => Yii::t('app', 'Name (en)'),
			'knnDescFieldType'         => Yii::t('mha', 'Description Field Type'),
			'knnDescFieldLabel'        => Yii::t('mha', 'Description Field Label'),
			'knnPresidentMemberID'     => Yii::t('mha', 'President'),
			'knnVicePresidentMemberID' => Yii::t('mha', 'VicePresident'),
			'knnOzv1MemberID'          => Yii::t('mha', 'Ozv1'),
			'knnOzv2MemberID'          => Yii::t('mha', 'Ozv2'),
			'knnOzv3MemberID'          => Yii::t('mha', 'Ozv3'),
			'knnWardenMemberID'        => Yii::t('mha', 'Warden'),
			'knnTalkerMemberID'        => Yii::t('mha', 'Talker'),
			'knnStatus'                => Yii::t('app', 'Status'),
			'knnCreatedAt'             => Yii::t('app', 'Created At'),
			'knnCreatedBy'             => Yii::t('app', 'Created By'),
			'knnCreatedBy_User'        => Yii::t('app', 'Created By'),
			'knnUpdatedAt'             => Yii::t('app', 'Updated At'),
			'knnUpdatedBy'             => Yii::t('app', 'Updated By'),
			'knnUpdatedBy_User'        => Yii::t('app', 'Updated By'),
			'knnRemovedAt'             => Yii::t('app', 'Removed At'),
			'knnRemovedBy'             => Yii::t('app', 'Removed By'),
			'knnRemovedBy_User'        => Yii::t('app', 'Removed By'),
		];
	}

	public function isSoftDeleted()
  {
    return ($this->knnStatus == enuKanoonStatus::Removed);
  }

	public static function canCreate() {
		return true;
	}

	public function canUpdate() {
		return ($this->knnStatus != enuKanoonStatus::Removed);
	}

	public function canDelete() {
		return ($this->knnStatus != enuKanoonStatus::Removed);
	}

	public function canUndelete() {
		return ($this->knnStatus == enuKanoonStatus::Removed);
	}

}
