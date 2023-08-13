<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\models;

use Yii;
use shopack\base\frontend\rest\RestClientActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuInsurerStatus;

class MasterInsurerModel extends RestClientActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\MasterInsurerModelTrait;

	public static $resourceName = 'mha/master-insurer';
  public static $primaryKey = ['minsID'];

	public function attributeLabels()
	{
		return [
			'minsID'               => Yii::t('app', 'ID'),
			'minsName'             => Yii::t('app', 'Name'),
			'minsStatus'           => Yii::t('app', 'Status'),
			'minsCreatedAt'        => Yii::t('app', 'Created At'),
			'minsCreatedBy'        => Yii::t('app', 'Created By'),
			'minsCreatedBy_User'   => Yii::t('app', 'Created By'),
			'minsUpdatedAt'        => Yii::t('app', 'Updated At'),
			'minsUpdatedBy'        => Yii::t('app', 'Updated By'),
			'minsUpdatedBy_User'   => Yii::t('app', 'Updated By'),
			'minsRemovedAt'        => Yii::t('app', 'Removed At'),
			'minsRemovedBy'        => Yii::t('app', 'Removed By'),
			'minsRemovedBy_User'   => Yii::t('app', 'Removed By'),
		];
	}

	public function isSoftDeleted()
  {
    return ($this->minsStatus == enuInsurerStatus::Removed);
  }

	public static function canCreate() {
		return true;
	}

	public function canUpdate() {
		return ($this->minsStatus != enuInsurerStatus::Removed);
	}

	public function canDelete() {
		return ($this->minsStatus != enuInsurerStatus::Removed);
	}

	public function canUndelete() {
		return ($this->minsStatus == enuInsurerStatus::Removed);
	}

}
