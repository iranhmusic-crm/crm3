<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use yii\web\JsExpression;
use shopack\base\frontend\common\widgets\Select2;
use shopack\base\common\helpers\ArrayHelper;
use shopack\base\common\helpers\Url;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\frontend\common\widgets\ActiveForm;
use shopack\base\frontend\common\widgets\FormBuilder;
use iranhmusic\shopack\mha\frontend\common\models\MembershipModel;
use iranhmusic\shopack\mha\common\enums\enuMemberMembershipStatus;
use iranhmusic\shopack\mha\frontend\common\models\MemberModel;
use shopack\base\frontend\common\widgets\datetime\DatePicker;
?>

<div class='membership-user-asset-form'>
	<?php
		$form = ActiveForm::begin([
			'model' => $model,
			'formConfig' => [
				'labelSpan' => 4,
			],
		]);

		$builder = $form->getBuilder();

		//from member view or side bar?
		if (empty($model->uasActorID)) {
			$formatJs =<<<JS
var formatMember = function(item) {
	if (item.loading)
		return 'در حال جستجو...'; //item.text;
	return '<div style="overflow:hidden;">' + item.name + '</div>';
};
var formatMemberSelection = function(item) {
	if (item.text)
		return item.text;
	return item.name;
}
JS;
			$this->registerJs($formatJs, \yii\web\View::POS_HEAD);

			// script to parse the results into the format expected by Select2
			$resultsJs =<<<JS
function(data, params) {
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

			if (empty($model->uasActorID))
				$initValueText = null;
			else {
				$memberModel = MemberModel::findOne($model->uasActorID);
				$initValueText = $memberModel->displayName();
			}

			$builder->fields([
				[
					'uasActorID',
					'type' => FormBuilder::FIELD_WIDGET,
					'widget' => Select2::class,
					'widgetOptions' => [
						'initValueText' => $initValueText,
						'value' => $model->uasActorID,
						'pluginOptions' => [
							'allowClear' => false,
							'minimumInputLength' => 2, //qom, rey
							'ajax' => [
								'url' => Url::to(['/mha/member/select2-list']),
								'dataType' => 'json',
								'delay' => 50,
								'data' => new JsExpression('function(params) { return {q:params.term, page:params.page}; }'),
								'processResults' => new JsExpression($resultsJs),
								'cache' => true,
							],
							'escapeMarkup' => new JsExpression('function(markup) { return markup; }'),
							'templateResult' => new JsExpression('formatMember'),
							'templateSelection' => new JsExpression('formatMemberSelection'),
						],
						'options' => [
							'placeholder' => '-- جستجو کنید --',
							'dir' => 'rtl',
							// 'multiple' => true,
						],
					],
				],
			]);

		} else {
			$builder->fields([
				[
					'uasActorID',
					'type' => FormBuilder::FIELD_STATIC,
					'staticValue' => $model->member->displayName(),
				],
			]);
		}

		$builder->fields([
			// [
			// 	'mbrshpMembershipID',
			// 	'type' => FormBuilder::FIELD_WIDGET,
			// 	'widget' => Select2::class,
			// 	'widgetOptions' => [
			// 		'data' => ArrayHelper::map(MembershipModel::find()->asArray()->noLimit()->all(), 'mshpID', 'mshpTitle'),
			// 		'options' => [
			// 			'placeholder' => Yii::t('app', '-- Choose --'),
			// 			'dir' => 'rtl',
			// 		],
			// 	],
			// ],
			[
				'mbrshpStartDate',
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
			// 'mbrshpEndDate',
			// [
			// 	'mbrshpStatus',
			// 	'type' => FormBuilder::FIELD_WIDGET,
			// 	'widget' => Select2::class,
			// 	'widgetOptions' => [
			// 		'data' => enuMemberMembershipStatus::getList(),
			// 		'options' => [
			// 			'placeholder' => Yii::t('app', '-- Choose --'),
			// 			'dir' => 'rtl',
			// 		],
			// 	],
			// ],

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
