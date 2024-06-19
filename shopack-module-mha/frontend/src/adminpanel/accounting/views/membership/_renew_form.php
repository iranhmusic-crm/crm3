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
				'labelSpan' => 4,
			],
		]);

		$form->registerActiveHiddenInput($model, 'ofpID');

		$builder = $form->getBuilder();

		$memberDisplayName = array_filter([
			'[عضویت: ' . ($this->mbrRegisterCode ?? 'ندارد') . ']',
			trim(($model->memberModel['usrFirstName'] ?? '')
				. ' '
				. ($model->memberModel['usrLastName'] ?? '')
			),
			empty($model->memberModel['usrEmail']) ? null : "<span class='d-inline-block dir-ltr'>" . $model->memberModel['usrEmail'] . "</span>",
			empty($model->memberModel['usrMobile']) ? null : Yii::$app->formatter->asPhone($model->memberModel['usrMobile']),
		]);

		$builder->fields([
			[
				'memberID',
				'type' => FormBuilder::FIELD_STATIC,
				'staticValue' => implode(' - ' , $memberDisplayName),
			],
		]);

		if (empty($model->offlinePaymentModel) == false) {
			$builder->fields([
				[
					'ofpID',
					'type' => FormBuilder::FIELD_STATIC,
					'staticValue' => $model->offlinePaymentModel['ofpID'] . ' - تاریخ پرداخت: ' . Yii::$app->formatter->asJalali($model->offlinePaymentModel['ofpPayDate'])
					 	. ' - مبلغ: ' . Yii::$app->formatter->asToman($model->offlinePaymentModel['ofpAmount']),
				],
			]);
		}

		$builder->fields(['<hr>']);

		$fnFormatSaleable = function($saleableModel) {
			$text = $saleableModel['slbName']
				. ' ('
				. Yii::$app->formatter->asToman($saleableModel['discountedBasePrice']);

			if ($saleableModel['discountAmount'] > 0) {
				$text .= " - تخفیف: " . Yii::$app->formatter->asToman($saleableModel['discountAmount']);

				// $text .= "<span class='text-decoration-line-through'>"
				// 	. Yii::$app->formatter->asToman($saleableModel['slbBasePrice'])
				// 	. "</span> ";
			}

			$text .= ')';

			$text .= ' - قابل ارائه از: '
				. Yii::$app->formatter->asJalali($saleableModel['slbAvailableFromDate']);

			return $text;
		};

		//membership saleables
		$saleablesData = [
			0 => 'بدون تمدید عضویت',
		];
		foreach ($model->membershipSaleableModels as $saleableModel) {
			$saleablesData += [
				$saleableModel['slbID'] => $fnFormatSaleable($saleableModel)
			];
		}

		$builder->fields([
			[
				'membershipSaleableID',
				'type' => FormBuilder::FIELD_RADIOLIST,
				'data' => $saleablesData,
				'widgetOptions' => [
					'inline' => false,
				],
			]
		]);

		$builder->fields([
			[
				'startDate',
				'type' => FormBuilder::FIELD_STATIC,
				'staticFormat' => 'jalali',
				'visibleConditions' => [
					'membershipSaleableID' => ['!=', 0],
				],
			],
		]);

		$yearsData = [];
		for ($i=1; $i<=$model->maxYears; $i++) {
			$yearsData[$i] = $i . ' سال';
		}

		$builder->fields([
			[
				'years',
				'type' => FormBuilder::FIELD_RADIOLIST,
				'data' => $yearsData,
				'widgetOptions' => [
					'inline' => true,
				],
				'visibleConditions' => [
					'membershipSaleableID' => ['!=', 0],
				],
			],
		]);

		$builder->fields(['<hr>']);

		//membership card saleables
		$saleablesData = [
			0 => 'بدون چاپ کارت',
		];
		foreach ($model->membershipCardSaleableModels as $saleableModel) {
			$saleablesData += [
				$saleableModel['slbID'] => $fnFormatSaleable($saleableModel)
			];
		}

		$builder->fields([
			[
				'membershipCardSaleableID',
				'type' => FormBuilder::FIELD_RADIOLIST,
				'data' => $saleablesData,
				'widgetOptions' => [
					'inline' => false,
				],
			]
		]);
	?>

	<?php $builder->beginFooter(); ?>
		<div class="card-footer">
			<div class="float-end">
				<?= Html::activeSubmitButton($model, 'ایجاد صورتحساب تمدید عضویت') ?>
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
