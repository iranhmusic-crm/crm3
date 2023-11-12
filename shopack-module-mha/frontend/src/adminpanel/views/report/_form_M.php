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

			'<hr>',

			['@cols' => 2, 'vertical' => true],
		]);

		$builder->fields([
			['@section', 'label' => 'فیلترهای ورودی'],

			['rptInputFields[mbrRegisterCode]',
				'label' => 'کد عضویت',
				'fieldOptions' => [
					'addon' => [
						'prepend' => [
							'content' => Html::checkbox(Html::getInputName($model, 'rptInputFields[mbrRegisterCode_None]'),
								($model->rptInputFields['mbrRegisterCode_None'] ?? 0) == 1,
								[
									'id' => Html::getInputId($model, 'rptInputFields[mbrRegisterCode_None]'),
									'label' => 'ندارد',
								]),
						],
					],
				],
			],

			['@col-break'],
			'<hr>',

			['rptInputFields[mbrAcceptedAt_None]',
				// 'label' => 'تاریخ تایید عضویت',
				'label' => 'ندارد',
				'type' => FormBuilder::FIELD_CHECKBOX,
				'widgetOptions' => [[], true],
			],
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

			['@col-break'],

			['rptInputFields[mbrExpireDate_None]',
				// 'label' => 'تاریخ انقضای عضویت',
				'label' => 'ندارد',
				'type' => FormBuilder::FIELD_CHECKBOX,
				'widgetOptions' => [[], true],
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
			'<hr>',

			['rptInputFields[usrBirthLocation_None]',
				// 'label' => 'محل تولد',
				'label' => 'ندارد',
				'type' => FormBuilder::FIELD_CHECKBOX,
				'widgetOptions' => [[], true],
			],
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
				'label' => 'شهر',
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
			['rptInputFields[usrBirthLocation][Town]',
				'label' => 'منطقه',
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
						'depends' => ["{$formNameLower}-rptinputfields-usrbirthlocation-city"],
						'initialize' => true,
						'url' => Url::to(['/aaa/geo-town/depdrop-list', 'sel' => $model->rptInputFields['usrBirthLocation']['City'] ?? null]),
						'loadingText' => Yii::t('app', 'Loading...'),
					],
				],
			],

			['@col-break'],

			['rptInputFields[usrBirthDate_None]',
				// 'label' => 'تاریخ تولد',
				'label' => 'ندارد',
				'type' => FormBuilder::FIELD_CHECKBOX,
				'widgetOptions' => [[], true],
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

			['@col-break'],
			'<hr>',

			['rptInputFields[Location_None]',
				// 'label' => 'محل سکونت',
				'label' => 'ندارد',
				'type' => FormBuilder::FIELD_CHECKBOX,
				'widgetOptions' => [[], true],
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
				'label' => 'شهر',
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


			['@col-break'],
			'<hr>',

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
				'fieldOptions' => [
					'addon' => [
						'prepend' => [
							'content' => Html::checkbox(Html::getInputName($model, 'rptInputFields[mbrknnKanoonID_None]'),
								($model->rptInputFields['mbrknnKanoonID_None'] ?? 0) == 1,
								[
									'id' => Html::getInputId($model, 'rptInputFields[mbrknnKanoonID_None]'),
									'label' => 'ندارد',
								]),
						],
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
				'fieldOptions' => [
					'addon' => [
						'prepend' => [
							'content' => Html::checkbox(Html::getInputName($model, 'rptInputFields[mbrknnMembershipDegree_None]'),
								($model->rptInputFields['mbrknnMembershipDegree_None'] ?? 0) == 1,
								[
									'id' => Html::getInputId($model, 'rptInputFields[mbrknnMembershipDegree_None]'),
									'label' => 'ندارد',
								]),
						],
					],
				],
			],

			['@col-break'],

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
				'fieldOptions' => [
					'addon' => [
						'prepend' => [
							'content' => Html::checkbox(Html::getInputName($model, 'rptInputFields[mbrInstrumentID_None]'),
								($model->rptInputFields['mbrInstrumentID_None'] ?? 0) == 1,
								[
									'id' => Html::getInputId($model, 'rptInputFields[mbrInstrumentID_None]'),
									'label' => 'ندارد',
								]),
						],
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
				'fieldOptions' => [
					'addon' => [
						'prepend' => [
							'content' => Html::checkbox(Html::getInputName($model, 'rptInputFields[mbrSingID_None]'),
								($model->rptInputFields['mbrSingID_None'] ?? 0) == 1,
								[
									'id' => Html::getInputId($model, 'rptInputFields[mbrSingID_None]'),
									'label' => 'ندارد',
								]),
						],
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
				'fieldOptions' => [
					'addon' => [
						'prepend' => [
							'content' => Html::checkbox(Html::getInputName($model, 'rptInputFields[mbrResearchID_None]'),
								($model->rptInputFields['mbrResearchID_None'] ?? 0) == 1,
								[
									'id' => Html::getInputId($model, 'rptInputFields[mbrResearchID_None]'),
									'label' => 'ندارد',
								]),
						],
					],
				],
			],

			'<hr>',

			['rptInputFields[mbrJob]',
				'label' => 'شغل',
				'type' => FormBuilder::FIELD_TEXT,
			],

			'<hr>',
		]);

		$builder->fields([
			['@section', 'label' => 'ستون‌های خروجی'],
			['@cols' => 4, 'vertical' => true],
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

			'@col-break',

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

			'@col-break',

			'usrBirthDate'          => Yii::t('aaa', 'Birth Date'),
			'usrBirthCityID'        => Yii::t('aaa', 'Birth Location'),
			'usrCountryID'          => Yii::t('aaa', 'Country'),
			'usrStateID'            => Yii::t('aaa', 'State'),
			'usrCityOrVillageID'    => Yii::t('aaa', 'City Or Village'),
			'usrTownID'             => Yii::t('aaa', 'Town'),
			'usrZipCode'            => Yii::t('aaa', 'Zip Code'),
			'usrHomeAddress'        => Yii::t('aaa', 'Home Address'),

			'mbrJob'									=> Yii::t('mha', 'Job'),

			'@col-break',

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

			'knnName'									=> Yii::t('mha', 'Kanoon'),
			'mbrknnMembershipDegree'	=> Yii::t('mha', 'Membership Degree'),

			'mbrInstrumentID'					=> Yii::t('mha', 'Instrument'),
			'mbrSingID'								=> Yii::t('mha', 'Sing'),
			'mbrResearchID'						=> Yii::t('mha', 'Research'),
		];

		foreach ($outputFields as $k => $v) {
			if ($v == '@col-break') {
				$builder->fields([
					['@col-break'],
				]);
			} else {
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
