<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use yii\web\JsExpression;
use shopack\base\common\helpers\Url;
use shopack\base\frontend\common\widgets\datetime\DatePicker;
use shopack\base\frontend\common\widgets\Select2;
use shopack\base\frontend\common\widgets\DepDrop;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\frontend\common\widgets\ActiveForm;
use shopack\base\frontend\common\widgets\FormBuilder;
use shopack\base\common\accounting\enums\enuSaleableStatus;

// \shopack\base\frontend\common\DynamicParamsFormAsset::register($this);
?>

<div class='membership-saleable-form'>
	<?php
		$form = ActiveForm::begin([
			'model' => $model,
			'formConfig' => [
				'labelSpan' => 4,
			],
		]);

		$builder = $form->getBuilder();

		$builder->fields([
			['slbProductID',
				'type' => FormBuilder::FIELD_STATIC,
				'staticValue' => $model->slbProductID . ' - ' . $model->product->prdName,
			],
			['slbStatus',
				'type' => FormBuilder::FIELD_RADIOLIST,
				'data' => enuSaleableStatus::listData('form'),
				'widgetOptions' => [
					'inline' => true,
				],
			],
			['slbName'],
			// ['slbCode'],
			// ['slbDesc'],
			['slbBasePrice',
				'fieldOptions' => [
					'addon' => [
						'append' => [
							'content' => 'تومان',
						],
					],
				],
				'widgetOptions' => [
					'style' => 'direction:ltr',
				],
			],
			['slbAvailableFromDate',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => DatePicker::class,
				'fieldOptions' => [
					'addon' => [
						'append' => [
							'content' => '<i class="far fa-calendar-alt"></i>',
						],
					],
				],
			],
			// ['slbAvailableToDate'],
			// ['slbPrivs'],
			// ['slbAdditives'],
			// ['slbProductCount'],
			// ['slbMaxSaleCountPerUser'],
			// ['slbInStockQty'],
			// ['slbOrderedQty'],
			// ['slbReturnedQty'],
			// ['slbVoucherTemplate'],
			// ['slbI18NData'],
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
