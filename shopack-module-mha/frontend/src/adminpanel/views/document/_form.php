<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use yii\web\JsExpression;
use shopack\base\common\helpers\Url;
use shopack\base\frontend\common\widgets\Select2;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\frontend\common\widgets\ActiveForm;
use shopack\base\frontend\common\widgets\FormBuilder;
use iranhmusic\shopack\mha\common\enums\enuDocumentType;
use iranhmusic\shopack\mha\common\enums\enuDocumentStatus;
use shopack\base\common\helpers\JsonSchema;
use shopack\base\frontend\common\widgets\JsonTableGrid;

?>

<div class='document-form'>
	<?php
		$form = ActiveForm::begin([
			'model' => $model,
			'formConfig' => [
				'labelSpan' => 4,
			],
		]);

		$builder = $form->getBuilder();

		$builder->fields([
			[
				'docStatus',
				'type' => FormBuilder::FIELD_RADIOLIST,
				'data' => enuDocumentStatus::listData('form'),
				'widgetOptions' => [
					'inline' => true,
				],
			],
			[
				'docType',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'data' => enuDocumentType::listData(),
					'options' => [
						'placeholder' => Yii::t('app', '-- Choose --'),
						'dir' => 'rtl',
					],
				]
			],
			['docName'],
		]);

		$builder->fields([
			[
				'docExtraParamsSchema',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => JsonTableGrid::class,
				// 'widgetOptions' => [
				// 	'jsonSchema' => $columnsInfo['docExtraParamsSchema']['jsonSchema']['fields'],
				// ],
			],
		]);

		// if (empty($docExtraParamsSchema_jsonSchema_fields) == false) {
		// 	$builder->fields([
		// 		['@cols' => count($docExtraParamsSchema_jsonSchema_fields)],
		// 	]);

		// 	foreach ($docExtraParamsSchema_jsonSchema_fields as $field) {
		// 		$f = [
		// 			"docExtraParamsSchema[values][{$field[0]}]",
		// 		];

		// 		if (isset($field['label'])) {
		// 			if (is_array($field['label'])) {
		// 				$cat = array_shift($field['label']);
		// 				$msg = array_shift($field['label']);
		// 				$f['label'] = Yii::t($cat, $msg, $field['label']);
		// 			} else
		// 				$f['label'] = Yii::t('mha', $field['label']);
		// 		} else
		// 			$f['label'] = $field[0];

		// 		if (isset($field['type'])) {
		// 			switch ($field['type']) {
		// 				case JsonSchema::TYPE_int:
		// 				case jsonSchema::TYPE_string:
		// 					break;

		// 				case jsonSchema::TYPE_boolean:
		// 					$f += [
		// 						'type' => FormBuilder::FIELD_CHECKBOX,
		// 						'widgetOptions' => [[], true],
		// 					];
		// 					break;
		// 			}
		// 		}

		// 		$builder->fields($f);
		// 	}

		// 	$builder->fields([
		// 		['@reset-cols'],
		// 	]);

		// }
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
