<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\models;

use shopack\base\common\rest\ModelColumnHelper;
use shopack\base\common\rest\enuColumnInfo;
use shopack\base\common\validators\JsonValidator;

/*
'mbrminshstID',
'mbrminshstUUID',
'mbrminshstMemberID',
'mbrminshstMasterInsTypeID',
'mbrminshstSubstation',
'mbrminshstStartDate',
'mbrminshstEndDate',
'mbrminshstInsuranceCode',
'mbrminshstCoCode',
'mbrminshstCoName',
'mbrminshstIssuanceDate',
'mbrminshstCreatedAt',
'mbrminshstCreatedBy',
'mbrminshstUpdatedAt',
'mbrminshstUpdatedBy',
'mbrminshstRemovedAt',
'mbrminshstRemovedBy',
*/
trait MemberMasterInsuranceModelTrait
{
	public function primaryKeyValue() {
		return $this->mbrminshstID;
	}

	public static function columnsInfo()
	{
		return [
			'mbrminshstID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],
      'mbrminshstUUID' => ModelColumnHelper::UUID(),
			'mbrminshstMemberID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],
			'mbrminshstMasterInsTypeID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],
			'mbrminshstSubstation' => [
				enuColumnInfo::type       => ['string', 'max' => 128], //JsonValidator::class,
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        // enuColumnInfo::search     => null,
			],
			'mbrminshstStartDate' => [
				enuColumnInfo::type       => 'safe',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
			],
			'mbrminshstEndDate' => [
				enuColumnInfo::type       => 'safe',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
			],
			'mbrminshstInsuranceCode' => [
				enuColumnInfo::type       => ['string', 'max' => 32],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
				// enuColumnInfo::search     => null,
			],
			'mbrminshstCoCode' => [
				enuColumnInfo::type       => ['string', 'max' => 32],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
				// enuColumnInfo::search     => null,
			],
			'mbrminshstCoName' => [
				enuColumnInfo::type       => ['string', 'max' => 128],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
				// enuColumnInfo::search     => null,
			],
			'mbrminshstIssuanceDate' => [
				enuColumnInfo::type       => 'safe',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
			],

			'mbrminshstCreatedAt' => ModelColumnHelper::CreatedAt(),
      'mbrminshstCreatedBy' => ModelColumnHelper::CreatedBy(),
      'mbrminshstUpdatedAt' => ModelColumnHelper::UpdatedAt(),
      'mbrminshstUpdatedBy' => ModelColumnHelper::UpdatedBy(),
			'mbrminshstRemovedAt' => ModelColumnHelper::RemovedAt(),
			'mbrminshstRemovedBy' => ModelColumnHelper::RemovedBy(),
		];
	}

	public function getCreatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mbrminshstCreatedBy']);
	}

	public function getUpdatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mbrminshstUpdatedBy']);
	}

	public function getRemovedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mbrminshstRemovedBy']);
	}

	public function getMember() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\iranhmusic\shopack\mha\backend\models\MemberModel';
		else
			$className = '\iranhmusic\shopack\mha\frontend\common\models\MemberModel';

		return $this->hasOne($className, ['mbrUserID' => 'mbrminshstMemberID']);
	}

	public function getMasterInsuranceType() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\iranhmusic\shopack\mha\backend\models\MasterInsurerTypeModel';
		else
			$className = '\iranhmusic\shopack\mha\frontend\common\models\MasterInsurerTypeModel';

		return $this->hasOne($className, ['minstypID' => 'mbrminshstMasterInsTypeID']);
	}

}
