<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use yii\web\JsExpression;
use shopack\base\common\helpers\Url;
use shopack\base\frontend\common\widgets\Select2;
use shopack\base\frontend\common\widgets\DepDrop;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\frontend\common\widgets\ActiveForm;
use shopack\base\frontend\common\widgets\FormBuilder;
use iranhmusic\shopack\mha\common\enums\enuKanoonStatus;
use iranhmusic\shopack\mha\common\enums\enuBasicDefinitionType;
?>

<div class='kanoon-form'>
	<?php
		$form = ActiveForm::begin([
			'model' => $model,
			'formConfig' => [
				'labelSpan' => 4,
			],
		]);

		$builder = $form->getBuilder();

		$fildTypes = [
			'text' => 'متن',
		];
		$mhaList = enuBasicDefinitionType::getList();
		foreach($mhaList as $k => $v) {
			$fildTypes['mha:' . $k] = $v;
		}

		$builder->fields([
			['knnStatus',
				'type' => FormBuilder::FIELD_RADIOLIST,
				'data' => enuKanoonStatus::listData('form'),
				'widgetOptions' => [
					'inline' => true,
				],
			],
			['knnName'],
			['knnNameEn'],
			['knnDescFieldType',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'data' => $fildTypes,
					'options' => [
						'placeholder' => Yii::t('app', '-- Choose --'),
						'dir' => 'rtl',
					],
					'pluginOptions' => [
						'allowClear' => true,
					],
				],
			],
			['knnDescFieldLabel',
				'visibleConditions' => [
					'knnDescFieldType' => ['!=', ''],
				],
			],
			['@static' => '<hr>'],
		]);

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
				[
				'knnPresidentMemberID',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'initValueText' => $model->knnPresidentMemberID ? $model->president->displayName() : null,
					'value' => $model->knnPresidentMemberID,
					'pluginOptions' => [
						'allowClear' => true,
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
			[
				'knnVicePresidentMemberID',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'initValueText' => $model->knnVicePresidentMemberID ? $model->vicePresident->displayName() : null,
					'value' => $model->knnVicePresidentMemberID,
					'pluginOptions' => [
						'allowClear' => true,
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
			[
				'knnOzv1MemberID',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'initValueText' => $model->knnOzv1MemberID ? $model->ozv1->displayName() : null,
					'value' => $model->knnOzv1MemberID,
					'pluginOptions' => [
						'allowClear' => true,
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
			[
				'knnOzv2MemberID',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'initValueText' => $model->knnOzv2MemberID ? $model->ozv2->displayName() : null,
					'value' => $model->knnOzv2MemberID,
					'pluginOptions' => [
						'allowClear' => true,
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
			[
				'knnOzv3MemberID',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'initValueText' => $model->knnOzv3MemberID ? $model->ozv3->displayName() : null,
					'value' => $model->knnOzv3MemberID,
					'pluginOptions' => [
						'allowClear' => true,
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
			[
				'knnWardenMemberID',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'initValueText' => $model->knnWardenMemberID ? $model->warden->displayName() : null,
					'value' => $model->knnWardenMemberID,
					'pluginOptions' => [
						'allowClear' => true,
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
			[
				'knnTalkerMemberID',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'initValueText' => $model->knnTalkerMemberID ? $model->talker->displayName() : null,
					'value' => $model->knnTalkerMemberID,
					'pluginOptions' => [
						'allowClear' => true,
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
