<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use shopack\aaa\backend\classes\AAAActiveRecord;
use shopack\base\common\accounting\enums\enuAmountType;
use yii\base\InvalidValueException;
use yii\web\UnprocessableEntityHttpException;

class MemberGroupModel extends AAAActiveRecord
{
  use \iranhmusic\shopack\mha\common\models\MemberGroupModelTrait;

	public static function tableName()
	{
		return '{{%MHA_MemberGroup}}';
	}

	public function behaviors()
	{
		return [
			[
				'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
				'createdAtAttribute' => 'mgpCreatedAt',
				'createdByAttribute' => 'mgpCreatedBy',
				'updatedAtAttribute' => 'mgpUpdatedAt',
				'updatedByAttribute' => 'mgpUpdatedBy',
			],
		];
	}

	public function save($runValidation = true, $attributeNames = null)
	{
		if ($this->mgpMembershipDiscountAmount == 0)
			$this->mgpMembershipDiscountAmount = null;
		if (empty($this->mgpMembershipDiscountType) && (empty($this->mgpMembershipDiscountAmount) == false)) {
			$this->mgpMembershipDiscountAmount = null;
		} else if ((empty($this->mgpMembershipDiscountType) == false) && empty($this->mgpMembershipDiscountAmount)) {
			$this->mgpMembershipDiscountType = null;
		}
		if (($this->mgpMembershipDiscountType == enuAmountType::Percent)
			&& ($this->mgpMembershipDiscountAmount > 100)
		) {
			throw new UnprocessableEntityHttpException('The percentage cannot be greater than 100');
		}

		if ($this->mgpMembershipCardDiscountAmount == 0)
			$this->mgpMembershipCardDiscountAmount = null;
		if (empty($this->mgpMembershipCardDiscountType) && (empty($this->mgpMembershipCardDiscountAmount) == false)) {
			$this->mgpMembershipCardDiscountAmount = null;
		} else if ((empty($this->mgpMembershipCardDiscountType) == false) && empty($this->mgpMembershipCardDiscountAmount)) {
			$this->mgpMembershipCardDiscountType = null;
		}
		if (($this->mgpMembershipCardDiscountType == enuAmountType::Percent)
			&& ($this->mgpMembershipCardDiscountAmount > 100)
		) {
			throw new UnprocessableEntityHttpException('The percentage cannot be greater than 100');
		}

		if ($this->mgpDeliveryDiscountAmount == 0)
			$this->mgpDeliveryDiscountAmount = null;
		if (empty($this->mgpDeliveryDiscountType) && (empty($this->mgpDeliveryDiscountAmount) == false)) {
			$this->mgpDeliveryDiscountAmount = null;
		} else if ((empty($this->mgpDeliveryDiscountType) == false) && empty($this->mgpDeliveryDiscountAmount)) {
			$this->mgpDeliveryDiscountType = null;
		}
		if (($this->mgpDeliveryDiscountType == enuAmountType::Percent)
			&& ($this->mgpDeliveryDiscountAmount > 100)
		) {
			throw new UnprocessableEntityHttpException('The percentage cannot be greater than 100');
		}

		return parent::save($runValidation, $attributeNames);
	}

}
