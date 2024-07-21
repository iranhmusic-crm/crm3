<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\frontend\common\helpers\Html;
use shopack\base\frontend\common\widgets\ActiveForm;
use shopack\base\frontend\common\widgets\FormBuilder;
?>

<div class='member-group-form'>
	<?php
		$form = ActiveForm::begin([
			'model' => $model,
			'formConfig' => [
				'labelSpan' => 5,
			],
		]);

		$builder = $form->getBuilder();

		$builder->fields([
			// [
			// 	'mgpStatus',
			// 	'type' => FormBuilder::FIELD_RADIOLIST,
			// 	'data' => enuMemberGroupStatus::listData('form'),
			// 	'widgetOptions' => [
			// 		'inline' => true,
			// 	],
			// ],
			['mgpName'],
			[
				'mgpName',
				'type' => FormBuilder::FIELD_TEXT_MULTILANGUAGE,
				'fieldOptions' => [
					'I18NDataFieldName' => 'mgpI18NData',
					// 'generateNoLanguageField' => true,
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
