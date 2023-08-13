<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\models;

use shopack\base\common\rest\ModelColumnHelper;
use shopack\base\common\rest\enuColumnInfo;
use shopack\base\common\validators\JsonValidator;
use iranhmusic\shopack\mha\common\enums\enuKanoonMembershipDegree;
use iranhmusic\shopack\mha\common\enums\enuMemberKanoonStatus;

/*
'mbrknnID',
'mbrknnUUID',
'mbrknnMemberID',
'mbrknnKanoonID',
'mbrknnParams',
'mbrknnIsMaster',
'mbrknnMembershipDegree',
'mbrknnComment',
'mbrknnHistory',
'mbrknnStatus',
'mbrknnCreatedAt',
'mbrknnCreatedBy',
'mbrknnUpdatedAt',
'mbrknnUpdatedBy',
'mbrknnRemovedAt',
'mbrknnRemovedBy',
*/
trait MemberKanoonModelTrait
{
	// public $mbrRegisterCode = null;

	public function primaryKeyValue() {
		return $this->mbrknnID;
	}

	public static function columnsInfo()
	{
		return [
      'mbrRegisterCode' => [
        enuColumnInfo::type       => 'string',
        enuColumnInfo::validator  => null,
        enuColumnInfo::default    => null,
        enuColumnInfo::required   => false,
        enuColumnInfo::selectable => false,
        enuColumnInfo::virtual    => true,
      ],

			'mbrknnID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],
      'mbrknnUUID' => ModelColumnHelper::UUID(),
			'mbrknnMemberID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],
			'mbrknnKanoonID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],
			'mbrknnParams' => [
				enuColumnInfo::type       => JsonValidator::class,
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        // enuColumnInfo::search     => null,
			],
			'mbrknnIsMaster' => [
				enuColumnInfo::type       => 'boolean',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => 1,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
				enuColumnInfo::search     => true,
			],
			//enuKanoonMembershipDegree
			'mbrknnMembershipDegree' => [
				enuColumnInfo::type       => ['string', 'max' => 1],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],
			'mbrknnComment' => [
				enuColumnInfo::type       => ['string', 'max' => 65500],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],
			'mbrknnHistory' => [
				enuColumnInfo::type       => JsonValidator::class,
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        // enuColumnInfo::search     => null,
			],
			'mbrknnStatus' => [
				enuColumnInfo::isStatus   => true,
				enuColumnInfo::type       => ['string', 'max' => 1],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => enuMemberKanoonStatus::WaitForSend,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
				enuColumnInfo::search     => true,
			],

			'mbrknnCreatedAt' => ModelColumnHelper::CreatedAt(),
      'mbrknnCreatedBy' => ModelColumnHelper::CreatedBy(),
      'mbrknnUpdatedAt' => ModelColumnHelper::UpdatedAt(),
      'mbrknnUpdatedBy' => ModelColumnHelper::UpdatedBy(),
			'mbrknnRemovedAt' => ModelColumnHelper::RemovedAt(),
			'mbrknnRemovedBy' => ModelColumnHelper::RemovedBy(),
		];
	}

	public function getCreatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mbrknnCreatedBy']);
	}

	public function getUpdatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mbrknnUpdatedBy']);
	}

	public function getRemovedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mbrknnRemovedBy']);
	}

	public function getMember() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\iranhmusic\shopack\mha\backend\models\MemberModel';
		else
			$className = '\iranhmusic\shopack\mha\frontend\common\models\MemberModel';

		return $this->hasOne($className, ['mbrUserID' => 'mbrknnMemberID']);
	}

	public function getKanoon() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\iranhmusic\shopack\mha\backend\models\KanoonModel';
		else
			$className = '\iranhmusic\shopack\mha\frontend\common\models\KanoonModel';

		return $this->hasOne($className, ['knnID' => 'mbrknnKanoonID']);
	}

}
