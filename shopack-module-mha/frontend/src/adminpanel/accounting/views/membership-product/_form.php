<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\frontend\common\helpers\Html;
use shopack\base\frontend\common\widgets\ActiveForm;
use shopack\base\frontend\common\widgets\FormBuilder;
use shopack\interface\accounting\common\enums\enuProductStatus;
?>

<div class='membership-product-form'>
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
				'prdStatus',
				'type' => FormBuilder::FIELD_RADIOLIST,
				'data' => enuProductStatus::listData('form'),
				'widgetOptions' => [
					'inline' => true,
				],
			],
			['prdName'],
			[
				'prdName',
				'type' => FormBuilder::FIELD_TEXT_MULTILANGUAGE,
				'fieldOptions' => [
					'I18NDataFieldName' => 'prdI18NData',
					// 'generateNoLanguageField' => true,
				],
			],
			// ['prdCode'],
			// ['prdDesc'],
			// ['prdValidFromDate'],
			// ['prdValidToDate'],
			// ['prdValidFromHour'],
			// ['prdValidToHour'],
			// ['prdDurationMinutes'],
			// ['prdStartAtFirstUse'],
			// ['prdPrivs'],
			// ['prdVAT'],
			// ['prdUnitID'],
			// ['prdQtyIsDecimal'],
			// ['prdInStockQty'],
			// ['prdOrderedQty'],
			// ['prdReturnedQty'],
			// ['prdI18NData'],
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
