<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\models;

use shopack\base\common\rest\ModelColumnHelper;
use shopack\base\common\rest\enuColumnInfo;
use iranhmusic\shopack\mha\common\enums\enuMembershipStatus;

/*
'mshpID',
'mshpUUID',
'mshpTitle',
'mshpStartFrom',
'mshpYearlyPrice',
'mshpStatus',
'mshpCreatedAt',
'mshpCreatedBy',
'mshpUpdatedAt',
'mshpUpdatedBy',
'mshpRemovedAt',
'mshpRemovedBy',
*/
trait MembershipModelTrait
{
	public function primaryKeyValue() {
		return $this->mshpID;
	}

	public static function columnsInfo()
	{
		return [
			'mshpID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],
      'mshpUUID' => ModelColumnHelper::UUID(),
			'mshpTitle' => [
				enuColumnInfo::type       => ['string', 'max' => 64],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => 'like',
			],
			'mshpStartFrom' => [
				enuColumnInfo::type       => 'safe',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],
			'mshpYearlyPrice' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],
			'mshpStatus' => [
				enuColumnInfo::isStatus   => true,
				enuColumnInfo::type       => ['string', 'max' => 1],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => enuMembershipStatus::Active,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],

			'mshpCreatedAt' => ModelColumnHelper::CreatedAt(),
      'mshpCreatedBy' => ModelColumnHelper::CreatedBy(),
      'mshpUpdatedAt' => ModelColumnHelper::UpdatedAt(),
      'mshpUpdatedBy' => ModelColumnHelper::UpdatedBy(),
			'mshpRemovedAt' => ModelColumnHelper::RemovedAt(),
			'mshpRemovedBy' => ModelColumnHelper::RemovedBy(),
		];
	}

	public function getCreatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mshpCreatedBy']);
	}

	public function getUpdatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mshpUpdatedBy']);
	}

	public function getRemovedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mshpRemovedBy']);
	}

}
