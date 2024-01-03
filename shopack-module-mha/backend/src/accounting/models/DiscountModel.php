<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\accounting\models;

use Yii;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;
use shopack\base\common\accounting\enums\enuDiscountStatus;

class DiscountModel extends MhaActiveRecord
{
	use \iranhmusic\shopack\mha\common\accounting\models\DiscountModelTrait;

	use \shopack\base\common\db\SoftDeleteActiveRecordTrait;
  public function initSoftDelete()
  {
    $this->softdelete_RemovedStatus  = enuDiscountStatus::Removed;
    // $this->softdelete_StatusField    = 'dscType';
    $this->softdelete_RemovedAtField = 'dscRemovedAt';
    $this->softdelete_RemovedByField = 'dscRemovedBy';
	}

	public static function tableName()
	{
		return '{{%MHA_Accounting_Discount}}';
	}

	public function behaviors()
	{
		return [
			[
				'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
				'createdAtAttribute' => 'dscCreatedAt',
				'createdByAttribute' => 'dscCreatedBy',
				'updatedAtAttribute' => 'dscUpdatedAt',
				'updatedByAttribute' => 'dscUpdatedBy',
			],
		];
	}

	public static function findSystemDiscount($saleableID)
	{
		// $userGroups


		// $models = self::find()
		// 	->andWhere([])
		// 	->asArray()
		// 	->all();

/*
'dscID',
'dscUUID',
'dscName',
'dscType',
'dscDiscountGroupID',
'dscCodeString',
'dscCodeHasSerial',
'dscCodeSerialCount',
'dscCodeSerialLength',
'dscValidFrom',
'dscValidTo',
'dscTotalMaxCount',
'dscTotalMaxPrice',
'dscPerUserMaxCount',
'dscPerUserMaxPrice',
'dscTargetUserIDs',
'dscTargetProductIDs',
'dscTargetSaleableIDs',
'dscReferrers',
'dscSaleableBasedMultiplier',
'dscAmount',
'dscAmountType',
'dscMaxAmount',
'dscTotalUsedCount',
'dscTotalUsedPrice',
'dscI18NData',
'dscStatus',

'dscTargetMemberGroupIDs',
'dscTargetKanoonIDs',
'dscTargetProductMhaTypes',
*/
	}

}
