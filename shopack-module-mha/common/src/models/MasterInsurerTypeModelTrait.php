<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\models;

use shopack\base\common\rest\ModelColumnHelper;
use shopack\base\common\rest\enuColumnInfo;
use shopack\base\common\rest\enuColumnSearchType;
// use shopack\base\common\validators\JsonValidator;
use iranhmusic\shopack\mha\common\enums\enuInsurerStatus;

/*
'minstypID',
'minstypUUID',
'minstypMasterInsurerID,
'minstypName',
'minstypStatus',
'minstypCreatedAt',
'minstypCreatedBy',
'minstypUpdatedAt',
'minstypUpdatedBy',
'minstypRemovedAt',
'minstypRemovedBy',
*/
trait MasterInsurerTypeModelTrait
{
	public static $primaryKey = ['minstypID'];

	public function primaryKeyValue() {
		return $this->minstypID;
	}

	public function columnsInfo()
	{
		return [
			'minstypID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
      'minstypUUID' => ModelColumnHelper::UUID(),
			'minstypMasterInsurerID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
			'minstypName' => [
				enuColumnInfo::type       => ['string', 'max' => 64],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::like,
			],
			'minstypStatus' => [
				enuColumnInfo::isStatus   => true,
				enuColumnInfo::type       => ['string', 'max' => 1],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => enuInsurerStatus::Active,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],

			'minstypCreatedAt' => ModelColumnHelper::CreatedAt(),
      'minstypCreatedBy' => ModelColumnHelper::CreatedBy(),
      'minstypUpdatedAt' => ModelColumnHelper::UpdatedAt(),
      'minstypUpdatedBy' => ModelColumnHelper::UpdatedBy(),
			'minstypRemovedAt' => ModelColumnHelper::RemovedAt(),
			'minstypRemovedBy' => ModelColumnHelper::RemovedBy(),
		];
	}

	public function getCreatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'minstypCreatedBy']);
	}

	public function getUpdatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'minstypUpdatedBy']);
	}

	public function getRemovedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'minstypRemovedBy']);
	}

	public function getMasterInsurer() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\iranhmusic\shopack\mha\backend\models\MasterInsurerModel';
		else
			$className = '\iranhmusic\shopack\mha\frontend\common\models\MasterInsurerModel';

		return $this->hasOne($className, ['minsID' => 'minstypMasterInsurerID']);
	}

}
