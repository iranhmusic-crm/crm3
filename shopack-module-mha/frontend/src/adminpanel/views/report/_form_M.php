<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use yii\web\JsExpression;
use borales\extensions\phoneInput\PhoneInput;
use shopack\base\common\helpers\Url;
use shopack\base\common\helpers\ArrayHelper;
use shopack\base\frontend\common\widgets\Select2;
use shopack\base\frontend\common\widgets\DepDrop;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\frontend\common\widgets\ActiveForm;
use shopack\base\frontend\common\widgets\FormBuilder;
use shopack\base\frontend\common\widgets\datetime\DatePicker;
use shopack\aaa\frontend\common\models\UserModel;
use shopack\aaa\common\enums\enuGender;
use shopack\aaa\frontend\common\models\GeoStateModel;
use iranhmusic\shopack\mha\common\enums\enuMemberStatus;
use iranhmusic\shopack\mha\common\enums\enuBasicDefinitionType;
use iranhmusic\shopack\mha\common\enums\enuKanoonMembershipDegree;
use iranhmusic\shopack\mha\frontend\common\models\KanoonModel;
use iranhmusic\shopack\mha\frontend\common\models\BasicDefinitionModel;
?>

<div class='members-report-form'>
	<?php
		$form = ActiveForm::begin([
			'model' => $model,
		]);

		$formName = $model->formName();
    $formNameLower = strtolower($formName);

		$builder = $form->getBuilder();

		$builder->fields([
			['rptName'],
			['@cols' => 3, 'vertical' => true],
		]);

		$builder->fields([
			['@section', 'label' => 'فیلترهای ورودی'],

			['rptInputFields[usrBirthLocation][State]',
				'label' => 'استان محل تولد',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'data' => ArrayHelper::map(GeoStateModel::find()->asArray()->noLimit()->all(), 'sttID', 'sttName'),
					'options' => [
						'placeholder' => Yii::t('app', '-- Choose --'),
						'dir' => 'rtl',
					],
					'pluginOptions' => [
						'allowClear' => true,
					],
				],
			],
			['rptInputFields[usrBirthLocation][City]',
				'label' => 'شهر محل تولد',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => DepDrop::class,
				'widgetOptions' => [
					'type' => DepDrop::TYPE_SELECT2,
					'options' => [
						'placeholder' => Yii::t('app', '-- Choose --'),
						'dir' => 'rtl',
					],
					'select2Options' => [
						'pluginOptions' => [
							'allowClear' => true,
						],
					],
					'pluginOptions' => [
						'depends' => ["{$formNameLower}-rptinputfields-usrbirthlocation-state"],
						'initialize' => true,
						'url' => Url::to(['/aaa/geo-city-or-village/depdrop-list', 'sel' => $model->rptInputFields['usrBirthLocation']['City'] ?? null]),
						'loadingText' => Yii::t('app', 'Loading...'),
					],
				],
			],
			['rptInputFields[usrBirthDate][From]',
				'label' => 'تاریخ تولد (از)',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => DatePicker::class,
				'fieldOptions' => [
					'addon' => [
						'append' => [
							'content' => '<i class="far fa-calendar-alt"></i>',
						],
					],
				],
				'widgetOptions' => [
					'allowClear' => true,
				],
			],
			['rptInputFields[usrBirthDate][To]',
				'label' => 'تاریخ تولد (تا)',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => DatePicker::class,
				'fieldOptions' => [
					'addon' => [
						'append' => [
							'content' => '<i class="far fa-calendar-alt"></i>',
						],
					],
				],
				'widgetOptions' => [
					'allowClear' => true,
				],
			],
			['rptInputFields[usrStateID]',
				'label' => 'استان محل سکونت',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'data' => ArrayHelper::map(GeoStateModel::find()->asArray()->noLimit()->all(), 'sttID', 'sttName'),
					'options' => [
						'placeholder' => Yii::t('app', '-- Choose --'),
						'dir' => 'rtl',
					],
					'pluginOptions' => [
						'allowClear' => true,
					],
				],
			],
			['rptInputFields[usrCityOrVillageID]',
				'label' => 'شهر محل سکونت',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => DepDrop::class,
				'widgetOptions' => [
					'type' => DepDrop::TYPE_SELECT2,
					'options' => [
						'placeholder' => Yii::t('app', '-- Choose --'),
						'dir' => 'rtl',
					],
					'select2Options' => [
						'pluginOptions' => [
							'allowClear' => true,
						],
					],
					'pluginOptions' => [
						'depends' => ["{$formNameLower}-rptinputfields-usrstateid"],
						'initialize' => true,
						'url' => Url::to(['/aaa/geo-city-or-village/depdrop-list', 'sel' => $model->rptInputFields['usrCityOrVillageID'] ?? null]),
						'loadingText' => Yii::t('app', 'Loading...'),
					],
				],
			],

			['@col-break'],

			['rptInputFields[mbrAcceptedAt][From]',
				'label' => 'تاریخ تایید عضویت (از)',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => DatePicker::class,
				'fieldOptions' => [
					'addon' => [
						'append' => [
							'content' => '<i class="far fa-calendar-alt"></i>',
						],
					],
				],
				'widgetOptions' => [
					'allowClear' => true,
				],
			],
			['rptInputFields[mbrAcceptedAt][To]',
				'label' => 'تاریخ تایید عضویت (تا)',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => DatePicker::class,
				'fieldOptions' => [
					'addon' => [
						'append' => [
							'content' => '<i class="far fa-calendar-alt"></i>',
						],
					],
				],
				'widgetOptions' => [
					'allowClear' => true,
				],
			],
			['rptInputFields[mbrExpireDate][From]',
				'label' => 'تاریخ انقضای عضویت (از)',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => DatePicker::class,
				'fieldOptions' => [
					'addon' => [
						'append' => [
							'content' => '<i class="far fa-calendar-alt"></i>',
						],
					],
				],
				'widgetOptions' => [
					'allowClear' => true,
				],
			],
			['rptInputFields[mbrExpireDate][To]',
				'label' => 'تاریخ انقضای عضویت (تا)',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => DatePicker::class,
				'fieldOptions' => [
					'addon' => [
						'append' => [
							'content' => '<i class="far fa-calendar-alt"></i>',
						],
					],
				],
				'widgetOptions' => [
					'allowClear' => true,
				],
			],

			['@col-break'],

			['rptInputFields[mbrknnKanoonID]',
				'label' => 'کانون',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'data' => ArrayHelper::map(KanoonModel::find()->asArray()->noLimit()->all(), 'knnID', 'knnName'),
					'options' => [
						'placeholder' => Yii::t('app', '-- Choose --'),
						'dir' => 'rtl',
					],
					'pluginOptions' => [
						'allowClear' => true,
					],
				],
			],
			['rptInputFields[mbrknnMembershipDegree]',
				'label' => 'رده عضویت',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'data' => enuKanoonMembershipDegree::getList(),
					'options' => [
						'placeholder' => Yii::t('app', '-- Choose --'),
						'dir' => 'rtl',
					],
					'pluginOptions' => [
						'allowClear' => true,
					],
				],
			],

			['rptInputFields[mbrInstrumentID]',
				'label' => 'ساز',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'data' => ArrayHelper::map(BasicDefinitionModel::find()->where(['bdfType' => enuBasicDefinitionType::Instrument])->asArray()->noLimit()->all(), 'bdfID', 'bdfName'),
					'options' => [
						'placeholder' => Yii::t('app', '-- Choose --'),
						'dir' => 'rtl',
					],
					'pluginOptions' => [
						'allowClear' => true,
					],
				],
			],
			['rptInputFields[mbrSingID]',
				'label' => 'آواز',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'data' => ArrayHelper::map(BasicDefinitionModel::find()->where(['bdfType' => enuBasicDefinitionType::Sing])->asArray()->noLimit()->all(), 'bdfID', 'bdfName'),
					'options' => [
						'placeholder' => Yii::t('app', '-- Choose --'),
						'dir' => 'rtl',
					],
					'pluginOptions' => [
						'allowClear' => true,
					],
				],
			],
			['rptInputFields[mbrResearchID]',
				'label' => 'پژوهش',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'data' => ArrayHelper::map(BasicDefinitionModel::find()->where(['bdfType' => enuBasicDefinitionType::Research])->asArray()->noLimit()->all(), 'bdfID', 'bdfName'),
					'options' => [
						'placeholder' => Yii::t('app', '-- Choose --'),
						'dir' => 'rtl',
					],
					'pluginOptions' => [
						'allowClear' => true,
					],
				],
			],
		]);

		$builder->fields([
			['@section', 'label' => 'ستون‌های خروجی'],
			['@cols' => 4, 'vertical' => false],
		]);

		$outputFields = [
      // 'usrID'                 => Yii::t('app', 'ID'),
			'usrImageFileID'        => Yii::t('aaa', 'Image'),
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
      // 'usrRoleID'             => Yii::t('aaa', 'Role'),
      // 'usrPrivs'              => Yii::t('aaa', 'Exclusive Privs'),
      // 'usrPassword'           => Yii::t('aaa', 'Password'),
      // 'usrRetypePassword'     => Yii::t('aaa', 'Retype Password'),
      // 'usrPasswordHash'       => Yii::t('aaa', 'Password Hash'),
      'hasPassword'           => Yii::t('aaa', 'Has Password'),
      'usrPasswordCreatedAt'  => Yii::t('aaa', 'Password Created At'),
      // 'usrMustChangePassword' => Yii::t('aaa', 'Must Change Password'),
			'usrBirthDate'          => Yii::t('aaa', 'Birth Date'),
			'usrBirthCityID'        => Yii::t('aaa', 'Birth Location'),
			'usrCountryID'          => Yii::t('aaa', 'Country'),
			'usrStateID'            => Yii::t('aaa', 'State'),
			'usrCityOrVillageID'    => Yii::t('aaa', 'City Or Village'),
			'usrTownID'             => Yii::t('aaa', 'Town'),
			'usrHomeAddress'        => Yii::t('aaa', 'Home Address'),
			'usrZipCode'            => Yii::t('aaa', 'Zip Code'),
      'usrStatus'             => Yii::t('app', 'Status'),
      // 'usrCreatedAt'          => Yii::t('app', 'Created At'),
      // 'usrCreatedBy'          => Yii::t('app', 'Created By'),
      // 'usrCreatedBy_User'     => Yii::t('app', 'Created By'),
      // 'usrUpdatedAt'          => Yii::t('app', 'Updated At'),
      // 'usrUpdatedBy'          => Yii::t('app', 'Updated By'),
      // 'usrUpdatedBy_User'     => Yii::t('app', 'Updated By'),
      // 'usrRemovedAt'          => Yii::t('app', 'Removed At'),
      // 'usrRemovedBy'          => Yii::t('app', 'Removed By'),
      // 'usrRemovedBy_User'     => Yii::t('app', 'Removed By'),

			'mbrRegisterCode'					=> Yii::t('mha', 'Register Code'),
			'mbrAcceptedAt'						=> Yii::t('mha', 'Registration Accepted At'),
			'mbrExpireDate'						=> Yii::t('mha', 'Expire Date'),
			'mbrInstrumentID'					=> Yii::t('mha', 'Instrument'),
			'mbrSingID'								=> Yii::t('mha', 'Sing'),
			'mbrResearchID'						=> Yii::t('mha', 'Research'),

			'knnName'									=> Yii::t('mha', 'Kanoon'),
			'mbrknnMembershipDegree'	=> Yii::t('mha', 'Membership Degree'),
		];

		foreach ($outputFields as $k => $v) {
			$builder->fields([
				[
					"rptOutputFields[{$k}]",
					'label' => $v,
					'type' => FormBuilder::FIELD_CHECKBOX,
					'widgetOptions' => [[], true],
					'fieldOptions' => [
						'autoOffset' => false,
					],
				],
			]);
		}
	?>

	<?php $builder->beginField(); ?>
		<div id='params-container' class='row offset-md-2'></div>
	<?php $builder->endField(); ?>

	<?php $builder->beginFooter(); ?>
		<div class="card-footer">
			<div class="float-end">
				<?= Html::activeSubmitButton($model) ?>
			</div>
			<div>
				<?= Html::formErrorSummary($model); ?>
			</div>
			<div class="clearfix"></div>
		</div>
	<?php $builder->endFooter(); ?>

	<?php
		$builder->render();
		$form->endForm(); //ActiveForm::end();
	?>
</div>
