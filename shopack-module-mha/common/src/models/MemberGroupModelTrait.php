<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\models;

use shopack\base\common\rest\ModelColumnHelper;
use shopack\base\common\rest\enuColumnInfo;
use shopack\base\common\rest\enuColumnSearchType;
// use shopack\base\common\validators\JsonValidator;
// use shopack\base\common\accounting\enums\enuDiscountType;
use iranhmusic\shopack\mha\common\enums\enuMemberGroupStatus;

/*
'mgpID',
'mgpUUID',
'mgpName',
'mgpMembershipDiscountAmount',
'mgpMembershipDiscountType',
'mgpMembershipCardDiscountAmount',
'mgpMembershipCardDiscountType',
'mgpDeliveryDiscountAmount',
'mgpDeliveryDiscountType',
'mgpI18NData',
'mgpStatus',
'mgpCreatedAt',
'mgpCreatedBy',
'mgpUpdatedAt',
'mgpUpdatedBy',
'mgpRemovedAt',
'mgpRemovedBy',
*/
trait MemberGroupModelTrait
{
  public static $primaryKey = ['mgpID'];

	public function primaryKeyValue() {
		return $this->mgpID;
	}

  public static function columnsInfo()
  {
    return [
      'mgpID' => [
        enuColumnInfo::type       => 'integer',
        enuColumnInfo::validator  => null,
        enuColumnInfo::default    => null,
        enuColumnInfo::required   => false,
        enuColumnInfo::selectable => true,
      ],
      'mgpUUID' => ModelColumnHelper::UUID(),
			'mgpName' => [
        enuColumnInfo::type       => ['string', 'max' => 64],
        enuColumnInfo::validator  => null,
        enuColumnInfo::default    => null,
        enuColumnInfo::required   => false,
        enuColumnInfo::selectable => true,
      ],
      'mgpMembershipDiscountAmount' => [
        enuColumnInfo::type       => ['integer', 'min' => 0],
        enuColumnInfo::validator  => null,
        enuColumnInfo::default    => null,
        enuColumnInfo::required   => false,
        enuColumnInfo::selectable => true,
      ],
      'mgpMembershipDiscountType' => [
        enuColumnInfo::type       => ['string', 'max' => 1],
        enuColumnInfo::validator  => null,
        enuColumnInfo::default    => null, //enuDiscountType::Percent,
        enuColumnInfo::required   => false,
        enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
      ],
      'mgpMembershipCardDiscountAmount' => [
        enuColumnInfo::type       => ['integer', 'min' => 0],
        enuColumnInfo::validator  => null,
        enuColumnInfo::default    => null,
        enuColumnInfo::required   => false,
        enuColumnInfo::selectable => true,
      ],
      'mgpMembershipCardDiscountType' => [
        enuColumnInfo::type       => ['string', 'max' => 1],
        enuColumnInfo::validator  => null,
        enuColumnInfo::default    => null, //enuDiscountType::Percent,
        enuColumnInfo::required   => false,
        enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
      ],
      'mgpDeliveryDiscountAmount' => [
        enuColumnInfo::type       => ['integer', 'min' => 0],
        enuColumnInfo::validator  => null,
        enuColumnInfo::default    => null,
        enuColumnInfo::required   => false,
        enuColumnInfo::selectable => true,
      ],
      'mgpDeliveryDiscountType' => [
        enuColumnInfo::type       => ['string', 'max' => 1],
        enuColumnInfo::validator  => null,
        enuColumnInfo::default    => null, //enuDiscountType::Percent,
        enuColumnInfo::required   => false,
        enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
      ],

			'mgpI18NData' => ModelColumnHelper::I18NData(['mgpName']),
      'mgpStatus' => [
        enuColumnInfo::isStatus   => true,
        enuColumnInfo::type       => ['string', 'max' => 1],
        enuColumnInfo::validator  => null,
        enuColumnInfo::default    => enuMemberGroupStatus::Active,
        enuColumnInfo::required   => true,
        enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
      ],

      'mgpCreatedAt' => ModelColumnHelper::CreatedAt(),
      'mgpCreatedBy' => ModelColumnHelper::CreatedBy(),
      'mgpUpdatedAt' => ModelColumnHelper::UpdatedAt(),
      'mgpUpdatedBy' => ModelColumnHelper::UpdatedBy(),
      'mgpRemovedAt' => ModelColumnHelper::RemovedAt(),
      'mgpRemovedBy' => ModelColumnHelper::RemovedBy(),
    ];
  }

  public function getCreatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mgpCreatedBy']);
	}

	public function getUpdatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mgpUpdatedBy']);
	}

	public function getRemovedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mgpRemovedBy']);
	}

}
