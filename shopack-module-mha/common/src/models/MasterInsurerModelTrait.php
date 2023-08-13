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
'minsID',
'minsUUID',
'minsName',
'minsStatus',
'minsCreatedAt',
'minsCreatedBy',
'minsUpdatedAt',
'minsUpdatedBy',
'minsRemovedAt',
'minsRemovedBy',
*/
trait MasterInsurerModelTrait
{
	public function primaryKeyValue() {
		return $this->minsID;
	}

	public static function columnsInfo()
	{
		return [
			'minsID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],
      'minsUUID' => ModelColumnHelper::UUID(),
			'minsName' => [
				enuColumnInfo::type       => ['string', 'max' => 64],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => 'like',
			],
			'minsStatus' => [
				enuColumnInfo::isStatus   => true,
				enuColumnInfo::type       => ['string', 'max' => 1],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => enuInsurerStatus::Active,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],

			'minsCreatedAt' => ModelColumnHelper::CreatedAt(),
      'minsCreatedBy' => ModelColumnHelper::CreatedBy(),
      'minsUpdatedAt' => ModelColumnHelper::UpdatedAt(),
      'minsUpdatedBy' => ModelColumnHelper::UpdatedBy(),
			'minsRemovedAt' => ModelColumnHelper::RemovedAt(),
			'minsRemovedBy' => ModelColumnHelper::RemovedBy(),
		];
	}

	public function getCreatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'minsCreatedBy']);
	}

	public function getUpdatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'minsUpdatedBy']);
	}

	public function getRemovedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'minsRemovedBy']);
	}

}
