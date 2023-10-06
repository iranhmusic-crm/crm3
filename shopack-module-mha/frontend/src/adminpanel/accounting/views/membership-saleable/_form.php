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
use shopack\base\common\accounting\enums\enuSaleableStatus;

// \shopack\base\frontend\common\DynamicParamsFormAsset::register($this);
?>

<div class='membership-saleable-form'>
	<?php
		$form = ActiveForm::begin([
			'model' => $model,
		]);

		$builder = $form->getBuilder();

		$builder->fields([
			[
				'slbProductID',
				'type' => FormBuilder::FIELD_STATIC,
				'staticText' => $model->product->prdName,
			],
			[
				'slbStatus',
				'type' => FormBuilder::FIELD_RADIOLIST,
				'data' => enuSaleableStatus::listData('form'),
				'widgetOptions' => [
					'inline' => true,
				],
			],
			['slbName'],
			['slbCode'],
			// ['slbDesc'],
			// ['slbAvailableFromDate'],
			// ['slbAvailableToDate'],
			// ['slbPrivs'],
			// ['slbBasePrice'],
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
