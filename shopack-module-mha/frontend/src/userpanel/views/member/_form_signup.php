<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use yii\web\JsExpression;
use borales\extensions\phoneInput\PhoneInput;
use shopack\base\common\helpers\Url;
use shopack\base\frontend\widgets\Select2;
use shopack\base\frontend\widgets\DepDrop;
use shopack\base\frontend\widgets\datetime\DatePicker;
use shopack\base\frontend\helpers\Html;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\common\helpers\ArrayHelper;
use shopack\base\frontend\widgets\ActiveForm;
use shopack\base\frontend\widgets\FormBuilder;
use shopack\aaa\frontend\common\models\UserModel;
use shopack\aaa\common\enums\enuGender;
use iranhmusic\shopack\mha\common\enums\enuMemberStatus;
use iranhmusic\shopack\mha\frontend\common\models\KanoonModel;
use shopack\aaa\frontend\common\models\GeoCountryModel;
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
					['usrCountryID',
						'type' => FormBuilder::FIELD_WIDGET,
						'widget' => Select2::class,
						'widgetOptions' => [
							'data' => ArrayHelper::map(GeoCountryModel::find()->asArray()->all(), 'cntrID', 'cntrName'),
							'options' => [
								'placeholder' => Yii::t('app', '-- Choose --'),
								'dir' => 'rtl',
							],
							'pluginOptions' => [
								'allowClear' => true,
							],
						],
					],
					['usrStateID',
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
								'depends' => ["{$formNameLower}-usrcountryid"],
								'initialize' => true,
								// 'initDepends' => ["{$formName}-usrcountryid"],
								'url' => Url::to(['/aaa/geo-state/depdrop-list', 'sel' => $model->usrStateID]),
								'loadingText' => Yii::t('app', 'Loading...'),
							],
						],
					],
					['usrCityOrVillageID',
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
								'depends' => ["{$formNameLower}-usrstateid"],
								'initialize' => true,
								// 'initDepends' => ["{$formName}-usrcountryid", "{$formName}-usrstateid"],
								'url' => Url::to(['/aaa/geo-city-or-village/depdrop-list', 'sel' => $model->usrCityOrVillageID]),
								'loadingText' => Yii::t('app', 'Loading...'),
							],
						],
					],
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
			$strKanoonParameters = json_encode($model->mbrknnParams);

		$builder->fields([
			['@col' => 1],
			['kanoonID',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'data' => ArrayHelper::map(KanoonModel::find()->asArray()->all(), 'knnID', 'knnName'),
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
			['@col' => 2],
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
			['mbrArtHistory'],
			['mbrMusicEducationHistory'],
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
