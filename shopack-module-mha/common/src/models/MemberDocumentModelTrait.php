<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\models;

use shopack\base\common\rest\ModelColumnHelper;
use shopack\base\common\rest\enuColumnInfo;
use iranhmusic\shopack\mha\common\enums\enuMemberDocumentStatus;

/*
'mbrdocID',
'mbrdocUUID',
'mbrdocMemberID',
'mbrdocDocumentID',
'mbrdocTitle',
'mbrdocFileID',
'mbrdocStatus',
'mbrdocCreatedAt',
'mbrdocCreatedBy',
'mbrdocUpdatedAt',
'mbrdocUpdatedBy',
'mbrdocRemovedAt',
'mbrdocRemovedBy',
*/
trait MemberDocumentModelTrait
{
	public function primaryKeyValue() {
		return $this->mbrdocID;
	}

	public static function columnsInfo()
	{
		return [
			'mbrdocID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],
      'mbrdocUUID' => ModelColumnHelper::UUID(),
			'mbrdocMemberID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],
			'mbrdocDocumentID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],
			'mbrdocTitle' => [
				enuColumnInfo::type       => ['string', 'max' => 256],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
				enuColumnInfo::search     => 'like',
			],
			'mbrdocFileID' => [
				enuColumnInfo::type       => 'safe', //'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => false, //true
			],
			'mbrdocStatus' => [
				enuColumnInfo::isStatus   => true,
				enuColumnInfo::type       => ['string', 'max' => 1],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => enuMemberDocumentStatus::WaitForApprove,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
				enuColumnInfo::search     => true,
			],

			'mbrdocCreatedAt' => ModelColumnHelper::CreatedAt(),
      'mbrdocCreatedBy' => ModelColumnHelper::CreatedBy(),
      'mbrdocUpdatedAt' => ModelColumnHelper::UpdatedAt(),
      'mbrdocUpdatedBy' => ModelColumnHelper::UpdatedBy(),
			'mbrdocRemovedAt' => ModelColumnHelper::RemovedAt(),
			'mbrdocRemovedBy' => ModelColumnHelper::RemovedBy(),
		];
	}

	public function getCreatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mbrdocCreatedBy']);
	}

	public function getUpdatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mbrdocUpdatedBy']);
	}

	public function getRemovedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mbrdocRemovedBy']);
	}

	public function getMember() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\iranhmusic\shopack\mha\backend\models\MemberModel';
		else
			$className = '\iranhmusic\shopack\mha\frontend\common\models\MemberModel';

		return $this->hasOne($className, ['mbrUserID' => 'mbrdocMemberID']);
	}

	public function getDocument() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\iranhmusic\shopack\mha\backend\models\DocumentModel';
		else
			$className = '\iranhmusic\shopack\mha\frontend\common\models\DocumentModel';

		return $this->hasOne($className, ['docID' => 'mbrdocDocumentID']);
	}

	public function getFile() {
		$className = get_called_class();

    if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UploadFileModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UploadFileModel';

    return $this->hasOne($className, ['uflID' => 'mbrdocFileID']);
  }

}
