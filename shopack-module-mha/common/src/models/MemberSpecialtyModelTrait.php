<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\models;

use shopack\base\common\rest\ModelColumnHelper;
use shopack\base\common\rest\enuColumnInfo;
use shopack\base\common\rest\enuColumnSearchType;
use shopack\base\common\validators\JsonValidator;

/*
'mbrspcID',
'mbrspcUUID',
'mbrspcMemberID',
'mbrspcSpecialtyID',
'mbrspcDesc',
'mbrspcCreatedAt',
'mbrspcCreatedBy',
'mbrspcUpdatedAt',
'mbrspcUpdatedBy',
'mbrspcRemovedAt',
'mbrspcRemovedBy',
*/
trait MemberSpecialtyModelTrait
{
  public static $primaryKey = 'mbrspcID'; //['mbrspcMemberID', 'mbrspcSpecialtyID'];

	public function primaryKeyValue() {
		return $this->mbrspcID;
	}

	public static function columnsInfo()
	{
		return [
			'mbrspcID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
      'mbrspcUUID' => ModelColumnHelper::UUID(),
			'mbrspcMemberID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
			'mbrspcSpecialtyID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
			'mbrspcDesc' => [
				enuColumnInfo::type       => JsonValidator::class,
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        // enuColumnInfo::search     => null,
			],

			'mbrspcCreatedAt' => ModelColumnHelper::CreatedAt(),
      'mbrspcCreatedBy' => ModelColumnHelper::CreatedBy(),
      'mbrspcUpdatedAt' => ModelColumnHelper::UpdatedAt(),
      'mbrspcUpdatedBy' => ModelColumnHelper::UpdatedBy(),
			'mbrspcRemovedAt' => ModelColumnHelper::RemovedAt(),
			'mbrspcRemovedBy' => ModelColumnHelper::RemovedBy(),
		];
	}

	public function getCreatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mbrspcCreatedBy']);
	}

	public function getUpdatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mbrspcUpdatedBy']);
	}

	public function getRemovedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mbrspcRemovedBy']);
	}

	public function getMember() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\iranhmusic\shopack\mha\backend\models\MemberModel';
		else
			$className = '\iranhmusic\shopack\mha\frontend\common\models\MemberModel';

		return $this->hasOne($className, ['mbrUserID' => 'mbrspcMemberID']);
	}

	public function getSpecialty() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\iranhmusic\shopack\mha\backend\models\SpecialtyModel';
		else
			$className = '\iranhmusic\shopack\mha\frontend\common\models\SpecialtyModel';

		return $this->hasOne($className, ['spcID' => 'mbrspcSpecialtyID']);
	}

}
