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
'mbrminsdocID',
'mbrminsdocUUID',
'mbrminsdocMemberID',
'mbrminsdocDocNumber',
'mbrminsdocDocDate',
'mbrminsdocHistory',
'mbrminsdocStatus',
'mbrminsdocCreatedAt',
'mbrminsdocCreatedBy',
'mbrminsdocUpdatedAt',
'mbrminsdocUpdatedBy',
'mbrminsdocRemovedAt',
'mbrminsdocRemovedBy',
*/
trait MemberMasterInsDocModelTrait
{
	public static $primaryKey = ['mbrminsdocID'];

	public function primaryKeyValue() {
		return $this->mbrminsdocID;
	}

	public function columnsInfo()
	{
		return [
			'mbrminsdocID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
      'mbrminsdocUUID' => ModelColumnHelper::UUID(),
			'mbrminsdocMemberID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
			'mbrminsdocDocNumber' => [
				enuColumnInfo::type       => ['string', 'max' => 64],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        // enuColumnInfo::search     => null,
			],
			'mbrminsdocDocDate' => [
				enuColumnInfo::type       => 'safe',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
			],
			'mbrminsdocHistory' => [
				enuColumnInfo::type       => JsonValidator::class,
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        // enuColumnInfo::search     => null,
			],
			'mbrminsdocStatus' => [
				enuColumnInfo::isStatus   => true,
				enuColumnInfo::type       => ['string', 'max' => 1],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => enuInsurerDocStatus::WaitForSurvey,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],

			'mbrminsdocCreatedAt' => ModelColumnHelper::CreatedAt(),
      'mbrminsdocCreatedBy' => ModelColumnHelper::CreatedBy(),
      'mbrminsdocUpdatedAt' => ModelColumnHelper::UpdatedAt(),
      'mbrminsdocUpdatedBy' => ModelColumnHelper::UpdatedBy(),
			'mbrminsdocRemovedAt' => ModelColumnHelper::RemovedAt(),
			'mbrminsdocRemovedBy' => ModelColumnHelper::RemovedBy(),
		];
	}

	public function getMember() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\iranhmusic\shopack\mha\backend\models\MemberModel';
		else
			$className = '\iranhmusic\shopack\mha\frontend\common\models\MemberModel';

		return $this->hasOne($className, ['mbrUserID' => 'mbrminsdocMemberID']);
	}

	public function getCreatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mbrminsdocCreatedBy']);
	}

	public function getUpdatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mbrminsdocUpdatedBy']);
	}

	public function getRemovedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mbrminsdocRemovedBy']);
	}

}
