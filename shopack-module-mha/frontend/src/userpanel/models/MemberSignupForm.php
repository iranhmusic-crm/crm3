<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\userpanel\models;

use Yii;
use yii\base\Model;
use shopack\base\common\helpers\Json;
use shopack\base\common\helpers\ArrayHelper;
use shopack\base\common\validators\GroupRequiredValidator;
use shopack\base\common\validators\JsonValidator;
use shopack\aaa\frontend\common\models\UserModel;
use iranhmusic\shopack\mha\common\enums\enuMemberStatus;
use shopack\base\common\helpers\HttpHelper;

class MemberSignupForm extends Model
{
	public $usrGender;
	public $usrFirstName;
	public $usrFirstName_en;
	public $usrLastName;
	public $usrLastName_en;
	public $usrEmail;
	public $usrMobile;
	public $usrSSID;
	public $usrBirthDate;
	public $usrCountryID;
	public $usrStateID;
	public $usrCityOrVillageID;
	// public $usrTownID;
	public $usrHomeAddress;
	public $usrZipCode;
	// public $usrImageFileID;

	public $mbrUserID;
	public $mbrMusicExperiences;
	public $mbrMusicExperienceStartAt;
	public $mbrArtHistory;
	public $mbrMusicEducationHistory;

	public $kanoonID;
	public $mbrknnParams;

	public function rules()
	{
		return [
			[[
				'usrGender',
				'usrFirstName',
				'usrFirstName_en',
				'usrLastName',
				'usrLastName_en',
				'usrEmail',
				'usrMobile',
				'usrSSID',
				'usrBirthDate',
				'usrCountryID',
				'usrStateID',
				'usrCityOrVillageID',
				// 'usrTownID',
				'usrHomeAddress',
				'usrZipCode',
			], 'safe'],

			['usrGender',
				'required',
				'when' => function ($model) {
					return (empty($model->user->usrGender));
				},
			],
			['usrFirstName',
				'required',
				'when' => function ($model) {
					return (empty($model->user->usrFirstName));
				},
			],
			['usrFirstName_en',
				'required',
				'when' => function ($model) {
					return (empty($model->user->usrFirstName_en));
				},
			],
			['usrLastName',
				'required',
				'when' => function ($model) {
					return (empty($model->user->usrLastName));
				},
			],
			['usrLastName_en',
				'required',
				'when' => function ($model) {
					return (empty($model->user->usrLastName_en));
				},
			],
			['usrEmail',
				'required',
				'when' => function ($model) {
					return (empty($model->user->usrEmail));
				},
			],
			['usrMobile',
				'required',
				'when' => function ($model) {
					return (empty($model->user->usrMobile));
				},
			],
			['usrSSID',
				'required',
				'when' => function ($model) {
					return (empty($model->user->usrSSID));
				},
			],
			['usrBirthDate',
				'required',
				'when' => function ($model) {
					return (empty($model->user->usrBirthDate));
				},
			],
			['usrCountryID',
				'required',
				'when' => function ($model) {
					return (empty($model->user->usrCountryID));
				},
			],
			['usrStateID',
				'required',
				'when' => function ($model) {
					return (empty($model->user->usrStateID));
				},
			],
			['usrCityOrVillageID',
				'required',
				'when' => function ($model) {
					return (empty($model->user->usrCityOrVillageID));
				},
			],
			['usrHomeAddress',
				'required',
				'when' => function ($model) {
					return (empty($model->user->usrHomeAddress));
				},
			],
			['usrZipCode',
				'required',
				'when' => function ($model) {
					return (empty($model->user->usrZipCode));
				},
			],

			[[
				'mbrUserID',
				'kanoonID',
			], 'integer'],

			['mbrMusicExperiences', 'string'],
			['mbrMusicExperienceStartAt', 'safe'],
			['mbrArtHistory', 'string'],
			['mbrMusicEducationHistory', 'string'],

			['mbrknnParams', JsonValidator::class],

			[[
				'mbrUserID',
        'kanoonID',
      ], 'required'],

		];
	}

	public function attributeLabels()
	{
		return [
      'usrGender'            => Yii::t('aaa', 'Gender'),
      'usrFirstName'         => Yii::t('aaa', 'First Name'),
      'usrFirstName_en'      => Yii::t('aaa', 'First Name (en)'),
      'usrLastName'          => Yii::t('aaa', 'Last Name'),
      'usrLastName_en'       => Yii::t('aaa', 'Last Name (en)'),
      'usrEmail'             => Yii::t('aaa', 'Email'),
      'usrEmailApprovedAt'   => Yii::t('aaa', 'Email Approved At'),
      'usrMobile'            => Yii::t('aaa', 'Mobile'),
      'usrMobileApprovedAt'  => Yii::t('aaa', 'Mobile Approved At'),
      'usrSSID'              => Yii::t('aaa', 'SSID'),
      'usrRoleID'            => Yii::t('aaa', 'Role'),
      'usrPrivs'             => Yii::t('aaa', 'Exclusive Privs'),
      'usrPassword'          => Yii::t('aaa', 'Password'),
      'usrRetypePassword'    => Yii::t('aaa', 'Retype Password'),
      'usrPasswordHash'      => Yii::t('aaa', 'Password Hash'),
      'usrPasswordCreatedAt' => Yii::t('aaa', 'Password Created At'),
			'usrBirthDate'         => Yii::t('aaa', 'Birth Date'),
			'usrCountryID'         => Yii::t('aaa', 'Country'),
			'usrStateID'           => Yii::t('aaa', 'State'),
			'usrCityOrVillageID'   => Yii::t('aaa', 'City Or Village'),
			// 'usrTownID'            => Yii::t('aaa', 'Town'),
			'usrHomeAddress'       => Yii::t('aaa', 'Home Address'),
			'usrZipCode'           => Yii::t('aaa', 'Zip Code'),
			'usrImage'             => Yii::t('aaa', 'Image'),
      'usrStatus'            => Yii::t('app', 'Status'),

			'kanoonID'          				=> Yii::t('mha', 'Kanoon'),
			'mbrUserID'       					=> Yii::t('mha', 'User'),
			'mbrMusicExperiences'       => Yii::t('mha', 'Music Experiences'),
			'mbrMusicExperienceStartAt' => Yii::t('mha', 'Music Experience Start At'),
			'mbrArtHistory'             => Yii::t('mha', 'Art History'),
			'mbrMusicEducationHistory'  => Yii::t('mha', 'Music Education History'),
		];
	}

	private $_user = null;
	public function getUser() {
		if ($this->_user == null)
			$this->_user = UserModel::findOne($this->mbrUserID);
		return $this->_user;
	}

  public function load($data, $formName = null)
  {
		$this->usrGender = $this->user->usrGender;
		$this->usrFirstName = $this->user->usrFirstName;
		$this->usrFirstName_en = $this->user->usrFirstName_en;
		$this->usrLastName = $this->user->usrLastName;
		$this->usrLastName_en = $this->user->usrLastName_en;
		$this->usrEmail = $this->user->usrEmail;
		$this->usrMobile = $this->user->usrMobile;
		$this->usrSSID = $this->user->usrSSID;
		$this->usrBirthDate = $this->user->usrBirthDate;
		$this->usrCountryID = $this->user->usrCountryID;
		$this->usrStateID = $this->user->usrStateID;
		$this->usrCityOrVillageID = $this->user->usrCityOrVillageID;
		// $this->usrTownID = $this->user->usrTownID;
		$this->usrHomeAddress = $this->user->usrHomeAddress;
		$this->usrZipCode = $this->user->usrZipCode;
		// $this->usrImageFileID = $this->user->usrImageFileID;

		$this->_oldAttributes = $this->attributes;

		$ret = parent::load($data, $formName);
		return $ret;
	}

	public function mustSetUserInfo()
	{
		return (
			empty($this->user->usrGender)
			|| empty($this->user->usrFirstName)
			|| empty($this->user->usrFirstName_en)
			|| empty($this->user->usrLastName)
			|| empty($this->user->usrLastName_en)
			|| empty($this->user->usrEmail)
			|| empty($this->user->usrMobile)
			|| empty($this->user->usrSSID)
			|| empty($this->user->usrBirthDate)
			|| empty($this->user->usrCountryID)
			|| empty($this->user->usrStateID)
			|| empty($this->user->usrCityOrVillageID)
			// || empty($this->user->usrTownID)
			|| empty($this->user->usrHomeAddress)
			|| empty($this->user->usrZipCode)
		);
	}

	private $_oldAttributes;
	public function getOldAttributes()
	{
		return $this->_oldAttributes === null ? [] : $this->_oldAttributes;
	}
	private function isAttributeDirty($attribute, $value)
	{
		$old_attribute = $this->oldAttributes[$attribute];
		if (is_array($value) && is_array($this->oldAttributes[$attribute])) {
			$value = ArrayHelper::recursiveSort($value);
			$old_attribute = ArrayHelper::recursiveSort($old_attribute);
		}

		return $value !== $old_attribute;
	}
	public function getDirtyAttributes($names = null)
	{
		if ($names === null) {
			$names = $this->attributes();
		}
		$names = array_flip($names);
		$attributes = [];
		if ($this->_oldAttributes === null) {
			foreach ($this->attributes as $name => $value) {
				if (isset($names[$name])) {
					$attributes[$name] = $value;
				}
			}
		} else {
			foreach ($this->attributes as $name => $value) {
				if (isset($names[$name]) && (!array_key_exists($name, $this->_oldAttributes) || $this->isAttributeDirty($name, $value))) {
					$attributes[$name] = $value;
				}
			}
		}

		return $attributes;
	}

	public function save()
	{
		if ($this->validate() == false)
			return false;

		$attributes = $this->getDirtyAttributes();

		if (isset($attributes['mbrknnParams'])
			&& ($attributes['mbrknnParams'] !== null)
			&& ($attributes['mbrknnParams'] !== '')
		) {
			$attributes['mbrknnParams'] = Json::encode($attributes['mbrknnParams']);
		}

		list ($resultStatus, $resultData) = HttpHelper::callApi('mha/member/signup',
			HttpHelper::METHOD_POST,
			[],
			$attributes,
		);

		if ($resultStatus < 200 || $resultStatus >= 300) {
			$this->addError(null, Yii::t('mha', $resultData['message'], $resultData));
			return false;
			// throw new \yii\web\HttpException($resultStatus, Yii::t('mha', $resultData['message'], $resultData));
		}

		return true;
	}

}
