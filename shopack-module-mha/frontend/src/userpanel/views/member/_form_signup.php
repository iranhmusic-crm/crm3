<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use yii\web\JsExpression;
use borales\extensions\phoneInput\PhoneInput;
use shopack\base\common\helpers\Json;
use shopack\base\common\helpers\Url;
use shopack\base\frontend\common\widgets\Select2;
use shopack\base\frontend\common\widgets\DepDrop;
use shopack\base\frontend\common\widgets\datetime\DatePicker;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\common\helpers\ArrayHelper;
use shopack\base\frontend\common\widgets\ActiveForm;
use shopack\base\frontend\common\widgets\FormBuilder;
use shopack\aaa\frontend\common\models\UserModel;
use shopack\aaa\common\enums\enuGender;
use iranhmusic\shopack\mha\common\enums\enuMemberStatus;
use iranhmusic\shopack\mha\frontend\common\models\KanoonModel;
use shopack\aaa\frontend\common\models\GeoCountryModel;
use iranhmusic\shopack\mha\common\enums\enuBasicDefinitionType;
use iranhmusic\shopack\mha\frontend\common\models\BasicDefinitionModel;
use shopack\aaa\frontend\common\widgets\form\GeoCityOrVillageChooseFormField;
use shopack\aaa\frontend\common\widgets\form\GeoCountryChooseFormField;
use shopack\aaa\frontend\common\widgets\form\GeoStateChooseFormField;
use shopack\aaa\frontend\common\widgets\form\GeoTownChooseFormField;

?>

<div class='member-form'>
	<?php
		$form = ActiveForm::begin([
			'model' => $model,
		]);

    $formName = $model->formName();
    $formNameLower = strtolower($formName);

		$builder = $form->getBuilder();

		$builder->fields([
			[
				'mbrUserID',
				'type' => FormBuilder::FIELD_STATIC,
				'staticValue' => $model->user->displayName('{fn} {ln} {em} {mob}'),
			],
		]);

		$builder->fields([
			[
				'@col' => 2,
				// 'vertical' => true,
			],
		]);

		//-----------------
		if ($model->mustSetUserInfo()) {
			if (empty($model->user->usrGender)) {
				$builder->fields([
					['@col' => 1],
					['usrGender',
						'type' => FormBuilder::FIELD_RADIOLIST,
						'data' => enuGender::listData(),
						'widgetOptions' => [
							'inline' => true,
						],
					],
					['@col' => 2],
				]);
			}
			if (empty($model->user->usrFirstName)) {
				$builder->fields([
					['usrFirstName']
				]);
			}
			if (empty($model->user->usrFirstName_en)) {
				$builder->fields([
					['usrFirstName_en',
						'widgetOptions' => [
							'class' => ['dir-ltr'],
						],
					]
				]);
			}
			if (empty($model->user->usrLastName)) {
				$builder->fields([
					['usrLastName']
				]);
			}
			if (empty($model->user->usrLastName_en)) {
				$builder->fields([
					['usrLastName_en',
						'widgetOptions' => [
							'class' => ['dir-ltr'],
						],
					]
				]);
			}
			if (empty($model->user->usrEmail)) {
				$builder->fields([
					['usrEmail',
						'type' => FormBuilder::FIELD_WIDGET,
						'widget' => \yii\widgets\MaskedInput::class,
						'widgetOptions' => [
							'options' => [
								'maxlength' => true,
								'style' => 'direction:ltr;',
							],
							'clientOptions' => [
								'alias' => 'email',
							],
						],
					]
				]);
			}
			if (empty($model->user->usrMobile)) {
				$builder->fields([
					['usrMobile',
						'widgetOptions' => [
							'class' => ['dir-ltr'],
						],
					]
				]);
			}
			if (empty($model->user->usrSSID)) {
				$builder->fields([
					['usrSSID']
				]);
			}
			if (empty($model->user->usrBirthDate)) {
				$builder->fields([
					['usrBirthDate']
				]);
			}

			if (empty($model->user->usrCountryID)
				|| empty($model->user->usrStateID)
				|| empty($model->user->usrCityOrVillageID)
			) {
				$builder->fields([
					GeoCountryChooseFormField::field($this, $model, 'usrCountryID', true, false),
					GeoStateChooseFormField::field($this, $model, 'usrStateID', true, false, 'usrCountryID'),
					GeoCityOrVillageChooseFormField::field($this, $model, 'usrCityOrVillageID', true, false, 'usrStateID'),
					// GeoTownChooseFormField::field($this, $model, 'usrTownID', true, false, 'usrCityOrVillageID'),
				]);
			}

			if (empty($model->user->usrHomeAddress)) {
				$builder->fields([
					['@col' => 1],
					['usrHomeAddress'],
					['@col' => 2],
				]);
			}
			if (empty($model->user->usrZipCode)) {
				$builder->fields([
					['usrZipCode']
				]);
			}

			$builder->fields([
				'<hr>',
			]);

			// $builder->fields([
			// 	['@reset-cols'],
			// ]);
		}

		//-----------------

		//-----------------

		//-----------------

		$loadingText = "<div class='text-center'>" . Yii::t('app', 'Loading...') . "</div>";

		$getParamsSchemaUrl = Url::to(['kanoon/params-schema']) . '?id=';
		$strKanoonParameters = '{}';
		if ($model->mbrknnParams !== null)
			$strKanoonParameters = Json::encode($model->mbrknnParams);

		$builder->fields([
			['@col' => 1],
			['kanoonID',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'data' => ArrayHelper::map(KanoonModel::find()->asArray()->noLimit()->all(), 'knnID', 'knnName'),
					'options' => [
						'placeholder' => Yii::t('app', '-- Choose --'),
						'dir' => 'rtl',
					],
					'pluginEvents' => [
						'select2:select' => "function(e) {
							createDynamicParamsFormUI($(this).val(), \"{$loadingText}\", '{$getParamsSchemaUrl}', '{$formNameLower}', 'mbrknnParams', '{$formName}', 'mbrknnParams', {$strKanoonParameters}, 'params-container', 2);
							return true;
						}",
					],
				],
			],
		]);

		if ($model->kanoonID) {
			$js = "createDynamicParamsFormUI('{$model->kanoonID}', \"{$loadingText}\", '{$getParamsSchemaUrl}', '{$formNameLower}', 'mbrknnParams', '{$formName}', 'mbrknnParams', {$strKanoonParameters}, 'params-container', 2);";

			$this->registerJs($js, \yii\web\View::POS_READY);
		}
	?>

	<?php $builder->beginField(); ?>
		<div id='params-container' class='row offset-md-1'></div>
	<?php $builder->endField(); ?>

	<?php
		$builder->fields([
			'<hr>',
			['@col' => 1],
			['mbrMusicExperiences'],
			['mbrMusicExperienceStartAt',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => DatePicker::class,
				'fieldOptions' => [
					'addon' => [
						'append' => [
							'content' => '<i class="far fa-calendar-alt"></i>',
						],
					],
				],
			],
			['@cols' => 2, 'vertical' => true],
			['mbrInstrumentID',
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
			['mbrSingID',
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
			['mbrResearchID',
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

			['@col-break'],
			['mbrArtDegree',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'data' => [
						'1' => 'درجه 1',
						'2' => 'درجه 2',
						'3' => 'درجه 3',
						'4' => 'درجه 4',
						'5' => 'درجه 5',
					],
					'options' => [
						'placeholder' => Yii::t('app', '-- Choose --'),
						'dir' => 'rtl',
					],
					'pluginOptions' => [
						'allowClear' => true,
					],
				],
			],
			['mbrHonarCreditCode'],
			['mbrJob'],

			['@cols' => 1],
			['mbrOwnOrgName'],

			['mbrArtHistory',
				'type' => FormBuilder::FIELD_TEXTAREA,
				'widgetOptions' => [
					'rows' => 4,
				],
			],
			['mbrMusicEducationHistory',
				'type' => FormBuilder::FIELD_TEXTAREA,
				'widgetOptions' => [
					'rows' => 4,
				],
			],
		]);
	?>

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
