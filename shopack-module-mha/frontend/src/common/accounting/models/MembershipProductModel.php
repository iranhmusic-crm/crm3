<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\accounting\models;

use Yii;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\frontend\common\rest\RestClientActiveRecord;
use shopack\base\common\accounting\enums\enuProductStatus;
use iranhmusic\shopack\mha\common\accounting\enums\enuMhaProductType;

class MembershipProductModel extends RestClientActiveRecord
{
	use \iranhmusic\shopack\mha\common\accounting\models\ProductModelTrait;

	public static $resourceName = 'mha/accounting/product';

	public function __construct()
	{
		$this->prdMhaType = enuMhaProductType::Membership;
	}

	public function attributeLabels()
	{
		return [
			'prdID'                  => Yii::t('app', 'ID'),
			// 'prdUUID'
			'prdCode'                => Yii::t('aaa', 'Code'),
			'prdName'                => Yii::t('app', 'Name'),
			'prdDesc'                => Yii::t('app', 'Desc'),
			'prdValidFromDate'       => Yii::t('app', 'Valid From Date'),
			'prdValidToDate'         => Yii::t('app', 'Valid To Date'),
			'prdValidFromHour'       => Yii::t('app', 'Valid From Hour'),
			'prdValidToHour'         => Yii::t('app', 'Valid To Hour'),
			'prdDurationMinutes'     => Yii::t('aaa', 'Duration Minutes'),
			'prdStartAtFirstUse'     => Yii::t('aaa', 'Start At First Use'),
			'prdPrivs'               => Yii::t('app', 'Privs'),
			'prdVAT'               	 => Yii::t('aaa', 'VAT'),
			'prdUnitID'              => Yii::t('aaa', 'Unit'),
			'prdQtyIsDecimal'        => Yii::t('aaa', 'Qty Is Decimal'),
			'prdInStockQty'          => Yii::t('aaa', 'In Stock Qty'),
			'prdOrderedQty'          => Yii::t('aaa', 'Ordered Qty'),
			'prdReturnedQty'         => Yii::t('aaa', 'Returned Qty'),
			// 'prdI18NData'            =>
			'prdStatus'              => Yii::t('app', 'Status'),
			'prdCreatedAt'           => Yii::t('app', 'Created At'),
			'prdCreatedBy'           => Yii::t('app', 'Created By'),
			'prdCreatedBy_User'      => Yii::t('app', 'Created By'),
			'prdUpdatedAt'           => Yii::t('app', 'Updated At'),
			'prdUpdatedBy'           => Yii::t('app', 'Updated By'),
			'prdUpdatedBy_User'      => Yii::t('app', 'Updated By'),
			'prdRemovedAt'           => Yii::t('app', 'Removed At'),
			'prdRemovedBy'           => Yii::t('app', 'Removed By'),
			'prdRemovedBy_User'      => Yii::t('app', 'Removed By'),
		];
	}

	public function isSoftDeleted()
  {
    return ($this->prdStatus == enuProductStatus::Removed);
  }

	public static function canCreate() {
		return true;
	}

	public function canUpdate() {
		return ($this->prdStatus != enuProductStatus::Removed);
	}

	public function canDelete() {
		return ($this->prdStatus != enuProductStatus::Removed);
	}

	public function canUndelete() {
		return ($this->prdStatus == enuProductStatus::Removed);
	}

}
