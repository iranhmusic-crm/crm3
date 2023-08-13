<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\frontend\helpers\Html;
use shopack\base\frontend\widgets\ActiveForm;
use shopack\base\frontend\widgets\FormBuilder;
use shopack\base\frontend\widgets\datetime\DatePicker;
?>

<div class='member-membership-form'>
	<?php
		$form = ActiveForm::begin([
			'model' => $model,
			'formConfig' => [
				'labelSpan' => 4,
			],
			// 'modalDoneScript_OK' => "window.localStorage.setItem('basket', result.basketdata);"
		]);

		$builder = $form->getBuilder();

		$builder->fields([
			[
				'startDate',
				'type' => FormBuilder::FIELD_STATIC,
				'staticFormat' => 'jalali',
				// 'staticValue' => Yii::$app->formatter->asJalali($model->mbrshpStartDate),
			],
			[
				'endDate',
				'type' => FormBuilder::FIELD_STATIC,
				'staticFormat' => 'jalali',
				// 'staticValue' => Yii::$app->formatter->asJalali($model->mbrshpEndDate),
			],
			[
				'years',
				'type' => FormBuilder::FIELD_STATIC,
				'staticFormat' => 'decimal',
			],
			[
				'unitPrice',
				'type' => FormBuilder::FIELD_STATIC,
				'staticFormat' => 'decimal',
			],
			[
				'totalPrice',
				'type' => FormBuilder::FIELD_STATIC,
				'staticFormat' => 'decimal', //['currency', 'IRT'],
			],
			// [
			// 	'saleableID',
			// 	'type' => FormBuilder::FIELD_STATIC,
			// ],
		]);

		// $builder->fields([
			// [
			// 	'mbrshpStartDate',
			// 	'type' => FormBuilder::FIELD_WIDGET,
			// 	'widget' => DatePicker::class,
			// 	'fieldOptions' => [
			// 		'addon' => [
			// 			'append' => [
			// 				'content' => '<i class="far fa-calendar-alt"></i>',
			// 			],
			// 		],
			// 	],
			// ],
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
		// ]);
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
