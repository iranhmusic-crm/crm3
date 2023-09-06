<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\common\helpers\Json;
use shopack\base\common\helpers\StringHelper;
use shopack\base\frontend\helpers\Html;
use shopack\base\frontend\widgets\grid\GridView;
use shopack\aaa\common\enums\enuGender;
use shopack\aaa\common\enums\enuUserStatus;
use iranhmusic\shopack\mha\frontend\common\models\MemberModel;
use iranhmusic\shopack\mha\frontend\common\models\MemberKanoonModel;
use iranhmusic\shopack\mha\frontend\common\models\KanoonModel;
use iranhmusic\shopack\mha\common\enums\enuKanoonMembershipDegree;
use iranhmusic\shopack\mha\common\enums\enuBasicDefinitionType;
use iranhmusic\shopack\mha\frontend\common\models\BasicDefinitionModel;
?>

<div>
<?php
  if ($dataProvider->getCount() <= 0) {
    echo Html::div('این گزارش خالی است.', ['class' => ['text-center']]);
  } else {
    $columns = [
      [
        'class' => 'kartik\grid\SerialColumn',
      ],
    ];

    $outputFields = [
      'mbrUserID'              => Yii::t('app', 'User ID'),
      'usrImageFileID'         => Yii::t('aaa', 'Image'),
      'usrGender'              => [
        'label' => Yii::t('aaa', 'Gender'),
        'value' => function ($model, $key, $index, $widget) {
          return enuGender::getLabel($model['usrGender']);
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
      ],
      'mbrExpireDate'          => [
        'label' => Yii::t('mha', 'Expire Date'),
        'format' => 'jalali',
      ],

      'knnName'                => Yii::t('mha', 'Kanoon'),

      'InstrumentName'         => Yii::t('mha', 'Instrument'),
			'SingName'							 => Yii::t('mha', 'Sing'),
			'ResearchName'					 => Yii::t('mha', 'Research'),

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
      ],

      'usrFatherName'          => Yii::t('aaa', 'Father Name'),
      'usrFatherName_en'       => Yii::t('aaa', 'Father Name (en)'),
      'usrEmail'               => Yii::t('aaa', 'Email'),
      'usrEmailApprovedAt'     => [
        'label' => Yii::t('aaa', 'Email Approved At'),
        'format' => 'jalaliWithTime',
      ],
      'usrMobile'              => Yii::t('aaa', 'Mobile'),
      'usrMobileApprovedAt'    => [
        'label' => Yii::t('aaa', 'Mobile Approved At'),
        'format' => 'jalaliWithTime',
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
      ],
      // 'usrMustChangePassword'  => Yii::t('aaa', 'Must Change Password'),
      'usrBirthDate'           => [
        'label' => Yii::t('aaa', 'Birth Date'),
        'format' => 'jalali',
      ],
      'usrBirthCityID'         => Yii::t('aaa', 'Birth Location'),
      'BirthStateName'         => 'استان تولد',
      'BirthCityName'          => 'شهر تولد',
      'usrStatus'              => [
        'label' => Yii::t('app', 'Status'),
        'value' => function ($model, $key, $index, $widget) {
          return enuUserStatus::getLabel($model['usrStatus']);
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

    foreach ($outputFields as $k    => $v) {
      if (array_key_exists($k, $dataProvider->allModels[0]) == false)
        continue;

      $column = [
        'attribute' => $k,
      ];

      if (is_array($v)) {
        $column = array_merge($column, $v);
      } else {
        $column['label'] = $v;
      }

      $columns[] = $column;
    }

    echo GridView::widget([
      'id' => StringHelper::generateRandomId(),
      'dataProvider' => $dataProvider,
      'columns' => $columns,
    ]);
  }

?>
</div>
