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
'mbrExpireDate',
'mbrMusicExperiences',
'mbrMusicExperienceStartAt', //Y/M/D
'mbrArtHistory',
'mbrMusicEducationHistory',

'mbrOwnOrgName',
'mbrInstrumentID',
'mbrSingID',
'mbrResearchID',
'mbrJob',
'mbrArtDegree',
'mbrHonarCreditCode',

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
			'mbrExpireDate' => [
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
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        // enuColumnInfo::search     => null,
			],
			'mbrMusicExperienceStartAt' => [
				enuColumnInfo::type       => 'safe', //Date
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        // enuColumnInfo::search     => null,
			],
			'mbrArtHistory' => [
				enuColumnInfo::type       => ['string', 'max' => 65000], //TEXT
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        // enuColumnInfo::search     => null,
			],
			'mbrMusicEducationHistory' => [
				enuColumnInfo::type       => ['string', 'max' => 65000], //TEXT
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        // enuColumnInfo::search     => null,
			],
			'mbrOwnOrgName' => [
				enuColumnInfo::type       => ['string', 'max' => 1024],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => 'like',
			],
			'mbrInstrumentID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],
			'mbrSingID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],
			'mbrResearchID' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],
			'mbrJob' => [
				enuColumnInfo::type       => ['string', 'max' => 512],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => 'like',
			],
			'mbrArtDegree' => [
				enuColumnInfo::type       => 'integer',
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => true,
			],
			'mbrHonarCreditCode' => [
				enuColumnInfo::type       => ['string', 'max' => 64],
				enuColumnInfo::validator  => null,
				enuColumnInfo::default    => null,
				enuColumnInfo::required   => false,
				enuColumnInfo::selectable => true,
        enuColumnInfo::search     => 'like',
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

	public function getInstrument() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\iranhmusic\shopack\mha\backend\models\BasicDefinitionModel';
		else
			$className = 'iranhmusic\shopack\mha\frontend\common\models\BasicDefinitionModel';

		return $this->hasOne($className, ['bdfID' => 'mbrInstrumentID']);
	}

	public function getSing() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\iranhmusic\shopack\mha\backend\models\BasicDefinitionModel';
		else
			$className = 'iranhmusic\shopack\mha\frontend\common\models\BasicDefinitionModel';

		return $this->hasOne($className, ['bdfID' => 'mbrSingID']);
	}

	public function getResearch() {
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\iranhmusic\shopack\mha\backend\models\BasicDefinitionModel';
		else
			$className = 'iranhmusic\shopack\mha\frontend\common\models\BasicDefinitionModel';

		return $this->hasOne($className, ['bdfID' => 'mbrResearchID']);
	}

}
