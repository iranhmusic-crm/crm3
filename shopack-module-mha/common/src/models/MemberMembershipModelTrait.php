<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\models;

use shopack\base\common\rest\ModelColumnHelper;
use shopack\base\common\rest\enuColumnInfo;
use iranhmusic\shopack\mha\common\enums\enuMemberMembershipStatus;

/*
'mbrshpID',
'mbrshpUUID',
'mbrshpMemberID',
'mbrshpMembershipID',
'mbrshpVoucherID',
'mbrshpStartDate',
'mbrshpEndDate',
'mbrshpStatus',
'mbrshpCreatedAt',
'mbrshpCreatedBy',
'mbrshpUpdatedAt',
'mbrshpUpdatedBy',
'mbrshpRemovedAt',
'mbrshpRemovedBy',
*/
trait MemberMembershipModelTrait
{
	public function primaryKeyValue() {
		return $this->mbrshpID;
	}

	public static function columnsInfo()
	{
		return [
			'mbrshpID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],
      'mbrshpUUID' => ModelColumnHelper::UUID(),
			'mbrshpMemberID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],
			'mbrshpMembershipID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],
			'mbrshpVoucherID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false, //true?
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],
			'mbrshpStartDate' => [
				enuColumnInfo::type       => 'safe',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],
			'mbrshpEndDate' => [
				enuColumnInfo::type       => 'safe',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],
			'mbrshpStatus' => [
				enuColumnInfo::isStatus   => true,
				enuColumnInfo::type       => ['string', 'max' => 1],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => enuMemberMembershipStatus::WaitForPay,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
				enuColumnInfo::search     => true,
			],

			'mbrshpCreatedAt' => ModelColumnHelper::CreatedAt(),
      'mbrshpCreatedBy' => ModelColumnHelper::CreatedBy(),
      'mbrshpUpdatedAt' => ModelColumnHelper::UpdatedAt(),
      'mbrshpUpdatedBy' => ModelColumnHelper::UpdatedBy(),
			'mbrshpRemovedAt' => ModelColumnHelper::RemovedAt(),
			'mbrshpRemovedBy' => ModelColumnHelper::RemovedBy(),
		];
	}

	public function getCreatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mbrshpCreatedBy']);
	}

	public function getUpdatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mbrshpUpdatedBy']);
	}

	public function getRemovedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mbrshpRemovedBy']);
	}

	public function getMember() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\iranhmusic\shopack\mha\backend\models\MemberModel';
		else
			$className = '\iranhmusic\shopack\mha\frontend\common\models\MemberModel';

		return $this->hasOne($className, ['mbrUserID' => 'mbrshpMemberID']);
	}

	public function getMembership() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\iranhmusic\shopack\mha\backend\models\MembershipModel';
		else
			$className = '\iranhmusic\shopack\mha\frontend\common\models\MembershipModel';

		return $this->hasOne($className, ['mshpID' => 'mbrshpMembershipID']);
	}

	public function getVoucher() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\VoucherModel';
		else
			$className = '\shopack\aaa\frontend\common\models\VoucherModel';

		return $this->hasOne($className, ['vchID' => 'mbrshpVoucherID']);
	}

}
