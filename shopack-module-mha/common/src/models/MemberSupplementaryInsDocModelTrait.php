<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\models;

use shopack\base\common\rest\ModelColumnHelper;
use shopack\base\common\rest\enuColumnInfo;
use shopack\base\common\rest\enuColumnSearchType;
use shopack\base\common\validators\JsonValidator;
use iranhmusic\shopack\mha\common\enums\enuInsurerDocStatus;

/*
'mbrsinsdocID',
'mbrsinsdocUUID',
'mbrsinsdocMemberID',
'mbrsinsdocSupplementaryInsurerID',
'mbrsinsdocDocNumber',
'mbrsinsdocDocDate',
'mbrsinsdocHistory',
'mbrsinsdocStatus',
'mbrsinsdocCreatedAt',
'mbrsinsdocCreatedBy',
'mbrsinsdocUpdatedAt',
'mbrsinsdocUpdatedBy',
*/
trait MemberSupplementaryInsDocModelTrait
{
	public static $primaryKey = ['mbrsinsdocID'];

	public function primaryKeyValue() {
		return $this->mbrsinsdocID;
	}

	public static function columnsInfo()
	{
		return [
			'mbrsinsdocID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
      'mbrsinsdocUUID' => ModelColumnHelper::UUID(),
			'mbrsinsdocMemberID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
			'mbrsinsdocSupplementaryInsurerID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
			'mbrsinsdocDocNumber' => [
				enuColumnInfo::type       => ['string', 'max' => 64],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        // enuColumnInfo::search     => null,
			],
			'mbrsinsdocDocDate' => [
				enuColumnInfo::type       => 'safe',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
			],
			'mbrsinsdocHistory' => [
				enuColumnInfo::type       => JsonValidator::class,
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        // enuColumnInfo::search     => null,
			],
			'mbrsinsdocStatus' => [
				enuColumnInfo::isStatus   => true,
				enuColumnInfo::type       => ['string', 'max' => 1],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => enuInsurerDocStatus::WaitForSurvey,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],

			'mbrsinsdocCreatedAt' => ModelColumnHelper::CreatedAt(),
      'mbrsinsdocCreatedBy' => ModelColumnHelper::CreatedBy(),
      'mbrsinsdocUpdatedAt' => ModelColumnHelper::UpdatedAt(),
      'mbrsinsdocUpdatedBy' => ModelColumnHelper::UpdatedBy(),
      'mbrsinsdocRemovedAt' => ModelColumnHelper::RemovedAt(),
      'mbrsinsdocRemovedBy' => ModelColumnHelper::RemovedBy(),
		];
	}

	public function getMember() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\iranhmusic\shopack\mha\backend\models\MemberModel';
		else
			$className = '\iranhmusic\shopack\mha\frontend\common\models\MemberModel';

		return $this->hasOne($className, ['mbrUserID' => 'mbrsinsdocMemberID']);
	}

	public function getSupplementaryInsurer() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\iranhmusic\shopack\mha\backend\models\SupplementaryInsurerModel';
		else
			$className = '\iranhmusic\shopack\mha\frontend\common\models\SupplementaryInsurerModel';

		return $this->hasOne($className, ['sinsID' => 'mbrsinsdocSupplementaryInsurerID']);
	}

	public function getCreatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mbrsinsdocCreatedBy']);
	}

	public function getUpdatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mbrsinsdocUpdatedBy']);
	}

	public function getRemovedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mbrsinsdocRemovedBy']);
	}

}
