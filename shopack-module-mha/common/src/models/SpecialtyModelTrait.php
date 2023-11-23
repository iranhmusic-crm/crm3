<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\models;

use shopack\base\common\rest\ModelColumnHelper;
use shopack\base\common\rest\enuColumnInfo;
use shopack\base\common\rest\enuColumnSearchType;
// use shopack\base\common\validators\JsonValidator;
use iranhmusic\shopack\mha\common\enums\enuSpecialtyStatus;

/*
'spcID',
'spcUUID',

'spcRoot' INT(10) UNSIGNED NULL DEFAULT NULL,
'spcLeft' INT(10) UNSIGNED NOT NULL,
'spcRight' INT(10) UNSIGNED NOT NULL,
'spcLevel' SMALLINT(5) UNSIGNED NOT NULL,
'spcName' VARCHAR(60) NOT NULL COLLATE 'utf8mb4_unicode_ci',
'spcDesc' MEDIUMTEXT NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',

'spcDescFieldType',
'spcDescFieldLabel',

'spcStatus',
'spcCreatedAt',
'spcCreatedBy',
'spcUpdatedAt',
'spcUpdatedBy',
'spcRemovedAt',
'spcRemovedBy',
*/
trait SpecialtyModelTrait
{
	public static $primaryKey = ['spcID'];

	public function primaryKeyValue() {
		return $this->spcID;
	}

	public function columnsInfo()
	{
		return [
			'spcID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false, //true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
      'spcUUID' => ModelColumnHelper::UUID(),
			'spcRoot' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
			'spcLeft' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
			'spcRight' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
			'spcLevel' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
			'spcName' => [
				enuColumnInfo::type       => ['string', 'max' => 64],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
			'spcDesc' => [
				enuColumnInfo::type       => 'string',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false, //true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
			'spcDescFieldType' => [
				enuColumnInfo::type       => ['string', 'max' => 64],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
				enuColumnInfo::search     => enuColumnSearchType::exact,
			],
			'spcDescFieldLabel' => [
				enuColumnInfo::type       => ['string', 'max' => 64],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
				enuColumnInfo::search     => enuColumnSearchType::exact,
			],
			'spcStatus' => [
				enuColumnInfo::isStatus   => true,
				enuColumnInfo::type       => ['string', 'max' => 1],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => enuSpecialtyStatus::Active,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],

			'spcCreatedAt' => ModelColumnHelper::CreatedAt(),
      'spcCreatedBy' => ModelColumnHelper::CreatedBy(),
      'spcUpdatedAt' => ModelColumnHelper::UpdatedAt(),
      'spcUpdatedBy' => ModelColumnHelper::UpdatedBy(),
			'spcRemovedAt' => ModelColumnHelper::RemovedAt(),
			'spcRemovedBy' => ModelColumnHelper::RemovedBy(),
		];
	}

	public function getCreatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'spcCreatedBy']);
	}

	public function getUpdatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'spcUpdatedBy']);
	}

	public function getRemovedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'spcRemovedBy']);
	}

}
