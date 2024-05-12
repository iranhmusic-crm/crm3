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
use shopack\base\common\accounting\enums\enuProductStatus;

// \shopack\base\frontend\common\DynamicParamsFormAsset::register($this);
?>

<div class='membership-card-product-form'>
	<?php
		$form = ActiveForm::begin([
			'model' => $model,
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
