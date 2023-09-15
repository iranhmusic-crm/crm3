<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\models;

use shopack\base\common\rest\ModelColumnHelper;
use shopack\base\common\rest\enuColumnInfo;
use shopack\base\common\rest\enuColumnSearchType;
use iranhmusic\shopack\mha\common\enums\enuDocumentStatus;

/*
'docID',
'docUUID',
'docName',
'docType',
'docStatus',
'docCreatedAt',
'docCreatedBy',
'docUpdatedAt',
'docUpdatedBy',
'docRemovedAt',
'docRemovedBy',
*/
trait DocumentModelTrait
{
  public static $primaryKey = ['docID'];

	public function primaryKeyValue() {
		return $this->docID;
	}

	public static function columnsInfo()
	{
		return [
			'docID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
      'docUUID' => ModelColumnHelper::UUID(),
			'docName' => [
				enuColumnInfo::type       => ['string', 'max' => 128],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::like,
			],
			'docType' => [
				enuColumnInfo::type       => ['string', 'max' => 1],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null, //enuDocumentType
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
			'docStatus' => [
				enuColumnInfo::isStatus   => true,
				enuColumnInfo::type       => ['string', 'max' => 1],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => enuDocumentStatus::Active,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],

			'docCreatedAt' => ModelColumnHelper::CreatedAt(),
      'docCreatedBy' => ModelColumnHelper::CreatedBy(),
      'docUpdatedAt' => ModelColumnHelper::UpdatedAt(),
      'docUpdatedBy' => ModelColumnHelper::UpdatedBy(),
			'docRemovedAt' => ModelColumnHelper::RemovedAt(),
			'docRemovedBy' => ModelColumnHelper::RemovedBy(),
		];
	}

	public function getCreatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'docCreatedBy']);
	}

	public function getUpdatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'docUpdatedBy']);
	}

	public function getRemovedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'docRemovedBy']);
	}

}
