<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use yii\web\JsExpression;
use borales\extensions\phoneInput\PhoneInput;
use shopack\base\common\helpers\Url;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\frontend\helpers\Html;
use shopack\base\frontend\widgets\ActiveForm;
use shopack\base\frontend\widgets\FormBuilder;
use shopack\base\frontend\widgets\Select2;
use shopack\base\frontend\widgets\DepDrop;
use shopack\base\frontend\widgets\datetime\DatePicker;
use shopack\aaa\common\enums\enuGender;
use shopack\aaa\frontend\common\models\UserModel;
use iranhmusic\shopack\mha\common\enums\enuMemberStatus;
?>

<div class='member-form'>
	<?php
		$form = ActiveForm::begin([
			'model' => $model,
		]);

		$builder = $form->getBuilder();

		if ($model->isNewRecord) {
			$formatJs =<<<JS
var formatUser = function(user)
{
	if (user.loading)
		return 'در حال جستجو...'; //user.text;
	return '<div style="overflow:hidden;">' + '<b>' + user.firstname + ' ' + user.lastname + '</b> - ' + user.email + '</div>';
};
var formatUserSelection = function(user)
{
	if (user.text)
		return user.text;
	return user.firstname + ' ' + user.lastname + ' - ' + user.email;
}
JS;
			$this->registerJs($formatJs, \yii\web\View::POS_HEAD);

			// script to parse the results into the format expected by Select2
			$resultsJs =<<<JS
function(data, params)
{
	if ((data == null) || (params == null))
		return;

	// params.page = params.page || 1;
	if (params.page == null)
		params.page = 0;
	return {
		results: data.items,
		pagination: {
			more: ((params.page + 1) * 20) < data.total_count
		}
	};
}
JS;
			if (!empty($model->mbrUserID))
			{
				$userModel = UserModel::findOne($model->mbrUserID);
				$userDesc = $userModel->usrFirstName . ' ' . $userModel->usrLastName . ' - ' . $userModel->usrEmail;
			} else
				$userDesc = null;

			$builder->fields([
				['mbrCreateNewUser',
					'type' => FormBuilder::FIELD_CHECKBOX,
				],
				//existing user:
				['mbrUserID',
					'visibleConditions' => [
						'mbrCreateNewUser' => 0,
					],
					'type' => FormBuilder::FIELD_WIDGET,
					'widget' => Select2::class,
					'widgetOptions' => [
						'initValueText' => $userDesc,
						'value' => $model->mbrUserID,
						'pluginOptions' => [
							'allowClear' => true,
							'minimumInputLength' => 3,
							'ajax' => [
								'url' => Url::to(['/aaa/user/select2-list']),
								'dataType' => 'json',
								'delay' => 50,
								'data' => new JsExpression('function(params) { return {q:params.term, page:params.page}; }'),
								'processResults' => new JsExpression($resultsJs),
								'cache' => true,
							],
							'escapeMarkup' => new JsExpression('function(markup) { return markup; }'),
							'templateResult' => new JsExpression('formatUser'),
							'templateSelection' => new JsExpression('formatUserSelection'),
						],
						'options' => [
							'placeholder' => '-- جستجو کنید --',
							'dir' => 'rtl',
							// 'multiple' => true,
						],
					],
				],
				//new user:
				['usrGender',
					'visibleConditions' => [
						'mbrCreateNewUser' => 1,
					],
					'type' => FormBuilder::FIELD_RADIOLIST,
					'data' => enuGender::listData(),
					'widgetOptions' => [
						'inline' => true,
					],
				],
				['@col' => 2],
				['usrFirstName',
					'visibleConditions' => [
						'mbrCreateNewUser' => 1,
					],
				],
				['usrFirstName_en',
					'visibleConditions' => [
						'mbrCreateNewUser' => 1,
					],
					'widgetOptions' => [
						'class' => 'latin-text',
					],
				],
				['usrLastName',
					'visibleConditions' => [
						'mbrCreateNewUser' => 1,
					],
				],
				['usrLastName_en',
					'visibleConditions' => [
						'mbrCreateNewUser' => 1,
					],
					'widgetOptions' => [
						'class' => 'latin-text',
					],
				],
				['@reset-cols'],
				['usrSSID',
					'visibleConditions' => [
						'mbrCreateNewUser' => 1,
					],
				],
				['usrMobile',
					'visibleConditions' => [
						'mbrCreateNewUser' => 1,
					],
					'type' => FormBuilder::FIELD_WIDGET,
					'widget' => PhoneInput::class,
					'widgetOptions' => [
						'jsOptions' => [
							'nationalMode' => false,
							'preferredCountries' => ['ir'], //, 'us'],
							'excludeCountries' => ['il'],
						],
						'options' => [
							'style' => 'direction:ltr',
						],
					],
				],
				['usrEmail',
					'visibleConditions' => [
						'mbrCreateNewUser' => 1,
					],
					'widgetOptions' => [
						'class' => 'latin-text',
					],
				],
				//
				['@static' => '<hr>'],
			]);
		} else {
			//edit mode
		}

		$builder->fields([
			// ['mbrStatus',
			// 	'type' => FormBuilder::FIELD_RADIOLIST,
			// 	'data' => enuMemberStatus::listData($model->isNewRecord ? 'create-form' : 'update-form'),
			// 	'widgetOptions' => [
			// 		'inline' => true,
			// 	],
			// ],
			// ['mbrRegisterCode'],
			// ['mbrAcceptedAt'],
			['mbrMusicExperiences'],
			['mbrMusicExperienceStartAt',  //Y/M/D
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
