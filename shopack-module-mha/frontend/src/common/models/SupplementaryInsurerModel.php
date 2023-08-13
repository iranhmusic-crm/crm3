<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\models;

use Yii;
use shopack\base\frontend\rest\RestClientActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuInsurerStatus;

class SupplementaryInsurerModel extends RestClientActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\SupplementaryInsurerModelTrait;

	public static $resourceName = 'mha/supplementary-insurer';
  public static $primaryKey = ['sinsID'];

	public function attributeLabels()
	{
		return [
			'sinsID'               => Yii::t('app', 'ID'),
			'sinsName'             => Yii::t('app', 'Name'),
			'sinsStatus'           => Yii::t('app', 'Status'),
			'sinsCreatedAt'        => Yii::t('app', 'Created At'),
			'sinsCreatedBy'        => Yii::t('app', 'Created By'),
			'sinsCreatedBy_User'   => Yii::t('app', 'Created By'),
			'sinsUpdatedAt'        => Yii::t('app', 'Updated At'),
			'sinsUpdatedBy'        => Yii::t('app', 'Updated By'),
			'sinsUpdatedBy_User'   => Yii::t('app', 'Updated By'),
			'sinsRemovedAt'        => Yii::t('app', 'Removed At'),
			'sinsRemovedBy'        => Yii::t('app', 'Removed By'),
			'sinsRemovedBy_User'   => Yii::t('app', 'Removed By'),
		];
	}

	public function isSoftDeleted()
  {
    return ($this->sinsStatus == enuInsurerStatus::Removed);
  }

	public static function canCreate() {
		return true;
	}

	public function canUpdate() {
		return ($this->sinsStatus != enuInsurerStatus::Removed);
	}

	public function canDelete() {
		return ($this->sinsStatus != enuInsurerStatus::Removed);
	}

	public function canUndelete() {
		return ($this->sinsStatus == enuInsurerStatus::Removed);
	}

}
