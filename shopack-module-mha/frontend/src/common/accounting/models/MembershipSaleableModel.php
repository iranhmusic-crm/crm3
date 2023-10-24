<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\accounting\models;

use Yii;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\frontend\common\rest\RestClientActiveRecord;
use shopack\base\common\accounting\enums\enuSaleableStatus;
use iranhmusic\shopack\mha\common\accounting\enums\enuSaleableType;

class MembershipSaleableModel extends RestClientActiveRecord
{
	use \iranhmusic\shopack\mha\common\accounting\models\SaleableModelTrait;

	public static $resourceName = 'mha/accounting/saleable';

	public function attributeLabels()
	{
		return [
			'slbID'                  => Yii::t('app', 'ID'),
			// 'slbUUID'
			'slbProductID'           => Yii::t('mha', 'Membership Product'),
			'slbCode'                => Yii::t('aaa', 'Code'),
			'slbName'                => Yii::t('app', 'Name'),
			'slbDesc'                => Yii::t('app', 'Desc'),
			'slbAvailableFromDate'   => Yii::t('app', 'Available From'),
			'slbAvailableToDate'     => Yii::t('app', 'Available To'),
			'slbPrivs'               => Yii::t('app', 'Privs'),
			'slbBasePrice'           => Yii::t('aaa', 'Price'),
			'slbAdditives'           => Yii::t('aaa', 'Additives'),
			'slbProductCount'        => Yii::t('aaa', 'Product Count'),
			'slbMaxSaleCountPerUser' => Yii::t('aaa', 'Max Sale Count Per User'),
			'slbInStockQty'          => Yii::t('aaa', 'In Stock Qty'),
			'slbOrderedQty'          => Yii::t('aaa', 'Ordered Qty'),
			'slbReturnedQty'         => Yii::t('aaa', 'Returned Qty'),
			'slbVoucherTemplate'     => Yii::t('aaa', 'Voucher Template'),
			// 'slbI18NData'            =>
			'slbStatus'              => Yii::t('app', 'Status'),
			'slbCreatedAt'           => Yii::t('app', 'Created At'),
			'slbCreatedBy'           => Yii::t('app', 'Created By'),
			'slbCreatedBy_User'      => Yii::t('app', 'Created By'),
			'slbUpdatedAt'           => Yii::t('app', 'Updated At'),
			'slbUpdatedBy'           => Yii::t('app', 'Updated By'),
			'slbUpdatedBy_User'      => Yii::t('app', 'Updated By'),
			'slbRemovedAt'           => Yii::t('app', 'Removed At'),
			'slbRemovedBy'           => Yii::t('app', 'Removed By'),
			'slbRemovedBy_User'      => Yii::t('app', 'Removed By'),
		];
	}

	public function isSoftDeleted()
  {
    return ($this->slbStatus == enuSaleableStatus::Removed);
  }

	public static function canCreate() {
		return true;
	}

	public function canUpdate() {
		return ($this->slbStatus != enuSaleableStatus::Removed);
	}

	public function canDelete() {
		return ($this->slbStatus != enuSaleableStatus::Removed);
	}

	public function canUndelete() {
		return ($this->slbStatus == enuSaleableStatus::Removed);
	}

}
