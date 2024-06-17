<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\frontend\common\helpers\Html;
use shopack\base\frontend\common\widgets\ActiveForm;
use shopack\base\frontend\common\widgets\FormBuilder;

?>

<div class='membership-renew-form'>
	<?php
		$form = ActiveForm::begin([
			'model' => $model,
		]);

		$form->registerActiveHiddenInput($model, 'ofpID');

		$builder = $form->getBuilder();

		$yearsData = [];
		for ($i=1; $i<=$model->maxYears; $i++) {
			$yearsData[$i] = $i;
		}

		$builder->fields([
			[
				'startDate',
				'type' => FormBuilder::FIELD_STATIC,
				'staticFormat' => 'jalali',
			],
			[
				'years',
				'type' => FormBuilder::FIELD_RADIOLIST,
				'data' => $yearsData,
				'widgetOptions' => [
					'inline' => true,
				],
			],



			//saleable drop down



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
