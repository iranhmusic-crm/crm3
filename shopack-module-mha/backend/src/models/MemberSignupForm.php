<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use Yii;
use yii\base\Model;
use yii\web\UnprocessableEntityHttpException;
use shopack\base\common\helpers\ArrayHelper;
use shopack\base\common\validators\GroupRequiredValidator;
use shopack\base\common\validators\JsonValidator;
use shopack\aaa\backend\models\UserModel;
use iranhmusic\shopack\mha\common\enums\enuMemberStatus;

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

			['mbrknnParams', 'safe'], //JsonValidator::class],

			[[
				'mbrUserID',
        'kanoonID',
      ], 'required'],

		];
	}

	private $_user = null;
	public function getUser() {
		if ($this->_user == null)
			$this->_user = UserModel::findOne($this->mbrUserID);
		return $this->_user;
	}

	public function signup()
	{
    if ($this->validate() == false)
      throw new UnprocessableEntityHttpException(implode("\n", $this->getFirstErrors()));

    //start transaction
		$transaction = Yii::$app->db->beginTransaction();

		try {
			//-- user
			$userFieldsCount = 0;

			if (array_key_exists('usrGender', $_POST)) {
				$userFieldsCount++;
				$this->user->usrGender = $this->usrGender;
			}
			if (array_key_exists('usrFirstName', $_POST)) {
				$userFieldsCount++;
				$this->user->usrFirstName = $this->usrFirstName;
			}
			if (array_key_exists('usrFirstName_en', $_POST)) {
				$userFieldsCount++;
				$this->user->usrFirstName_en = $this->usrFirstName_en;
			}
			if (array_key_exists('usrLastName', $_POST)) {
				$userFieldsCount++;
				$this->user->usrLastName = $this->usrLastName;
			}
			if (array_key_exists('usrLastName_en', $_POST)) {
				$userFieldsCount++;
				$this->user->usrLastName_en = $this->usrLastName_en;
			}
			if (array_key_exists('usrEmail', $_POST)) {
				$userFieldsCount++;
				$this->user->usrEmail = $this->usrEmail;
			}
			if (array_key_exists('usrMobile', $_POST)) {
				$userFieldsCount++;
				$this->user->usrMobile = $this->usrMobile;
			}
			if (array_key_exists('usrSSID', $_POST)) {
				$userFieldsCount++;
				$this->user->usrSSID = $this->usrSSID;
			}
			if (array_key_exists('usrBirthDate', $_POST)) {
				$userFieldsCount++;
				$this->user->usrBirthDate = $this->usrBirthDate;
			}
			if (array_key_exists('usrCountryID', $_POST)) {
				$userFieldsCount++;
				$this->user->usrCountryID = $this->usrCountryID;
			}
			if (array_key_exists('usrStateID', $_POST)) {
				$userFieldsCount++;
				$this->user->usrStateID = $this->usrStateID;
			}
			if (array_key_exists('usrCityOrVillageID', $_POST)) {
				$userFieldsCount++;
				$this->user->usrCityOrVillageID = $this->usrCityOrVillageID;
			}
			if (array_key_exists('usrTownID', $_POST)) {
				$userFieldsCount++;
				$this->user->usrTownID = $this->usrTownID;
			}
			if (array_key_exists('usrHomeAddress', $_POST)) {
				$userFieldsCount++;
				$this->user->usrHomeAddress = $this->usrHomeAddress;
			}
			if (array_key_exists('usrZipCode', $_POST)) {
				$userFieldsCount++;
				$this->user->usrZipCode = $this->usrZipCode;
			}
			if (array_key_exists('usrImageFileID', $_POST)) {
				$userFieldsCount++;
				$this->user->usrImageFileID = $this->usrImageFileID;
			}

			if ($userFieldsCount > 0) {
				if ($this->user->save() == false)  {
					throw new UnprocessableEntityHttpException("could not save user\n" . implode("\n", $this->user->getFirstErrors()));
				}
			}

			//-- member
			$memberModel = new MemberModel;

			$memberModel->mbrUserID = $this->mbrUserID;
			$memberModel->mbrMusicExperiences = $this->mbrMusicExperiences;
			$memberModel->mbrMusicExperienceStartAt = $this->mbrMusicExperienceStartAt;
			$memberModel->mbrArtHistory = $this->mbrArtHistory;
			$memberModel->mbrMusicEducationHistory = $this->mbrMusicEducationHistory;

			if ($memberModel->save() == false)  {
				throw new UnprocessableEntityHttpException("could not save member\n" . implode("\n", $memberModel->getFirstErrors()));
			}

			//-- member-kanoon
			$memberKanoonModel = new MemberKanoonModel;

			$memberKanoonModel->mbrknnMemberID = $memberModel->mbrUserID;
			$memberKanoonModel->mbrknnKanoonID = $this->kanoonID;
			if (empty($this->mbrknnParams) == false)
				$memberKanoonModel->mbrknnParams = json_decode($this->mbrknnParams, true);

			if ($memberKanoonModel->save() == false)  {
				throw new UnprocessableEntityHttpException("could not save member kanoon\n" . implode("\n", $memberKanoonModel->getFirstErrors()));
			}

      //commit
      $transaction->commit();

			return [
				'mbrknnID' => $memberKanoonModel->mbrknnID,
			];

    } catch (\Exception $e) {
      $transaction->rollBack();
      throw $e;
		} catch (\Throwable $e) {
			$transaction->rollBack();
      throw $e;
    }

	}

}
