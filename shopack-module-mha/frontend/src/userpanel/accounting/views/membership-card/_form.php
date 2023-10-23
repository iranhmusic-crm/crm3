<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\frontend\common\helpers\Html;
use shopack\base\frontend\common\widgets\ActiveForm;
use shopack\base\frontend\common\widgets\FormBuilder;
use shopack\base\frontend\common\widgets\datetime\DatePicker;
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
				'membershipUserAssetID',
				'type' => FormBuilder::FIELD_STATIC,
				'staticValue' => $model->saleableModel['slbName'],
			],
			[
				'price',
				'type' => FormBuilder::FIELD_STATIC,
				'staticFormat' => 'toman', //['currency', 'IRT'],
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
