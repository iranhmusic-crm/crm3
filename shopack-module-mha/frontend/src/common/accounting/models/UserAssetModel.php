<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\accounting\models;

use Yii;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\frontend\common\rest\RestClientActiveRecord;

abstract class UserAssetModel extends RestClientActiveRecord
{
	use \iranhmusic\shopack\mha\common\accounting\models\UserAssetModelTrait;

	public function attributeLabels()
	{
		return [
			'uasID'                  => Yii::t('app', 'ID'),
			// 'uasUUID'

			'uasActorID'             => Yii::t('aaa', 'Actor'),
			'uasSaleableID'          => Yii::t('aaa', 'Saleable'),
			'uasQty'                 => Yii::t('aaa', 'Qty'),
			'uasVoucherID'           => Yii::t('aaa', 'Voucher'),
			'uasVoucherItemUUID'     => Yii::t('aaa', 'Voucher Item UUID'),
			'uasVoucherItemInfo'     => Yii::t('aaa', 'Voucher Item Info'),
			'uasCouponID'            => Yii::t('aaa', 'Coupon'),
			'uasDiscountAmount'      => Yii::t('aaa', 'Discount Amount'),
			'uasPrefered'            => Yii::t('aaa', 'Prefered'),
			'uasValidFromDate'       => Yii::t('aaa', 'Valid From Date'),
			'uasValidToDate'         => Yii::t('aaa', 'Valid To Date'),
			'uasValidFromHour'       => Yii::t('aaa', 'Valid From Hour'),
			'uasValidToHour'         => Yii::t('aaa', 'Valid To Hour'),
			'uasDurationMinutes'     => Yii::t('aaa', 'Duration Minutes'),
			'uasBreakedAt'           => Yii::t('aaa', 'Breaked At'),

			'uasStatus'              => Yii::t('app', 'Status'),
			'uasCreatedAt'           => Yii::t('app', 'Created At'),
			'uasCreatedBy'           => Yii::t('app', 'Created By'),
			'uasCreatedBy_User'      => Yii::t('app', 'Created By'),
			'uasUpdatedAt'           => Yii::t('app', 'Updated At'),
			'uasUpdatedBy'           => Yii::t('app', 'Updated By'),
			'uasUpdatedBy_User'      => Yii::t('app', 'Updated By'),
			'uasRemovedAt'           => Yii::t('app', 'Removed At'),
			'uasRemovedBy'           => Yii::t('app', 'Removed By'),
			'uasRemovedBy_User'      => Yii::t('app', 'Removed By'),
		];
	}

}
