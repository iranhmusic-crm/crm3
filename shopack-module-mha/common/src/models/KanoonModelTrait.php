<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\models;

use shopack\base\common\rest\ModelColumnHelper;
use shopack\base\common\rest\enuColumnInfo;
use shopack\base\common\rest\enuColumnSearchType;
// use shopack\base\common\validators\JsonValidator;
use iranhmusic\shopack\mha\common\enums\enuKanoonStatus;

/*
'knnID',
'knnUUID',
'knnName',
'knnNameEn',
'knnDescFieldType',
'knnDescFieldLabel',
'knnPresidentMemberID',
'knnVicePresidentMemberID',
'knnOzv1MemberID',
'knnOzv2MemberID',
'knnOzv3MemberID',
'knnWardenMemberID',
'knnTalkerMemberID',
'knnStatus',
'knnCreatedAt',
'knnCreatedBy',
'knnUpdatedAt',
'knnUpdatedBy',
'knnRemovedAt',
'knnRemovedBy',
*/
trait KanoonModelTrait
{
	public static $primaryKey = ['knnID'];

	public function primaryKeyValue() {
		return $this->knnID;
	}

	public function columnsInfo()
	{
		return [
			'knnID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
      'knnUUID' => ModelColumnHelper::UUID(),
			'knnName' => [
				enuColumnInfo::type       => ['string', 'max' => 128],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::like,
			],
			'knnNameEn' => [
				enuColumnInfo::type       => ['string', 'max' => 128],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::like,
			],
			'knnDescFieldType' => [
				enuColumnInfo::type       => ['string', 'max' => 64],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
				enuColumnInfo::search     => enuColumnSearchType::exact,
			],
			'knnDescFieldLabel' => [
				enuColumnInfo::type       => ['string', 'max' => 64],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
				enuColumnInfo::search     => enuColumnSearchType::exact,
			],
			'knnPresidentMemberID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
			'knnVicePresidentMemberID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
			'knnOzv1MemberID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
			'knnOzv2MemberID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
			'knnOzv3MemberID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
			'knnWardenMemberID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
			'knnTalkerMemberID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],
			'knnStatus' => [
				enuColumnInfo::isStatus   => true,
				enuColumnInfo::type       => ['string', 'max' => 1],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => enuKanoonStatus::Active,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => enuColumnSearchType::exact,
			],

			'knnCreatedAt' => ModelColumnHelper::CreatedAt(),
      'knnCreatedBy' => ModelColumnHelper::CreatedBy(),
      'knnUpdatedAt' => ModelColumnHelper::UpdatedAt(),
      'knnUpdatedBy' => ModelColumnHelper::UpdatedBy(),
			'knnRemovedAt' => ModelColumnHelper::RemovedAt(),
			'knnRemovedBy' => ModelColumnHelper::RemovedBy(),
		];
	}

	public function getCreatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'knnCreatedBy']);
	}

	public function getUpdatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'knnUpdatedBy']);
	}

	public function getRemovedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'knnRemovedBy']);
	}

	public function getPresident() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\iranhmusic\shopack\mha\backend\models\MemberModel';
		else
			$className = '\iranhmusic\shopack\mha\frontend\common\models\MemberModel';

		return $this->hasOne($className, ['mbrUserID' => 'knnPresidentMemberID']);
	}
	public function getVicePresident() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\iranhmusic\shopack\mha\backend\models\MemberModel';
		else
			$className = '\iranhmusic\shopack\mha\frontend\common\models\MemberModel';

		return $this->hasOne($className, ['mbrUserID' => 'knnVicePresidentMemberID']);
	}
	public function getOzv1() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\iranhmusic\shopack\mha\backend\models\MemberModel';
		else
			$className = '\iranhmusic\shopack\mha\frontend\common\models\MemberModel';

		return $this->hasOne($className, ['mbrUserID' => 'knnOzv1MemberID']);
	}
	public function getOzv2() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\iranhmusic\shopack\mha\backend\models\MemberModel';
		else
			$className = '\iranhmusic\shopack\mha\frontend\common\models\MemberModel';

		return $this->hasOne($className, ['mbrUserID' => 'knnOzv2MemberID']);
	}
	public function getOzv3() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\iranhmusic\shopack\mha\backend\models\MemberModel';
		else
			$className = '\iranhmusic\shopack\mha\frontend\common\models\MemberModel';

		return $this->hasOne($className, ['mbrUserID' => 'knnOzv3MemberID']);
	}
	public function getWarden() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\iranhmusic\shopack\mha\backend\models\MemberModel';
		else
			$className = '\iranhmusic\shopack\mha\frontend\common\models\MemberModel';

		return $this->hasOne($className, ['mbrUserID' => 'knnWardenMemberID']);
	}
	public function getTalker() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\iranhmusic\shopack\mha\backend\models\MemberModel';
		else
			$className = '\iranhmusic\shopack\mha\frontend\common\models\MemberModel';

		return $this->hasOne($className, ['mbrUserID' => 'knnTalkerMemberID']);
	}

}
