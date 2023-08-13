<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\models;

use shopack\base\common\rest\ModelColumnHelper;
use shopack\base\common\rest\enuColumnInfo;
// use shopack\base\common\validators\JsonValidator;
use iranhmusic\shopack\mha\common\enums\enuInsurerStatus;

/*
'sinsID',
'sinsUUID',
'sinsName',
'sinsStatus',
'sinsCreatedAt',
'sinsCreatedBy',
'sinsUpdatedAt',
'sinsUpdatedBy',
'sinsRemovedAt',
'sinsRemovedBy',
*/
trait SupplementaryInsurerModelTrait
{
	public function primaryKeyValue() {
		return $this->sinsID;
	}

	public static function columnsInfo()
	{
		return [
			'sinsID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],
      'sinsUUID' => ModelColumnHelper::UUID(),
			'sinsName' => [
				enuColumnInfo::type       => ['string', 'max' => 64],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => 'like',
			],
			'sinsStatus' => [
				enuColumnInfo::isStatus   => true,
				enuColumnInfo::type       => ['string', 'max' => 1],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => enuInsurerStatus::Active,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],

			'sinsCreatedAt' => ModelColumnHelper::CreatedAt(),
      'sinsCreatedBy' => ModelColumnHelper::CreatedBy(),
      'sinsUpdatedAt' => ModelColumnHelper::UpdatedAt(),
      'sinsUpdatedBy' => ModelColumnHelper::UpdatedBy(),
			'sinsRemovedAt' => ModelColumnHelper::RemovedAt(),
			'sinsRemovedBy' => ModelColumnHelper::RemovedBy(),
		];
	}

	public function getCreatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'sinsCreatedBy']);
	}

	public function getUpdatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'sinsUpdatedBy']);
	}

	public function getRemovedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'sinsRemovedBy']);
	}

}
