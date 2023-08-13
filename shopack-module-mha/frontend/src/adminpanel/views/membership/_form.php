<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use yii\web\JsExpression;
use shopack\base\common\helpers\Url;
use shopack\base\frontend\widgets\datetime\DatePicker;
use shopack\base\frontend\widgets\Select2;
use shopack\base\frontend\widgets\DepDrop;
use shopack\base\frontend\helpers\Html;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\frontend\widgets\ActiveForm;
use shopack\base\frontend\widgets\FormBuilder;
use iranhmusic\shopack\mha\common\enums\enuMembershipStatus;
?>

<div class='membership-form'>
	<?php
		$form = ActiveForm::begin([
			'model' => $model,
			'formConfig' => [
				'labelSpan' => 4,
			],
		]);

		$builder = $form->getBuilder();

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

		$builder->fields([
			['mshpStatus',
				'type' => FormBuilder::FIELD_RADIOLIST,
				'data' => enuMembershipStatus::listData('form'),
				'widgetOptions' => [
					'inline' => true,
				],
			],
			[
				'mshpTitle',
			],
			[
				'mshpStartFrom',
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
			[
				'mshpYearlyPrice',
				'fieldOptions' => [
					'addon' => [
						'append' => [
							'content' => 'تومان',
						],
					],
				],
				'widgetOptions' => [
					'style' => 'direction:ltr',
				],
			],

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
