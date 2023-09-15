<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use yii\web\JsExpression;
use shopack\base\common\helpers\Json;
use shopack\base\common\helpers\Url;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\frontend\common\widgets\Select2;
use shopack\base\frontend\common\widgets\DepDrop;
use shopack\base\frontend\common\widgets\ActiveForm;
use shopack\base\frontend\common\widgets\FormBuilder;
use iranhmusic\shopack\mha\frontend\common\models\SpecialtyModel;
use shopack\base\common\helpers\ArrayHelper;
use iranhmusic\shopack\mha\frontend\common\models\MemberModel;
?>

<div class='member-specialty-form'>
	<?php
		$form = ActiveForm::begin([
			'model' => $model,
		]);

    $formName = $model->formName();
    $formNameLower = strtolower($formName);

		$builder = $form->getBuilder();

		//from member view or side bar?
		if (empty($model->mbrspcMemberID)) {
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

			if (empty($model->mbrspcMemberID))
				$initValueText = null;
			else {
				$memberModel = MemberModel::findOne($model->mbrspcMemberID);
				$initValueText = $memberModel->displayName();
			}

			$builder->fields([
				[
					'mbrspcMemberID',
					'type' => FormBuilder::FIELD_WIDGET,
					'widget' => Select2::class,
					'widgetOptions' => [
						'initValueText' => $initValueText,
						'value' => $model->mbrspcMemberID,
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
					'mbrspcMemberID',
					'type' => FormBuilder::FIELD_STATIC,
					'staticValue' => $model->member->displayName(),
				],
			]);
		}

		$loadingText = "<div class='text-center'>" . Yii::t('app', 'Loading...') . "</div>";

		$getParamsSchemaUrl = Url::to(['specialty/params-schema']) . '?id=';
		$strSpecialtyParameters = '{}';
		if ($model->mbrspcDesc !== null)
			$strSpecialtyParameters = Json::encode($model->mbrspcDesc);

		$builder->fields([
			['mbrspcSpecialtyID',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'data' => ArrayHelper::map($model->form_specialties, 'id', 'name'),
					// 'initValueText' => $initValueText,
					// 'value' => $model->mbrspcSpecialtyID,
					// 'pluginOptions' => [
					// 	'allowClear' => false,
					// 	'minimumInputLength' => 2, //qom, rey
					// 	'ajax' => [
					// 		'url' => Url::to(['/mha/specialty/select2-list']),
					// 		'dataType' => 'json',
					// 		'delay' => 50,
					// 		'data' => new JsExpression('function(params) { return {q:params.term, page:params.page}; }'),
					// 			'processResults' => new JsExpression($resultsJs),
					// 			'cache' => true,
					// 	],
					// 	'escapeMarkup' => new JsExpression('function(markup) { return markup; }'),
					// 	'templateResult' => new JsExpression('formatSpecialty'),
					// 	'templateSelection' => new JsExpression('formatSpecialtySelection'),
					// ],
					'options' => [
						'placeholder' => '-- جستجو کنید --',
						'dir' => 'rtl',
						// 'multiple' => true,
					],
					'pluginEvents' => [
						'select2:select' => "function(e) {
							createDynamicParamsFormUI($(this).val(), \"{$loadingText}\", '{$getParamsSchemaUrl}', '{$formNameLower}', 'mbrspcdesc', '{$formName}', 'mbrspcDesc', {$strSpecialtyParameters}, 'params-container', 3);
							return true;
						}",
					],
				],
			],
		]);

		if ($model->mbrspcSpecialtyID) {
			// $js = "$('#{$formNameLower}-mbrspcspecialtyid').trigger('select2:select');";
			$js = "createDynamicParamsFormUI('{$model->mbrspcSpecialtyID}', \"{$loadingText}\", '{$getParamsSchemaUrl}', '{$formNameLower}', 'mbrspcdesc', '{$formName}', 'mbrspcDesc', {$strSpecialtyParameters}, 'params-container', 3);";

			$this->registerJs($js, \yii\web\View::POS_READY);
		}
	?>

	<?php $builder->beginField(); ?>
		<div id='params-container' class='row'></div>
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
