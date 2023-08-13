<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\models;

use shopack\base\common\rest\ModelColumnHelper;
use shopack\base\common\rest\enuColumnInfo;
// use shopack\base\common\validators\JsonValidator;
use iranhmusic\shopack\mha\common\enums\enuMemberStatus;

/*
'mbrUserID',  // 1 <-> 1
'mbrUUID',
'mbrRegisterCode',
'mbrAcceptedAt',
'mbrMusicExperiences',
'mbrMusicExperienceStartAt', //Y/M/D
'mbrArtHistory',
'mbrMusicEducationHistory',
'mbrStatus',
'mbrCreatedAt',
'mbrCreatedBy',
'mbrUpdatedAt',
'mbrUpdatedBy',
'mbrRemovedAt',
'mbrRemovedBy',
*/
trait MemberModelTrait
{
	public function primaryKeyValue() {
		return $this->mbrUserID;
	}

	public static function columnsInfo()
	{
		return [
			'mbrUserID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false, //true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],
      'mbrUUID' => ModelColumnHelper::UUID(),
			'mbrRegisterCode' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],
			'mbrAcceptedAt' => [
				enuColumnInfo::type       => 'safe',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],
			'mbrMusicExperiences' => [
				enuColumnInfo::type       => ['string', 'max' => 65000], //TEXT
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => null,
				enuColumnInfo::selectable => true,
        // enuColumnInfo::search     => null,
			],
			'mbrMusicExperienceStartAt' => [
				enuColumnInfo::type       => 'safe', //Date
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => null,
				enuColumnInfo::selectable => true,
        // enuColumnInfo::search     => null,
			],
			'mbrArtHistory' => [
				enuColumnInfo::type       => ['string', 'max' => 65000], //TEXT
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => null,
				enuColumnInfo::selectable => true,
        // enuColumnInfo::search     => null,
			],
			'mbrMusicEducationHistory' => [
				enuColumnInfo::type       => ['string', 'max' => 65000], //TEXT
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => null,
				enuColumnInfo::selectable => true,
        // enuColumnInfo::search     => null,
			],
			'mbrStatus' => [
				enuColumnInfo::isStatus   => true,
				enuColumnInfo::type       => ['string', 'max' => 1],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => enuMemberStatus::WaitingForApproval,
				enuColumnInfo::required   => true,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],

			'mbrCreatedAt' => ModelColumnHelper::CreatedAt(),
      'mbrCreatedBy' => ModelColumnHelper::CreatedBy(),
      'mbrUpdatedAt' => ModelColumnHelper::UpdatedAt(),
      'mbrUpdatedBy' => ModelColumnHelper::UpdatedBy(),
			'mbrRemovedAt' => ModelColumnHelper::RemovedAt(),
			'mbrRemovedBy' => ModelColumnHelper::RemovedBy(),
		];
	}

	public function getCreatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mbrCreatedBy']);
	}

	public function getUpdatedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mbrUpdatedBy']);
	}

	public function getRemovedByUser() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\shopack\aaa\backend\models\UserModel';
		else
			$className = '\shopack\aaa\frontend\common\models\UserModel';

		return $this->hasOne($className, ['usrID' => 'mbrRemovedBy']);
	}

}
