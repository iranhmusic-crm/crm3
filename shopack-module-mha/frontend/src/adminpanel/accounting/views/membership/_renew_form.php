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
			'formConfig' => [
				'labelSpan' => 3,
			],
		]);

		$form->registerActiveHiddenInput($model, 'ofpID');

		$builder = $form->getBuilder();

		$memberDisplayName = array_filter([
			'[عضویت: ' . ($this->mbrRegisterCode ?? 'ندارد') . ']',
			$model->memberModel['usrFirstName'],
			$model->memberModel['usrLastName'],
			empty($model->memberModel['usrEmail']) ? null : "<span class='d-inline-block dir-ltr'>" . $model->memberModel['usrEmail'] . "</span>",
			empty($model->memberModel['usrMobile']) ? null : Yii::$app->formatter->asPhone($model->memberModel['usrMobile']),
		]);

		$builder->fields([
			[
				'memberID',
				'type' => FormBuilder::FIELD_STATIC,
				'staticValue' => implode(' ' , $memberDisplayName),
			],
		]);

		if (empty($model->offlinePaymentModel) == false) {
			$builder->fields([
				[
					'ofpID',
					'label' => 'پرداخت آفلاین',
					'type' => FormBuilder::FIELD_STATIC,
					'staticValue' => 'تاریخ پرداخت: ' . Yii::$app->formatter->asJalali($model->offlinePaymentModel['ofpPayDate'])
					 	. ' - مبلغ: ' . Yii::$app->formatter->asToman($model->offlinePaymentModel['ofpAmount']),
				],
			]);
		}

		$builder->fields(['<hr>']);

		//saleables data
		$saleablesData = [];
		foreach ($model->saleableModels as $saleableModel) {
			$saleablesData += [
				$saleableModel['slbID'] => $saleableModel['slbName']
					. ' (' . Yii::$app->formatter->asToman($saleableModel['discountedBasePrice']) . ')'
					. ' - قابل ارائه از: '
					. Yii::$app->formatter->asJalali($saleableModel['slbAvailableFromDate'])
			];
		}

		$builder->fields([
			[
				'saleableID',
				'label' => 'حق عضویت',
				'type' => FormBuilder::FIELD_RADIOLIST,
				'data' => $saleablesData,
				'widgetOptions' => [
					'inline' => true,
				],
			]
		]);

		$builder->fields([
			[
				'startDate',
				'type' => FormBuilder::FIELD_STATIC,
				'staticFormat' => 'jalali',
			],
		]);

		$yearsData = [];
		for ($i=1; $i<=$model->maxYears; $i++) {
			$yearsData[$i] = $i . ' سال';
		}

		$builder->fields([
			[
				'years',
				'label' => 'طول دوره',
				'type' => FormBuilder::FIELD_RADIOLIST,
				'data' => $yearsData,
				'widgetOptions' => [
					'inline' => true,
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
