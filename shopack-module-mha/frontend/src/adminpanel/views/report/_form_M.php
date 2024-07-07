<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\helpers\ArrayHelper;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\frontend\common\widgets\ActiveForm;
use shopack\base\frontend\common\widgets\FormBuilder;
use shopack\base\frontend\common\widgets\Select2;
use shopack\base\frontend\common\widgets\datetime\DatePicker;
use shopack\aaa\common\enums\enuGender;
use shopack\aaa\frontend\common\widgets\form\GeoCityOrVillageChooseFormField;
use shopack\aaa\frontend\common\widgets\form\GeoStateChooseFormField;
use shopack\aaa\frontend\common\widgets\form\GeoTownChooseFormField;
use iranhmusic\shopack\mha\common\enums\enuBasicDefinitionType;
use iranhmusic\shopack\mha\common\enums\enuKanoonMembershipDegree;
use iranhmusic\shopack\mha\frontend\common\models\BasicDefinitionModel;
use iranhmusic\shopack\mha\frontend\common\widgets\form\KanoonChooseFormField;
?>

<div class='members-report-form'>
	<?php
		$form = ActiveForm::begin([
			'model' => $model,
		]);

		$formName = $model->formName();
    $formNameLower = strtolower($formName);

		$js =<<<JS
var _lock_nullableRadioCheckChanged = false;
function nullableRadioCheckChanged(e)
{
  if (_lock_nullableRadioCheckChanged)
    return;

  _lock_nullableRadioCheckChanged = true;

  var sender = null;
  if (e != null) {
    if (e.target !== undefined)
      sender = e.target;
    else if (e.input !== undefined) {
      if (e.input.length !== undefined)
        sender = e.input[0];
      else
        sender = e.input;
    }
  }

	prefix = sender.id.substring(0, sender.id.length - 1);

  $('input:checkbox[id^="' + prefix + '"]').each(function() {
    var el = $(this);
    if ((el.attr('id') != sender.id) && el.is(':checked')) {
      el.prop('checked', false);
    }
  });

  _lock_nullableRadioCheckChanged = false;
}
JS;
		$this->registerJs($js, \yii\web\View::POS_END);

		$js =<<<JS
$('[id*="-has--"]').each(function() { $(this).on('change', function(e) {
	nullableRadioCheckChanged(e);
}); });
JS;
		$this->registerJs($js, \yii\web\View::POS_READY);

		$builder = $form->getBuilder();

		$builder->fields([
			['rptName'],

			['@cols' => 2, 'vertical' => true],
		]);

		$fnGetValue = function($value, $qouted = false) {
			return ($qouted ? "'" : "") . "{$value}" . ($qouted ? "'" : "");
		};

		$builder->fields([
			['@section', 'label' => 'فیلترهای ورودی'],

			[
				'rptInputFields[mbrRegisterCode_Has]',
				'label' => 'کد عضویت',
				'type' => FormBuilder::FIELD_CHECKBOXLIST,
				'data' => [
					0 => 'ندارد',
					1 => 'دارد',
				],
				'widgetOptions' => [
					'inline' => true,
				],
			],
			[
				'rptInputFields[mbrRegisterCode]',
				'label' => '',
				'visibleConditions' => [
					'rptInputFields[mbrRegisterCode_Has]' => ['js', "function() { return ({{conditionFieldValue}} == false); }()"],
				],
			],

			['@col-break'],

			[
				'rptInputFields[usrGender_Has]',
				'label' => 'جنسیت',
				'type' => FormBuilder::FIELD_CHECKBOXLIST,
				'data' => [
					0 => 'ندارد',
					1 => 'دارد',
				],
				'widgetOptions' => [
					'inline' => true,
				],
			],
			[
				'rptInputFields[usrGender]',
				'label' => '',
				'visibleConditions' => [
					'rptInputFields[usrGender_Has]' => ['js', "function() { return ({{conditionFieldValue}} == false); }()"],
				],
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'data' => enuGender::listData(),
					'options' => [
						'placeholder' => Yii::t('app', '-- Choose --'),
						'dir' => 'rtl',
					],
					'pluginOptions' => [
						'allowClear' => true,
					],
				],
			],

			['@col-break'],
			'<hr>',

			[
				'rptInputFields[mbrAcceptedAt_Has]',
				'label' => 'تاریخ تایید عضویت',
				'type' => FormBuilder::FIELD_CHECKBOXLIST,
				'data' => [
					0 => 'ندارد',
					1 => 'دارد',
				],
				'widgetOptions' => [
					'inline' => true,
				],
			],
			[
				'rptInputFields[mbrAcceptedAt][From]',
				'label' => 'از',
				'visibleConditions' => [
					'rptInputFields[mbrAcceptedAt_Has]' => ['js', "function() { return ({{conditionFieldValue}} == false); }()"],
				],
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
				'label' => 'تا',
				'visibleConditions' => [
					'rptInputFields[mbrAcceptedAt_Has]' => ['js', "function() { return ({{conditionFieldValue}} == false); }()"],
				],
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

			[
				'rptInputFields[mbrExpireDate_Has]',
				'label' => 'تاریخ انقضای عضویت',
				'type' => FormBuilder::FIELD_CHECKBOXLIST,
				'data' => [
					0 => 'ندارد',
					1 => 'دارد',
				],
				'widgetOptions' => [
					'inline' => true,
				],
			],
			[
				'rptInputFields[mbrExpireDate][From]',
				'label' => 'از',
				'visibleConditions' => [
					'rptInputFields[mbrExpireDate_Has]' => ['js', "function() { return ({{conditionFieldValue}} == false); }()"],
				],
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
			[
				'rptInputFields[mbrExpireDate][To]',
				'label' => 'تا',
				'visibleConditions' => [
					'rptInputFields[mbrExpireDate_Has]' => ['js', "function() { return ({{conditionFieldValue}} == false); }()"],
				],
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

			[
				'rptInputFields[usrBirthLocation_Has]',
				'label' => 'محل تولد',
				'type' => FormBuilder::FIELD_CHECKBOXLIST,
				'data' => [
					0 => 'ندارد',
					1 => 'دارد',
				],
				'widgetOptions' => [
					'inline' => true,
				],
			],
			GeoStateChooseFormField::field($this, $model, 'rptInputFields[usrBirthLocation][State]', true, false, null, [
				'label' => 'استان',
				'visibleConditions' => [
					'rptInputFields[usrBirthLocation_Has]' => ['js', "function() { return ({{conditionFieldValue}} == false); }()"],
				],
			]),
			GeoCityOrVillageChooseFormField::field($this, $model, 'rptInputFields[usrBirthLocation][City]', true, false, 'rptInputFields[usrBirthLocation][State]', [
				'label' => 'شهر',
				'visibleConditions' => [
					'rptInputFields[usrBirthLocation_Has]' => ['js', "function() { return ({{conditionFieldValue}} == false); }()"],
				],
			]),
			GeoTownChooseFormField::field($this, $model, 'rptInputFields[usrBirthLocation][Town]', true, false, 'rptInputFields[usrBirthLocation][City]', [
				'label' => 'منطقه',
				'visibleConditions' => [
					'rptInputFields[usrBirthLocation_Has]' => ['js', "function() { return ({{conditionFieldValue}} == false); }()"],
				],
			]),

			['@col-break'],

			[
				'rptInputFields[usrBirthDate_Has]',
				'label' => 'تاریخ تولد',
				'type' => FormBuilder::FIELD_CHECKBOXLIST,
				'data' => [
					0 => 'ندارد',
					1 => 'دارد',
				],
				'widgetOptions' => [
					'inline' => true,
				],
			],
			[
				'rptInputFields[usrBirthDate][From]',
				'label' => 'از',
				'visibleConditions' => [
					'rptInputFields[usrBirthDate_Has]' => ['js', "function() { return ({{conditionFieldValue}} == false); }()"],
				],
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
			[
				'rptInputFields[usrBirthDate][To]',
				'label' => 'تا',
				'visibleConditions' => [
					'rptInputFields[usrBirthDate_Has]' => ['js', "function() { return ({{conditionFieldValue}} == false); }()"],
				],
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

			[
				'rptInputFields[Location_Has]',
				'label' => 'محل سکونت',
				'type' => FormBuilder::FIELD_CHECKBOXLIST,
				'data' => [
					0 => 'ندارد',
					1 => 'دارد',
				],
				'widgetOptions' => [
					'inline' => true,
				],
			],
			GeoStateChooseFormField::field($this, $model, 'rptInputFields[usrStateID]', true, false, null, [
				'label' => 'استان',
				'visibleConditions' => [
					'rptInputFields[Location_Has]' => ['js', "function() { return ({{conditionFieldValue}} == false); }()"],
				],
			]),
			GeoCityOrVillageChooseFormField::field($this, $model, 'rptInputFields[usrCityOrVillageID]', true, false, 'rptInputFields[usrStateID]', [
				'label' => 'شهر',
				'visibleConditions' => [
					'rptInputFields[Location_Has]' => ['js', "function() { return ({{conditionFieldValue}} == false); }()"],
				],
			]),

			['@col-break'],
			'<hr>',
		]);

		$builder->fields([
			[
				'rptInputFields[mbrknnKanoonID_Has]',
				'label' => 'کانون',
				'type' => FormBuilder::FIELD_CHECKBOXLIST,
				'data' => [
					0 => 'ندارد',
					1 => 'دارد',
				],
				'widgetOptions' => [
					'inline' => true,
				],
			],
			KanoonChooseFormField::field($this, $model, 'rptInputFields[mbrknnKanoonID]', true, true, [
				'label' => '',
				'visibleConditions' => [
					'rptInputFields[mbrknnKanoonID_Has]' => ['js', "function() { return ({{conditionFieldValue}} == false); }()"],
				],
			]),
		]);

		$builder->fields([
			[
				'rptInputFields[mbrknnMembershipDegree_Has]',
				'label' => 'رده عضویت',
				'type' => FormBuilder::FIELD_CHECKBOXLIST,
				'data' => [
					0 => 'ندارد',
					1 => 'دارد',
				],
				'widgetOptions' => [
					'inline' => true,
				],
			],
			[
				'rptInputFields[mbrknnMembershipDegree]',
				'label' => '',
				'visibleConditions' => [
					'rptInputFields[mbrknnMembershipDegree_Has]' => ['js', "function() { return ({{conditionFieldValue}} == false); }()"],
				],
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'data' => enuKanoonMembershipDegree::getList(),
					'options' => [
						'placeholder' => Yii::t('app', '-- Choose --'),
						'dir' => 'rtl',
						'multiple' => true,
					],
					'pluginOptions' => [
						'allowClear' => true,
					],
				],
			],

			['@col-break'],

			[
				'rptInputFields[mbrInstrumentID_Has]',
				'label' => 'ساز',
				'type' => FormBuilder::FIELD_CHECKBOXLIST,
				'data' => [
					0 => 'ندارد',
					1 => 'دارد',
				],
				'widgetOptions' => [
					'inline' => true,
				],
			],
			[
				'rptInputFields[mbrInstrumentID]',
				'label' => '',
				'visibleConditions' => [
					'rptInputFields[mbrInstrumentID_Has]' => ['js', "function() { return ({{conditionFieldValue}} == false); }()"],
				],
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'data' => ArrayHelper::map(BasicDefinitionModel::find()->where(['bdfType' => enuBasicDefinitionType::Instrument])->asArray()->noLimit()->all(), 'bdfID', 'bdfName'),
					'options' => [
						'placeholder' => Yii::t('app', '-- Choose --'),
						'dir' => 'rtl',
						'multiple' => true,
					],
					'pluginOptions' => [
						'allowClear' => true,
					],
				],
			],

			[
				'rptInputFields[mbrSingID_Has]',
				'label' => 'آواز',
				'type' => FormBuilder::FIELD_CHECKBOXLIST,
				'data' => [
					0 => 'ندارد',
					1 => 'دارد',
				],
				'widgetOptions' => [
					'inline' => true,
				],
			],
			[
				'rptInputFields[mbrSingID]',
				'label' => '',
				'visibleConditions' => [
					'rptInputFields[mbrSingID_Has]' => ['js', "function() { return ({{conditionFieldValue}} == false); }()"],
				],
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'data' => ArrayHelper::map(BasicDefinitionModel::find()->where(['bdfType' => enuBasicDefinitionType::Sing])->asArray()->noLimit()->all(), 'bdfID', 'bdfName'),
					'options' => [
						'placeholder' => Yii::t('app', '-- Choose --'),
						'dir' => 'rtl',
						'multiple' => true,
					],
					'pluginOptions' => [
						'allowClear' => true,
					],
				],
			],

			[
				'rptInputFields[mbrResearchID_Has]',
				'label' => 'پژوهش',
				'type' => FormBuilder::FIELD_CHECKBOXLIST,
				'data' => [
					0 => 'ندارد',
					1 => 'دارد',
				],
				'widgetOptions' => [
					'inline' => true,
				],
			],
			[
				'rptInputFields[mbrResearchID]',
				'label' => '',
				'visibleConditions' => [
					'rptInputFields[mbrResearchID_Has]' => ['js', "function() { return ({{conditionFieldValue}} == false); }()"],
				],
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'data' => ArrayHelper::map(BasicDefinitionModel::find()->where(['bdfType' => enuBasicDefinitionType::Research])->asArray()->noLimit()->all(), 'bdfID', 'bdfName'),
					'options' => [
						'placeholder' => Yii::t('app', '-- Choose --'),
						'dir' => 'rtl',
						'multiple' => true,
					],
					'pluginOptions' => [
						'allowClear' => true,
					],
				],
			],

			'<hr>',

			[
				'rptInputFields[mbrJob_Has]',
				'label' => 'شغل',
				'type' => FormBuilder::FIELD_CHECKBOXLIST,
				'data' => [
					0 => 'ندارد',
					1 => 'دارد',
				],
				'widgetOptions' => [
					'inline' => true,
				],
			],
			[
				'rptInputFields[mbrJob]',
				'label' => '',
				'visibleConditions' => [
					'rptInputFields[mbrJob_Has]' => ['js', "function() { return ({{conditionFieldValue}} == false); }()"],
				],
				'type' => FormBuilder::FIELD_TEXT,
			],
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
