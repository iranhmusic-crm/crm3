<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\models;

use Yii;
use shopack\base\frontend\rest\RestClientActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuReportStatus;

class ReportModel extends RestClientActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\ReportModelTrait;

	public static $resourceName = 'mha/report';
  public static $primaryKey = ['rptID'];

	public function attributeLabels()
	{
		return [
			'rptID'                   => Yii::t('app', 'ID'),
			'rptName'                 => Yii::t('app', 'Name'),
			'rptType'                	=> Yii::t('app', 'Type'),
			'rptInputFields'          => Yii::t('app', 'Input Fields'),
			'rptOutputFields'         => Yii::t('app', 'Output Fields'),
			'rptStatus'               => Yii::t('app', 'Status'),
			'rptCreatedAt'            => Yii::t('app', 'Created At'),
			'rptCreatedBy'            => Yii::t('app', 'Created By'),
			'rptCreatedBy_User'       => Yii::t('app', 'Created By'),
			'rptUpdatedAt'            => Yii::t('app', 'Updated At'),
			'rptUpdatedBy'            => Yii::t('app', 'Updated By'),
			'rptUpdatedBy_User'       => Yii::t('app', 'Updated By'),
			'rptRemovedAt'            => Yii::t('app', 'Removed At'),
			'rptRemovedBy'            => Yii::t('app', 'Removed By'),
			'rptRemovedBy_User'       => Yii::t('app', 'Removed By'),
		];
	}

	public function isSoftDeleted()
  {
    return ($this->rptStatus == enuReportStatus::Removed);
  }

	public static function canCreate() {
		return true;
	}

	public function canUpdate() {
		return ($this->rptStatus != enuReportStatus::Removed);
	}

	public function canDelete() {
		return ($this->rptStatus != enuReportStatus::Removed);
	}

	public function canUndelete() {
		return ($this->rptStatus == enuReportStatus::Removed);
	}

	public function save($runValidation = true, $attributeNames = null)
  {
		if (empty($this->rptOutputFields) == false) {
			$this->rptOutputFields = array_filter($this->rptOutputFields, function($v) {
				return ((empty($v) == false) && ($v != 0));
			});
		}

		if (empty($this->rptOutputFields)) {
			$this->addError(null, 'ستون‌های خروجی مشخص نشده‌اند');
			return false;
		}

    return parent::save($runValidation, $attributeNames);
  }

}
