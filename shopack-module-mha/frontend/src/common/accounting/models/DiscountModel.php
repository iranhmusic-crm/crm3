<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\accounting\models;

use Yii;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\frontend\common\rest\RestClientActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuDocumentStatus;

class DiscountModel extends RestClientActiveRecord
{
	use \iranhmusic\shopack\mha\common\accounting\models\DiscountModelTrait;

	public static $resourceName = 'mha/accounting/discount';

	public function attributeLabels()
	{
		return [
			'dscID'                      => Yii::t('app', 'ID'),
			// 'dscUUID'
			'dscName'                    => Yii::t('app', 'Name'),
			'dscCode'                    => Yii::t('aaa', 'Code'),
			'dscTotalMaxCount'           => Yii::t('aaa', 'Total Max Count'),
			'dscTotalMaxPrice'           => Yii::t('aaa', 'Total Max Amount'),
			'dscPerUserMaxCount'         => Yii::t('aaa', 'Per User Max Count'),
			'dscPerUserMaxPrice'         => Yii::t('aaa', 'Per User Max Amount'),
			'dscValidFrom'               => Yii::t('app', 'Valid From Date'),
			'dscValidTo'                 => Yii::t('app', 'Valid To Date'),
			'dscAmount'               	 => Yii::t('aaa', 'Amount'),
			'dscAmountType'              => Yii::t('aaa', 'Amount Type'),
			'dscMaxAmount'               => Yii::t('aaa', 'Max Amount'),
			'dscSaleableBasedMultiplier' => Yii::t('aaa', 'Saleable Based Multiplier'),
			'dscTotalUsedCount'          => Yii::t('aaa', 'Total Used Count'),
			'dscTotalUsedPrice'          => Yii::t('aaa', 'Total Used Amount'),
			// 'dscI18NData'            =>
			'dscStatus'                  => Yii::t('app', 'Status'),
			'dscCreatedAt'               => Yii::t('app', 'Created At'),
			'dscCreatedBy'               => Yii::t('app', 'Created By'),
			'dscCreatedBy_User'          => Yii::t('app', 'Created By'),
			'dscUpdatedAt'               => Yii::t('app', 'Updated At'),
			'dscUpdatedBy'               => Yii::t('app', 'Updated By'),
			'dscUpdatedBy_User'          => Yii::t('app', 'Updated By'),
			'dscRemovedAt'               => Yii::t('app', 'Removed At'),
			'dscRemovedBy'               => Yii::t('app', 'Removed By'),
			'dscRemovedBy_User'          => Yii::t('app', 'Removed By'),
		];
	}

	public function isSoftDeleted()
  {
    return ($this->dscStatus == enuDocumentStatus::Removed);
  }

	public static function canCreate() {
		return true;
	}

	public function canUpdate() {
		return ($this->dscStatus != enuDocumentStatus::Removed);
	}

	public function canDelete() {
		return ($this->dscStatus != enuDocumentStatus::Removed);
	}

	public function canUndelete() {
		return ($this->dscStatus == enuDocumentStatus::Removed);
	}

}
