<?php

/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\models;

use Yii;
use yii\data\ArrayDataProvider;
use shopack\base\common\helpers\ArrayHelper;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\frontend\common\rest\RestClientActiveRecord;
use shopack\aaa\common\enums\enuGender;
use shopack\aaa\common\enums\enuUserStatus;
use iranhmusic\shopack\mha\common\enums\enuReportStatus;
use iranhmusic\shopack\mha\common\enums\enuKanoonMembershipDegree;

class ReportModel extends RestClientActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\ReportModelTrait;

	public static $resourceName = 'mha/report';

	public function attributeLabels()
	{
		return [
			'rptID'                   => Yii::t('app', 'ID'),
			'rptName'                 => Yii::t('mha', 'Report Title'),
			'rptType'                	=> Yii::t('app', 'Type'),
			'rptInputFields'          => Yii::t('app', 'Input Fields'),
			'rptOutputFields'         => Yii::t('app', 'Output Fields'),
			'rptStatus'               => Yii::t('app', 'Status'),
			'rptCreatedAt'            => Yii::t('app', 'Created At'),
			'rptCreatedBy'            => Yii::t('app', 'Created By'),
			'rptCreatedBy_User'       => Yii::t('app', 'Created By'),
			'rptUpdatedAt'            => Yii::t('app', 'Updated At'),
			'rptUpdatedBy'            => Yii::t('app', 'Updated By'),
			'rptUpdatedBy_User'       => Yii::t('app', 'Updated By'),
			'rptRemovedAt'            => Yii::t('app', 'Removed At'),
			'rptRemovedBy'            => Yii::t('app', 'Removed By'),
			'rptRemovedBy_User'       => Yii::t('app', 'Removed By'),
		];
	}

	public function outputFields()
	{
		//by appearance order
		return [
			'mbrUserID' => [
				'label' => Yii::t('aaa', 'User ID'),
				'format' => 'raw',
				'value' => function ($model, $key, $index, $widget) {
					return Html::a($model['mbrUserID'], ['/mha/member/view', 'id' => $model['mbrUserID']]);
				},
				'export' => function ($model) {
					return $model['mbrUserID'];
				},
			],

			//user
			// 'user.usrID'									=> Yii::t('aaa', 'User ID'),
			'user.usrImageFileID'         => [
				'label' => Yii::t('aaa', 'Image'),
				'value' => function ($model, $key, $index, $widget) {
					return Html::asUploadedImage($model['user']['imageFile'] ?? null, '50px', false);
				},
				'export' => false,
			],
			'user.usrGender' => [
				'label' => Yii::t('aaa', 'Gender'),
				'value' => function ($model, $key, $index, $widget) {
					return enuGender::getLabel($model['user']['usrGender'] ?? null);
				},
				'checked' => true,
			],
			'user.usrFirstName'           => ['label' => Yii::t('aaa', 'First Name'), 'checked' => true,],
			'user.usrFirstName_en'        => ['label' => Yii::t('aaa', 'First Name (en)'),],
			'user.usrLastName'            => ['label' => Yii::t('aaa', 'Last Name'), 'checked' => true,],
			'user.usrLastName_en'         => ['label' => Yii::t('aaa', 'Last Name (en)'),],
			'user.usrFatherName'          => ['label' => Yii::t('aaa', 'Father Name'),],
			'user.usrFatherName_en'       => ['label' => Yii::t('aaa', 'Father Name (en)'),],
			'user.usrEmail'               => ['label' => Yii::t('aaa', 'Email'),],
			'user.usrEmailApprovedAt'     => [
				'label' => Yii::t('aaa', 'Email Approved At'),
				'format' => 'jalaliWithTime',
				'export' => function ($model) {
					return Yii::$app->formatter->asJalaliWithTime($model['user']['usrEmailApprovedAt'] ?? null);
				},
			],
			'user.usrMobile'              => [
				'label' => Yii::t('aaa', 'Mobile'),
				'format' => 'phone',
			],
			'user.usrMobileApprovedAt'    => [
				'label' => Yii::t('aaa', 'Mobile Approved At'),
				'format' => 'jalaliWithTime',
				'export' => function ($model) {
					return Yii::$app->formatter->asJalaliWithTime($model['user']['usrMobileApprovedAt'] ?? null);
				},
			],
			'user.usrSSID'                => Yii::t('aaa', 'SSID'),
			'user.hasPassword'            => [
				'label' => Yii::t('aaa', 'Has Password'),
				'value' => function ($model, $key, $index, $widget) {
					return ($model['user']['hasPassword'] ?? false ? 'بلی' : 'خیر');
				},
			],
			'user.usrPasswordCreatedAt'   => [
				'label' => Yii::t('aaa', 'Password Created At'),
				'format' => 'jalaliWithTime',
				'export' => function ($model) {
					return Yii::$app->formatter->asJalaliWithTime($model['user']['usrPasswordCreatedAt'] ?? null);
				},
			],
			// 'usrMustChangePassword'  => Yii::t('aaa', 'Must Change Password'),
			'user.usrBirthDate'           => [
				'label' => Yii::t('aaa', 'Birth Date'),
				'format' => 'jalali',
				'export' => function ($model) {
					return Yii::$app->formatter->asJalali($model['user']['usrBirthDate'] ?? null);
				},
				'checked' => true,
			],
			'user.usrBirthCityID'         => [
				'label' => Yii::t('aaa', 'Birth Location'),
				'value' => function ($model, $key, $index, $widget) {
					return ($model['user']['birthCityOrVillage']['ctvName'] ?? null);
				},
				'checked' => true,
			],
			'user.usrDeadAt'   => [
				'label' => Yii::t('aaa', 'Dead At'),
				'format' => 'jalali',
				'export' => function ($model) {
					return Yii::$app->formatter->asJalali($model['user']['usrDeadAt'] ?? null);
				},
			],
			'user.usrCountryID'           => [
				'label' => Yii::t('aaa', 'Country'),
				'value' => function ($model, $key, $index, $widget) {
					return ($model['user']['country']['cntrName'] ?? null);
				},
			],
			'user.usrStateID'             => [
				'label' => Yii::t('aaa', 'State'),
				'value' => function ($model, $key, $index, $widget) {
					return ($model['user']['state']['sttName'] ?? null);
				},
			],
			'user.usrCityOrVillageID'     => [
				'label' => Yii::t('aaa', 'City Or Village'),
				'value' => function ($model, $key, $index, $widget) {
					return ($model['user']['cityOrVillage']['ctvName'] ?? null);
				},
			],
			'user.usrTownID'              => [
				'label' => Yii::t('aaa', 'Town'),
				'value' => function ($model, $key, $index, $widget) {
					return ($model['user']['town']['twnName'] ?? null);
				},
			],
			'user.usrZipCode'             => Yii::t('aaa', 'Zip Code'),
			'user.usrHomeAddress'         => Yii::t('aaa', 'Home Address'),

			'mbrJob' => Yii::t('mha', 'Job'),

			// 'birthCityOrVillage.ctvName'       => 'شهر تولد',
			// 'birthCityOrVillage.state.sttName' => 'استان تولد',
			// 'cityOrVillage.ctvName'            => 'شهر سکونت',
			// 'state.sttName'    				  => 'استان سکونت',

			'user.usrStatus'              => [
				'label' => Yii::t('app', 'Status'),
				'value' => function ($model, $key, $index, $widget) {
					return enuUserStatus::getLabel($model['user']['usrStatus'] ?? null);
				},
				'export' => function ($model) {
					return enuUserStatus::getLabel($model['user']['usrStatus'] ?? null);
				},
			],

			//member
			'mbrRegisterCode' => [
				'label' => Yii::t('mha', 'Register Code'),
				'checked' => true,
			],
			'mbrAcceptedAt' => [
				'label' => Yii::t('mha', 'Registration Accepted At'),
				'format' => 'jalaliWithTime',
				'export' => function ($model) {
					return Yii::$app->formatter->asJalaliWithTime($model['mbrAcceptedAt'] ?? null);
				},
				'checked' => true,
			],
			'mbrExpireDate' => [
				'label' => Yii::t('mha', 'Expire Date'),
				'format' => 'jalali',
				'export' => function ($model) {
					return Yii::$app->formatter->asJalali($model['mbrExpireDate'] ?? null);
				},
				'checked' => true,
			],

			// 'knnName' => [
			'kanoonNames' => [
				'label' => Yii::t('mha', 'Kanoon'),
				'value' => function ($model, $key, $index, $widget) {
					$value = $model['kanoonNames'] ?? null;
					if (empty($value))
						return null;
					$value = explode('|', $value);
					return implode(' - ', $value);
				},
				'checked' => true,
			],

			// 'mbrknnMembershipDegree' => [
			'kanoonDegrees' => [
				'label' => Yii::t('mha', 'Membership Degree'),
				'value' => function ($model, $key, $index, $widget) {
					$value = $model['kanoonDegrees'] ?? null;
					if (empty($value))
						return null;
					$value = explode('|', $value);
					$result = [];
					foreach ($value as $v) {
						$result[] = enuKanoonMembershipDegree::getLabel($v);
					}
					return implode(' - ', $result);
				},
				'checked' => true,
			],

			'mbrInstrumentID' => [
				'label' => Yii::t('mha', 'Instrument'),
				'value' => function ($model, $key, $index, $widget) {
					return $model['instrument']['bdfName'] ?? null;
				},
			],
			'mbrSingID' => [
				'label' => Yii::t('mha', 'Sing'),
				'value' => function ($model, $key, $index, $widget) {
					return $model['sing']['bdfName'] ?? null;
				},
			],
			'mbrResearchID' => [
				'label' => Yii::t('mha', 'Research'),
				'value' => function ($model, $key, $index, $widget) {
					return $model['research']['bdfName'] ?? null;
				},
			],

			// 'mbrknnParams'           => [
			//   'label' => 'تخصص',
			//   'value' => function($model) {
			//     if (empty($model['knnID'])
			//       || empty($model['mbrknnParams'])
			//       || empty($model['knnDescFieldType'])
			//     )
			//       return null;

			//     $mbrknnParams = Json::decode($model['mbrknnParams'], true);
			//     $desc = $mbrknnParams['desc'];
			//     $fieldType = $model['knnDescFieldType'];
			//     if ($fieldType == 'text')
			//       return $desc;

			//     if (str_starts_with($fieldType, 'mha:')) {
			//       $bdf = substr($fieldType, 4);

			//       $basicDefinitionModel = BasicDefinitionModel::find()
			//         ->andWhere(['bdfID' => $desc])
			//         // ->andWhere(['bdfType' => $bdf])
			//         ->one()
			//       ;

			//       if ($basicDefinitionModel)
			//         return enuBasicDefinitionType::getLabel($bdf) . ': ' . $basicDefinitionModel->bdfName;

			//       return enuBasicDefinitionType::getLabel($bdf) . ': ' . $desc;
			//     }

			//     // $mhaList = enuBasicDefinitionType::getList();
			//     // foreach($mhaList as $k => $v) {
			//     //   if ($fieldType == 'mha:' . $k) {
			//     //     return $v . ': ' . $desc;
			//     //   }
			//     // }

			//     return $desc;
			//   },
			// ],
		];
	}

	public function isSoftDeleted()
	{
		return ($this->rptStatus == enuReportStatus::Removed);
	}

	public static function canCreate()
	{
		return true;
	}

	public function canUpdate()
	{
		return ($this->rptStatus != enuReportStatus::Removed);
	}

	public function canDelete()
	{
		return ($this->rptStatus != enuReportStatus::Removed);
	}

	public function canUndelete()
	{
		return ($this->rptStatus == enuReportStatus::Removed);
	}

	public function save($runValidation = true, $attributeNames = null)
	{
		$errors = [];

		$this->rptInputFields = ArrayHelper::filterNullOrEmpty($this->rptInputFields);
		if (empty($this->rptInputFields))
			$errors[] = 'فیلترهای ورودی مشخص نشده‌اند';

		$this->rptOutputFields = ArrayHelper::filterNullOrEmpty($this->rptOutputFields, true);
		if (empty($this->rptOutputFields))
			$errors[] = 'ستون‌های خروجی مشخص نشده‌اند';

		if (empty($errors) == false) {
			$this->addError(null, $errors);
			return false;
		}

		//---------------------
		return parent::save($runValidation, $attributeNames);
	}

	public function run()
	{
		$params = ['id' => $this->rptID];

		if (empty($_GET['sort']) == false) $params['sort'] = $_GET['sort'];

		if (empty($_GET['page']) == false) $params['page'] = $_GET['page'];

		if ((empty($_GET['per-page']) == false) || (
				isset($_GET['per-page']) && ($_GET['per-page'] == 0)
			)
		) {
			$params['per-page'] = $_GET['per-page'];
		}

		$apiResponse = HttpHelper::callApi(self::$resourceName . "/run", HttpHelper::METHOD_GET, $params);

		if ($apiResponse['status'] != 200)
			return null;

		$config = [
			'allModels' => $apiResponse['body']['data'],
			// 'sort' => [
			// 	'attributes' => [
			// 	],
			// ],
		];

		// $config['pagination'] = $apiResponse['body']['pagination'];

		$dataProvider = new ArrayDataProvider($config);

		$dataProvider->setModels($apiResponse['body']['data']);

		$dataProvider->setTotalCount($apiResponse['body']['pagination']['totalCount']);

		$page = 0;
		if (empty($_GET['page']) == false)
			$page = intval($_GET['page']) - 1;

		// $dataProvider->pagination->setPage($page);

		$dataProvider->setPagination([
			'page' => $page,
			// 'pageSize' => 20,
			'totalCount' => $apiResponse['body']['pagination']['totalCount'],
		]);

		// if (isset($apiResponse['body']['pagination']['totalCount'])) {
		// 	// $config['pagination'] = $apiResponse['body']['pagination'];
		// 	$dataProvider->setTotalCount($apiResponse['body']['pagination']['totalCount']);
		// }

		return $dataProvider;

		/*
		$query = self::find()
			->endpoint('run')
			// ->limit(null)
			// ->offset(null)
			->addUrlParameter('id', $this->rptID)
		;

		$dataProvider = new RestClientDataProvider([
			'query' => $query,
			// 'pagination' => false, //prevent HEAD request
			// 'sort' => [
			// 	// 'enableMultiSort' => true,
			// 	'attributes' => [
			// 	],
			// ],
		]);

		return $dataProvider;
		*/
	}

	public function export()
	{
		$params = ['id' => $this->rptID];

		if (empty($_GET['sort']) == false)
			$params['sort'] = $_GET['sort'];

		$params['per-page'] = 0;

		$apiResponse = HttpHelper::callApi(self::$resourceName . "/run", HttpHelper::METHOD_GET, $params);

		if ($apiResponse['status'] != 200)
			return null;

		return $apiResponse['body']['data'];
	}
}
