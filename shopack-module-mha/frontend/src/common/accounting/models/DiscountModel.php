<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\accounting\models;

use Yii;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\frontend\common\rest\RestClientActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuDocumentStatus;
use shopack\base\common\accounting\enums\enuAmountType;
use shopack\base\common\accounting\enums\enuDiscountType;
use shopack\base\frontend\common\helpers\Html;

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
			'dscType'                    => Yii::t('aaa', 'Discount Type'),

			'dscCodeString'              => Yii::t('aaa', 'Discount Code'),
			'dscCodeHasSerial'           => Yii::t('aaa', 'Code Has Serial'),
			'dscCodeSerialCount'         => Yii::t('aaa', 'Code Serial Count'),
			'dscCodeSerialLength'        => Yii::t('aaa', 'Code SerialL ength'),

			'dscValidFrom'               => Yii::t('app', 'Valid From Date'),
			'dscValidTo'                 => Yii::t('app', 'Valid To Date'),
			'dscTotalMaxCount'           => Yii::t('aaa', 'Total Max Count'),
			'dscTotalMaxPrice'           => Yii::t('aaa', 'Total Max Amount'),
			'dscPerUserMaxCount'         => Yii::t('aaa', 'Per User Max Count'),
			'dscPerUserMaxPrice'         => Yii::t('aaa', 'Per User Max Amount'),
			'dscTargetUserIDs'           => Yii::t('mha', 'Members'),
			'dscTargetProductIDs'        => Yii::t('aaa', 'Products'),
			'dscTargetSaleableIDs'       => Yii::t('aaa', 'Saleables'),
			'dscReferrers'               => Yii::t('aaa', 'Referrers'),
			'dscSaleableBasedMultiplier' => Yii::t('aaa', 'Saleable Based Multiplier'),
			'dscAmount'               	 => Yii::t('aaa', 'Discount Amount'),
			'dscAmountType'              => Yii::t('aaa', 'Amount Type'),
			'dscMaxAmount'               => Yii::t('aaa', 'Max Amount'),
			'dscSaleableBasedMultiplier' => Yii::t('aaa', 'Saleable Based Multiplier'),
			'dscTotalUsedCount'          => Yii::t('aaa', 'Total Used Count'),
			'dscTotalUsedPrice'          => Yii::t('aaa', 'Total Used Price'),
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

			//-- mha --
			'dscTargetMemberGroupIDs'    => Yii::t('mha', 'Member Groups'),
			'dscTargetKanoonIDs'         => Yii::t('mha', 'Kanoons'),
			'dscTargetProductMhaTypes'   => Yii::t('mha', 'Mha Product Type'),
		];
	}

	public function extraRules()
	{
    $fnGetConst = function($value) { return $value; };
		$fnGetConstQouted = function($value) { return "'{$value}'"; };
		$fnGetFieldId = function($field) { return Html::getInputId($this, $field); };

		return [
			['dscCodeString',
				'required',
				'whenClient' => "function (attribute, value) {
					return ($('#{$fnGetFieldId('dscType')} :checked').val() == {$fnGetConstQouted(enuDiscountType::Coupon)});
				}",
			],
			['dscCodeSerialCount',
				'required',
				'whenClient' => "function (attribute, value) {
					return (($('#{$fnGetFieldId('dscType')} :checked').val() == {$fnGetConstQouted(enuDiscountType::Coupon)}) && $('#{$fnGetFieldId('dscCodeHasSerial')}')[0].checked);
				}",
			],
			['dscCodeSerialLength',
				'required',
				'whenClient' => "function (attribute, value) {
					return (($('#{$fnGetFieldId('dscType')} :checked').val() == {$fnGetConstQouted(enuDiscountType::Coupon)}) && $('#{$fnGetFieldId('dscCodeHasSerial')}')[0].checked);
				}",
			],
			['dscAmount',
				'number',
				'min' => 0,
				'max' => 100,
				'whenClient' => "function (attribute, value) {
					return ($('#{$fnGetFieldId('dscAmountType')} :checked').val() == {$fnGetConstQouted(enuAmountType::Percent)});
				}",
			],
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
