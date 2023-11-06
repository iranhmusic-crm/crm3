<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\models;

use Yii;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\frontend\common\rest\RestClientActiveRecord;
// use iranhmusic\shopack\mha\common\enums\enuMemberGroupStatus;

class MemberGroupModel extends RestClientActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\MemberGroupModelTrait;

	public static $resourceName = 'mha/member-group';

	public function attributeLabels()
	{
		return [
			'mgpID'               						=> Yii::t('app', 'ID'),
			'mgpName'             						=> Yii::t('app', 'Name'),
			'mgpMembershipDiscountAmount'     => Yii::t('mha', 'Membership Discount Amount'),
			'mgpMembershipDiscountType'       => Yii::t('mha', 'Membership Discount Type'),
			'mgpMembershipCardDiscountAmount' => Yii::t('mha', 'Membership Card Discount Amount'),
			'mgpMembershipCardDiscountType'   => Yii::t('mha', 'Membership Card Discount Type'),
			'mgpDeliveryDiscountAmount'       => Yii::t('mha', 'Delivery Discount Amount'),
			'mgpDeliveryDiscountType'         => Yii::t('mha', 'Delivery Discount Type'),
			'mgpStatus'           						=> Yii::t('app', 'Status'),
			'mgpCreatedAt'        						=> Yii::t('app', 'Created At'),
			'mgpCreatedBy'        						=> Yii::t('app', 'Created By'),
			'mgpCreatedBy_User'   						=> Yii::t('app', 'Created By'),
			'mgpUpdatedAt'        						=> Yii::t('app', 'Updated At'),
			'mgpUpdatedBy'        						=> Yii::t('app', 'Updated By'),
			'mgpUpdatedBy_User'   						=> Yii::t('app', 'Updated By'),
			'mgpRemovedAt'        						=> Yii::t('app', 'Removed At'),
			'mgpRemovedBy'        						=> Yii::t('app', 'Removed By'),
			'mgpRemovedBy_User'   						=> Yii::t('app', 'Removed By'),
		];
	}

	// public function extraRules()
	// {
  //   $fnGetConst = function($value) { return $value; };
	// 	$fnGetFieldId = function($field) { return Html::getInputId($this, $field); };

	// 	return [
	// 		['mgpMembershipDiscountAmount',
	// 			'required',
	// 			'when' => function ($model) {
	// 				return (empty($model->mgpMembershipDiscountType) == false);
	// 			},
	// 			'whenClient' => "function (attribute, value) {
	// 				return ($('#{$fnGetFieldId('mgpMembershipDiscountType')}').val() != '');
	// 			}"
	// 		],
	// 	];
	// }

	public function isSoftDeleted()
  {
    return false; //($this->mgpStatus == enuMemberGroupStatus::Removed);
  }

	public static function canCreate() {
		return true;
	}

	public function canUpdate() {
		return true; //($this->mgpStatus != enuMemberGroupStatus::Removed);
	}

	public function canDelete() {
		return true; //($this->mgpStatus != enuMemberGroupStatus::Removed);
	}

	public function canUndelete() {
		return false; //($this->mgpStatus == enuMemberGroupStatus::Removed);
	}

}
