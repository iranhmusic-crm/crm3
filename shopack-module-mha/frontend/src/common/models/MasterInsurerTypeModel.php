<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\models;

use Yii;
use shopack\base\frontend\rest\RestClientActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuInsurerStatus;

class MasterInsurerTypeModel extends RestClientActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\MasterInsurerTypeModelTrait;

	public static $resourceName = 'mha/master-insurer-type';
  public static $primaryKey = ['minstypID'];

	public function attributeLabels()
	{
		return [
			'minstypID'               => Yii::t('app', 'ID'),
			'minstypName'             => Yii::t('app', 'Name'),
			'minstypStatus'           => Yii::t('app', 'Status'),
			'minstypCreatedAt'        => Yii::t('app', 'Created At'),
			'minstypCreatedBy'        => Yii::t('app', 'Created By'),
			'minstypCreatedBy_User'   => Yii::t('app', 'Created By'),
			'minstypUpdatedAt'        => Yii::t('app', 'Updated At'),
			'minstypUpdatedBy'        => Yii::t('app', 'Updated By'),
			'minstypUpdatedBy_User'   => Yii::t('app', 'Updated By'),
			'minstypRemovedAt'        => Yii::t('app', 'Removed At'),
			'minstypRemovedBy'        => Yii::t('app', 'Removed By'),
			'minstypRemovedBy_User'   => Yii::t('app', 'Removed By'),
		];
	}

	public function isSoftDeleted()
  {
    return ($this->minstypStatus == enuInsurerStatus::Removed);
  }

	public static function canCreate() {
		return true;
	}

	public function canUpdate() {
		return ($this->minstypStatus != enuInsurerStatus::Removed);
	}

	public function canDelete() {
		return ($this->minstypStatus != enuInsurerStatus::Removed);
	}

	public function canUndelete() {
		return ($this->minstypStatus == enuInsurerStatus::Removed);
	}

}
