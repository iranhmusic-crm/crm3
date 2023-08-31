<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use yii\web\JsExpression;
use shopack\base\common\helpers\ArrayHelper;
use shopack\base\frontend\widgets\Select2;
use shopack\base\frontend\widgets\DepDrop;
use shopack\base\common\helpers\Url;
use shopack\base\frontend\helpers\Html;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\frontend\widgets\ActiveForm;
use shopack\base\frontend\widgets\FormBuilder;
use iranhmusic\shopack\mha\frontend\common\models\SpecialtyModel;
?>

<div class='member-specialty-form'>
	<?php
		$form = ActiveForm::begin([
			'model' => $model,
		]);

		$builder = $form->getBuilder();

		// $builder->fields([
		// 	[
		// 		'mbrspcMemberID',
		// 		'type' => FormBuilder::FIELD_STATIC,
		// 		'staticValue' => $model->member->displayName(),
		// 	],
		// ]);

		$formatJs =<<<JS
var formatSpecialty = function(item) {
	if (item.loading)
		return 'در حال جستجو...'; //item.text;
	return '<div style="overflow:hidden;">' + item.name + '</div>';
};
var formatSpecialtySelection = function(item) {
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

		if (empty($model->mbrspcSpecialtyID))
			$initValueText = null;
		else {
			$specialtyModel = SpecialtyModel::findOne($model->mbrspcSpecialtyID);
			$initValueText = $specialtyModel->spcName;
		}

		$builder->fields([
			['mbrspcSpecialtyID',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					// 'data' => ArrayHelper::map(SpecialtyModel::find()->asArray()->noLimit()->all(), 'spcID', 'spcName'),
					'initValueText' => $initValueText,
					'value' => $model->mbrspcSpecialtyID,
					'pluginOptions' => [
						'allowClear' => false,
						'minimumInputLength' => 2, //qom, rey
						'ajax' => [
							'url' => Url::to(['/mha/specialty/select2-list']),
							'dataType' => 'json',
							'delay' => 50,
							'data' => new JsExpression('function(params) { return {q:params.term, page:params.page}; }'),
								'processResults' => new JsExpression($resultsJs),
								'cache' => true,
						],
						'escapeMarkup' => new JsExpression('function(markup) { return markup; }'),
						'templateResult' => new JsExpression('formatSpecialty'),
						'templateSelection' => new JsExpression('formatSpecialtySelection'),
					],
					'options' => [
						'placeholder' => '-- جستجو کنید --',
						'dir' => 'rtl',
						// 'multiple' => true,
					],
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
