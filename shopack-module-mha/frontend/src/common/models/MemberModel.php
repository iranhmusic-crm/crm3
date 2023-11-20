<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\models;

use Yii;
use shopack\base\common\helpers\Url;
use shopack\base\common\validators\GroupRequiredValidator;
use shopack\base\frontend\common\rest\RestClientActiveRecord;
use shopack\aaa\frontend\common\models\UserModel;
use iranhmusic\shopack\mha\common\enums\enuMemberStatus;

class MemberModel extends RestClientActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\MemberModelTrait;

	public static $resourceName = 'mha/member';

	public $mbrCreateNewUser = false;

	public $usrGender;
	public $usrFirstName;
	public $usrFirstName_en;
	public $usrLastName;
	public $usrLastName_en;
	public $usrEmail;
	public $usrMobile;
	public $usrSSID;

	public function extraRules() {
		return [
			['mbrCreateNewUser', 'boolean'],

			['mbrUserID',
				'required',
				'when' => function ($model) {
					return ($model->mbrCreateNewUser == false);
				},
				'whenClient' => "function (attribute, value) {
					return ($('#membermodel-mbrcreatenewuser')[0].checked == false);
				}"
			],

			[['usrEmail',
  			'usrMobile',
	  		'usrSSID',
				'usrGender',
				'usrFirstName',
				'usrFirstName_en',
				'usrLastName',
        'usrLastName_en',
			], 'string'],

      // [[
      //   'usrEmail',
      //   'usrMobile',
      // ], GroupRequiredValidator::class,
      //   'min' => 1,
      //   'in' => [
      //     'usrEmail',
      //     'usrMobile',
      //   ],
      //   'message' => Yii::t('aaa', 'one of email or mobile is required'),
			// 	'when' => function ($model) {
			// 		return ($model->mbrCreateNewUser);
			// 	},
			// 	'whenClient' => "function (attribute, value) {
			// 		return $('#membermodel-mbrcreatenewuser')[0].checked;
			// 	}"
      // ],

			[[
        'usrMobile',
        'usrSSID',
				'usrFirstName',
				'usrFirstName_en',
        'usrLastName',
        'usrLastName_en',
      ], 'required',
				'when' => function ($model) {
					return ($model->mbrCreateNewUser);
				},
				'whenClient' => "function (attribute, value) {
					return $('#membermodel-mbrcreatenewuser')[0].checked;
				}"
      ],

		];
	}

	public function attributeLabels()
	{
		return [
			'mbrCreateNewUser'          => Yii::t('mha', 'Create new user'),
			'mbrUserID'                 => Yii::t('mha', 'Related User'),
			'mbrRegisterCode'           => Yii::t('mha', 'Register Code'),
			'mbrAcceptedAt'							=> Yii::t('mha', 'Registration Accepted At'),
			'mbrExpireDate'							=> Yii::t('mha', 'Expire Date'),
			'mbrMusicExperiences'       => Yii::t('mha', 'Music Experiences'),
			'mbrMusicExperienceStartAt' => Yii::t('mha', 'Music Experience Start At'),
			'mbrArtHistory'             => Yii::t('mha', 'Art History'),
			'mbrMusicEducationHistory'  => Yii::t('mha', 'Music Education History'),

			'mbrOwnOrgName'             => Yii::t('mha', 'Own Org Name'),
			'mbrInstrumentID'           => Yii::t('mha', 'Instrument'),
			'mbrSingID'                 => Yii::t('mha', 'Sing'),
			'mbrResearchID'             => Yii::t('mha', 'Research'),
			'mbrJob'                    => Yii::t('mha', 'Job'),
			'mbrArtDegree'              => Yii::t('mha', 'Art Degree'),
			'mbrHonarCreditCode'        => Yii::t('mha', 'Honar Credit Code'),

			'mbrStatus'                 => Yii::t('mha', 'Member Status'),
			'mbrCreatedAt'              => Yii::t('app', 'Created At'),
			'mbrCreatedBy'              => Yii::t('app', 'Created By'),
			'mbrCreatedBy_User'         => Yii::t('app', 'Created By'),
			'mbrUpdatedAt'              => Yii::t('app', 'Updated At'),
			'mbrUpdatedBy'              => Yii::t('app', 'Updated By'),
			'mbrUpdatedBy_User'         => Yii::t('app', 'Updated By'),
			'mbrRemovedAt'              => Yii::t('app', 'Removed At'),
			'mbrRemovedBy'              => Yii::t('app', 'Removed By'),
			'mbrRemovedBy_User'         => Yii::t('app', 'Removed By'),

      'usrID'                 => Yii::t('app', 'ID'),
      'usrGender'             => Yii::t('aaa', 'Gender'),
      'usrFirstName'          => Yii::t('aaa', 'First Name'),
      'usrFirstName_en'       => Yii::t('aaa', 'First Name (en)'),
      'usrLastName'           => Yii::t('aaa', 'Last Name'),
      'usrLastName_en'        => Yii::t('aaa', 'Last Name (en)'),
      'usrFatherName'         => Yii::t('aaa', 'Father Name'),
      'usrFatherName_en'      => Yii::t('aaa', 'Father Name (en)'),
      'usrEmail'              => Yii::t('aaa', 'Email'),
      'usrEmailApprovedAt'    => Yii::t('aaa', 'Email Approved At'),
      'usrMobile'             => Yii::t('aaa', 'Mobile'),
      'usrMobileApprovedAt'   => Yii::t('aaa', 'Mobile Approved At'),
      'usrSSID'               => Yii::t('aaa', 'SSID'),
			'usrBirthCertID'      	=> Yii::t('aaa', 'Birth Cert ID'),
      'usrRoleID'             => Yii::t('aaa', 'Role'),
      'usrPrivs'              => Yii::t('aaa', 'Exclusive Privs'),
      'usrPassword'           => Yii::t('aaa', 'Password'),
      'usrRetypePassword'     => Yii::t('aaa', 'Retype Password'),
      'usrPasswordHash'       => Yii::t('aaa', 'Password Hash'),
      'usrPasswordCreatedAt'  => Yii::t('aaa', 'Password Created At'),
      'usrMustChangePassword' => Yii::t('aaa', 'Must Change Password'),
			'usrBirthDate'          => Yii::t('aaa', 'Birth Date'),
      'usrBirthCityID'      	=> Yii::t('aaa', 'Birth Location'),
			'usrCountryID'          => Yii::t('aaa', 'Country'),
			'usrStateID'            => Yii::t('aaa', 'State'),
			'usrCityOrVillageID'    => Yii::t('aaa', 'City Or Village'),
			'usrTownID'             => Yii::t('aaa', 'Town'),
			'usrHomeAddress'        => Yii::t('aaa', 'Home Address'),
			'usrZipCode'            => Yii::t('aaa', 'Zip Code'),
      'usrPhones'             => Yii::t('aaa', 'Phones'),
      'usrWorkAddress'        => Yii::t('aaa', 'Work Address'),
      'usrWorkPhones'         => Yii::t('aaa', 'Work Phones'),
      'usrWebsite'            => Yii::t('aaa', 'Website'),
			'usrImage'              => Yii::t('aaa', 'Image'),
			'usrImageFileID'        => Yii::t('aaa', 'Image'),

      'usrEducationLevel'     => Yii::t('aaa', 'Education Level'),
      'usrFieldOfStudy'       => Yii::t('aaa', 'Field Of Study'),
      'usrYearOfGraduation'   => Yii::t('aaa', 'Year Of Graduation'),
      'usrEducationPlace'     => Yii::t('aaa', 'Education Place'),
      'usrMaritalStatus'      => Yii::t('aaa', 'Marital Status'),
      'usrMilitaryStatus'     => Yii::t('aaa', 'Military Status'),
			'usrStatus'             => Yii::t('aaa', 'User Status'),

      'hasPassword'           => Yii::t('aaa', 'Has Password'),
		];
	}

	public function attributeHints()
	{
		return [
			'mbrOwnOrgName' => 'در صورت دارا بودن کسب و کار شخصی، عنوان آنرا در این بخش وارد کنید',
		];
	}

	public function isSoftDeleted()
  {
    return ($this->mbrStatus == enuMemberStatus::Removed);
  }

	public static function canCreate() {
		return true;
	}

	public function canUpdate() {
		return ($this->mbrStatus != enuMemberStatus::Removed);
	}

	public function canDelete() {
		return ($this->mbrStatus != enuMemberStatus::Removed);
	}

	public function canUndelete() {
		return ($this->mbrStatus == enuMemberStatus::Removed);
	}

	public function getUser() {
		return $this->hasOne(UserModel::class, ['usrID' => 'mbrUserID']);
	}

	public function load($data, $formName = null) {
		$ret = parent::load($data, $formName);

		//load relations
		try {
      $this->user->load($data);
		} catch (\Throwable $exp) {}

		return $ret;
	}

	public function save($runValidation = true, $attributeNames = null) {
		if ($this->isNewRecord) {
			if ($this->mbrCreateNewUser) {
				$userModel = new UserModel();

				$userModel->usrGender        = $this->usrGender;
				$userModel->usrFirstName     = $this->usrFirstName;
				$userModel->usrFirstName_en  = $this->usrFirstName_en;
				$userModel->usrLastName      = $this->usrLastName;
				$userModel->usrLastName_en   = $this->usrLastName_en;
				$userModel->usrSSID          = $this->usrSSID;
				$userModel->usrMobile        = $this->usrMobile;
				$userModel->usrEmail         = $this->usrEmail;

				$done = $userModel->save();
				if (!$done) {
					$this->addErrors($userModel->getErrors());
					return false;
				}
				$this->mbrCreateNewUser = false;
				$this->mbrUserID = $userModel->usrID;

				$this->usrGender        = null;
				$this->usrFirstName     = null;
				$this->usrFirstName_en  = null;
				$this->usrLastName      = null;
				$this->usrLastName_en   = null;
				$this->usrSSID          = null;
				$this->usrMobile        = null;
				$this->usrEmail         = null;

				// unset($this['user']);
				// // $this->populateRelation('user', null);
			}
		}

		try {
			return parent::save($runValidation, $attributeNames);
		} catch (\Throwable $exp) {
			$this->addError(null, $exp->getMessage());
		}

		return false;
	}

	public function displayName($format = null)
	{
		$result = '';

		if ($format == null)
			$format = '{fn} {ln} {em} {mob}';

		// if ($this->mbrRegisterCode)
			$result = '[عضویت: ' . ($this->mbrRegisterCode ?? 'ندارد') . '] ';

		return $result . $this->user->displayName($format);
	}

	public function getDefects()
	{
		$defects = [];

		//-- email
		if (empty($this->user->usrEmailApprovedAt)) {
			$defects[] = [
				'label' => Yii::t('aaa', 'Email'),
				'desc' => Yii::t('app', 'Not approved'),
				'url' => Url::to(['/aaa/profile/index', 'fragment' => 'login']),
			];
		}

		//-- mobile
		if (empty($this->user->usrMobileApprovedAt)) {
			$defects[] = [
				'label' => Yii::t('aaa', 'Mobile'),
				'desc' => Yii::t('app', 'Not approved'),
				'url' => Url::to(['/aaa/profile/index', 'fragment' => 'login']),
			];
		}

		//-- image
		if ($this->user->usrImageFileID == null) {
			$defects[] = [
				'label' => Yii::t('aaa', 'Image'),
				'desc' => Yii::t('app', 'Not defined'),
				// 'url' => Url::to(['/aaa/profile/update-image']),
			];
		}

		//-- specialty
		$memberSpecialtyCount = MemberSpecialtyModel::find()
			->andWhere(['mbrspcMemberID' => $this->mbrUserID])->count();

		if (empty($memberSpecialtyCount))
			$defects[] = [
				'label' => Yii::t('mha', 'Specialty'),
				'desc' => Yii::t('app', 'Not defined'),
				'url' => Url::to(['/mha/member-specialty/index']),
			];

		//-- doc
		$doctypesSearchModel = new DocumentSearchModel();
		$doctypesDataProvider = $doctypesSearchModel->getDocumentTypesForMember($this->mbrUserID);
		$docModels = $doctypesDataProvider->getModels();
		if (empty($docModels) == false) {
			$docDefects = [];
			foreach ($docModels as $docModel) {
				if ($docModel->providedCount == 0) {
					$docDefects[] = $docModel->docName;
				}
			}
			if (empty($docDefects) == false) {
				$defects[] = [
					'label' => Yii::t('mha', 'Documents'),
					'desc' => $docDefects,
					'url' => Url::to(['/mha/member-document/index']),
				];
			}
		}

		//--
		return $defects;
	}

}
