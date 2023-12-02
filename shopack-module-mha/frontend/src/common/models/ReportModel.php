<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\models;

use iranhmusic\shopack\mha\common\enums\enuKanoonMembershipDegree;
use Yii;
use yii\data\ArrayDataProvider;
use shopack\base\frontend\common\rest\RestClientActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuReportStatus;
use shopack\aaa\common\enums\enuGender;
use shopack\aaa\common\enums\enuUserStatus;
use shopack\base\common\helpers\ArrayHelper;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\frontend\common\rest\RestClientDataProvider;

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
		return [
			'mbrUserID'              => [
				'label' => 'کد کاربری', //Yii::t('aaa', 'User ID'),
				'format' => 'raw',
				'value' => function ($model, $key, $index, $widget) {
					return Html::a($model['mbrUserID'], ['/mha/member/view', 'id' => $model['mbrUserID']]);
				},
			],
			'usrImageFileID'         => Yii::t('aaa', 'Image'),
			'usrGender'              => [
				'label' => Yii::t('aaa', 'Gender'),
				'value' => function ($model, $key, $index, $widget) {
					return enuGender::getLabel($model['usrGender']);
				},
				'export' => function ($value) {
					return enuGender::getLabel($value);
				},
			],
			'usrFirstName'           => Yii::t('aaa', 'First Name'),
			'usrFirstName_en'        => Yii::t('aaa', 'First Name (en)'),
			'usrLastName'            => Yii::t('aaa', 'Last Name'),
			'usrLastName_en'         => Yii::t('aaa', 'Last Name (en)'),

			'mbrRegisterCode'        => Yii::t('mha', 'Register Code'),
			'mbrAcceptedAt'          => [
				'label' => Yii::t('mha', 'Registration Accepted At'),
				'format' => 'jalaliWithTime',
				'export' => function ($value) {
					return Yii::$app->formatter->asJalaliWithTime($value);
				},
			],
			'mbrExpireDate'          => [
				'label' => Yii::t('mha', 'Expire Date'),
				'format' => 'jalali',
				'export' => function ($value) {
					return Yii::$app->formatter->asJalali($value);
				},
			],

			'knnName'                => Yii::t('mha', 'Kanoon'),

			'InstrumentName'         => Yii::t('mha', 'Instrument'),
			'SingName'							 => Yii::t('mha', 'Sing'),
			'ResearchName'					 => Yii::t('mha', 'Research'),

			'mbrJob'					 => Yii::t('mha', 'Job'),

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
			'mbrknnMembershipDegree' => [
				'label' => Yii::t('mha', 'Membership Degree'),
				'value' => function ($model, $key, $index, $widget) {
					return enuKanoonMembershipDegree::getLabel($model['mbrknnMembershipDegree']);
				},
				'export' => function ($value) {
					return enuKanoonMembershipDegree::getLabel($value);
				},
			],

			'usrFatherName'          => Yii::t('aaa', 'Father Name'),
			'usrFatherName_en'       => Yii::t('aaa', 'Father Name (en)'),
			'usrEmail'               => Yii::t('aaa', 'Email'),
			'usrEmailApprovedAt'     => [
				'label' => Yii::t('aaa', 'Email Approved At'),
				'format' => 'jalaliWithTime',
				'export' => function ($value) {
					return Yii::$app->formatter->asJalaliWithTime($value);
				},
			],
			'usrMobile'              => [
				'label' => Yii::t('aaa', 'Mobile'),
				'format' => 'phone',
				// 'template' => '<phone>{value}</phone>',
				// 'value' => function ($model, $key, $index, $widget) {
				//   return '<phone>' . Yii::$app->formatter->asPhone($model['usrMobile']) . '</phone>';
				// },
			],
			'usrMobileApprovedAt'    => [
				'label' => Yii::t('aaa', 'Mobile Approved At'),
				'format' => 'jalaliWithTime',
				'export' => function ($value) {
					return Yii::$app->formatter->asJalaliWithTime($value);
				},
			],
			'usrSSID'                => Yii::t('aaa', 'SSID'),
			// 'usrRoleID'              => Yii::t('aaa', 'Role'),
			// 'usrPrivs'               => Yii::t('aaa', 'Exclusive Privs'),
			// 'usrPassword'            => Yii::t('aaa', 'Password'),
			// 'usrRetypePassword'      => Yii::t('aaa', 'Retype Password'),
			// 'usrPasswordHash'        => Yii::t('aaa', 'Password Hash'),
			'hasPassword'            => [
				'label' => Yii::t('aaa', 'Has Password'),
				'value' => function ($model, $key, $index, $widget) {
					return ($model['hasPassword'] ? 'بلی' : 'خیر');
				},
			],
			'usrPasswordCreatedAt'   => [
				'label' => Yii::t('aaa', 'Password Created At'),
				'format' => 'jalaliWithTime',
				'export' => function ($value) {
					return Yii::$app->formatter->asJalaliWithTime($value);
				},
			],
			// 'usrMustChangePassword'  => Yii::t('aaa', 'Must Change Password'),
			'usrBirthDate'           => [
				'label' => Yii::t('aaa', 'Birth Date'),
				'format' => 'jalali',
				'export' => function ($value) {
					return Yii::$app->formatter->asJalali($value);
				},
			],
			'usrBirthCityID'         => Yii::t('aaa', 'Birth Location'),
			'BirthStateName'         => 'استان تولد',
			'BirthCityName'          => 'شهر تولد',
			'usrStatus'              => [
				'label' => Yii::t('app', 'Status'),
				'value' => function ($model, $key, $index, $widget) {
					return enuUserStatus::getLabel($model['usrStatus']);
				},
				'export' => function ($value) {
					return enuUserStatus::getLabel($value);
				},
			],
			// 'usrCreatedAt'           => Yii::t('app', 'Created At'),
			// 'usrCreatedBy'           => Yii::t('app', 'Created By'),
			// 'usrCreatedBy_User'      => Yii::t('app', 'Created By'),
			// 'usrUpdatedAt'           => Yii::t('app', 'Updated At'),
			// 'usrUpdatedBy'           => Yii::t('app', 'Updated By'),
			// 'usrUpdatedBy_User'      => Yii::t('app', 'Updated By'),
			// 'usrRemovedAt'           => Yii::t('app', 'Removed At'),
			// 'usrRemovedBy'           => Yii::t('app', 'Removed By'),
			// 'usrRemovedBy_User'      => Yii::t('app', 'Removed By'),

			'usrCountryID'           => Yii::t('aaa', 'Country'),
			'usrStateID'             => Yii::t('aaa', 'State'),
			'usrCityOrVillageID'     => Yii::t('aaa', 'City Or Village'),
			'usrTownID'              => Yii::t('aaa', 'Town'),
			'usrHomeAddress'         => Yii::t('aaa', 'Home Address'),
			'usrZipCode'             => Yii::t('aaa', 'Zip Code'),

			'HomeStateName'          => 'استان سکونت',
			'HomeCityName'           => 'شهر سکونت',
		];
	}

	public function isSoftDeleted()
  {
    return ($this->rptStatus == enuReportStatus::Removed);
  }

	public static function canCreate() {
		return true;
	}

	public function canUpdate() {
		return ($this->rptStatus != enuReportStatus::Removed);
	}

	public function canDelete() {
		return ($this->rptStatus != enuReportStatus::Removed);
	}

	public function canUndelete() {
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
				))
			$params['per-page'] = $_GET['per-page'];

		$result = HttpHelper::callApi(self::$resourceName . "/run", HttpHelper::METHOD_GET, $params);

    if ($result[0] != 200)
			return null;

		$config = [
			'allModels' => $result[1]['data'],
			// 'sort' => [
			// 	'attributes' => [
			// 	],
			// ],
		];

		// $config['pagination'] = $result[1]['pagination'];

		$dataProvider = new ArrayDataProvider($config);

		$dataProvider->setModels($result[1]['data']);

		$dataProvider->setTotalCount($result[1]['pagination']['totalCount']);

		$page = 0;
		if (empty($_GET['page']) == false)
			$page = intval($_GET['page']) - 1;

		// $dataProvider->pagination->setPage($page);

		$dataProvider->setPagination([
			'page' => $page,
			// 'pageSize' => 20,
			'totalCount' => $result[1]['pagination']['totalCount'],
		]);

		// if (isset($result[1]['pagination']['totalCount'])) {
		// 	// $config['pagination'] = $result[1]['pagination'];
		// 	$dataProvider->setTotalCount($result[1]['pagination']['totalCount']);
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

		$result = HttpHelper::callApi(self::$resourceName . "/run", HttpHelper::METHOD_GET, $params);

    if ($result[0] != 200)
			return null;

		return $result[1]['data'];
	}

}
