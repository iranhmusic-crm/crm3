<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use iranhmusic\shopack\mha\frontend\common\widgets\grid\KanoonChooseFormField;
use yii\web\JsExpression;
use shopack\base\common\helpers\Url;
use shopack\base\frontend\common\widgets\datetime\DatePicker;
use shopack\base\frontend\common\widgets\Select2;
use shopack\base\frontend\common\widgets\DepDrop;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\frontend\common\widgets\ActiveForm;
use shopack\base\frontend\common\widgets\FormBuilder;
use shopack\base\common\accounting\enums\enuDiscountStatus;
use shopack\base\common\accounting\enums\enuAmountType;
use iranhmusic\shopack\mha\frontend\common\widgets\grid\MemberChooseFormField;
use iranhmusic\shopack\mha\frontend\common\widgets\grid\MemberGroupChooseFormField;
use iranhmusic\shopack\mha\frontend\common\widgets\grid\ProductChooseFormField;
use iranhmusic\shopack\mha\frontend\common\widgets\grid\SaleableChooseFormField;

// \shopack\base\frontend\common\DynamicParamsFormAsset::register($this);
?>

<div class='discount-form'>
	<?php
		$form = ActiveForm::begin([
			'model' => $model,
			'fieldConfig' => [
				'labelSpan' => 3,
			],
		]);

		$builder = $form->getBuilder();

		$builder->fields([
			[
				'dscStatus',
				'type' => FormBuilder::FIELD_RADIOLIST,
				'data' => enuDiscountStatus::listData('form'),
				'widgetOptions' => [
					'inline' => true,
				],
			],
			['dscName'],
			['dscCode'],

			['@cols' => 2],

			['@section', 'label' => 'محدودیت‌ها'],

			['dscValidFrom',
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
			['dscValidTo',
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

			['dscTotalMaxCount',
				'fieldOptions' => [
					'addon' => [
						'append' => [
							'content' => 'عدد',
						],
					],
				],
			],
			['dscTotalMaxPrice',
				'fieldOptions' => [
					'addon' => [
						'append' => [
							'content' => 'تومان',
						],
					],
				],
			],
			['dscPerUserMaxCount',
				'fieldOptions' => [
					'addon' => [
						'append' => [
							'content' => 'عدد',
						],
					],
				],
			],
			['dscPerUserMaxPrice',
				'fieldOptions' => [
					'addon' => [
						'append' => [
							'content' => 'تومان',
						],
					],
				],
			],

			['@reset-cols'],
			['@section', 'label' => Yii::t('aaa', 'Conditions')],
		]);

		$builder->fields(MemberChooseFormField::field($this, $model, 'dscTargetUserIDs', true, true));

		$builder->fields(ProductChooseFormField::field($this, $model, 'dscTargetProductIDs', true, true));

		$builder->fields(SaleableChooseFormField::field($this, $model, 'dscTargetSaleableIDs', true, true));

		$builder->fields([
			['dscSaleableBasedMultiplier'],
		]);

		$builder->fields(MemberGroupChooseFormField::field($this, $model, 'dscTargetMemberGroupIDs', true, true));

		$builder->fields(KanoonChooseFormField::field($this, $model, 'dscTargetKanoonIDs', true, true));

		$builder->fields([
			['dscTargetProductMhaTypes'],
		]);

		$builder->fields([
			['@section', 'label' => 'عملیات'],
			['@col' => 2, 'vertical' => true],

			['dscAmount',
				'widgetOptions' => [
					'maxlength' => true,
					'style' => 'direction:ltr',
				],
			],
			['dscAmountType',
				'type' => FormBuilder::FIELD_RADIOLIST,
				'data' => enuAmountType::listData(),
				'widgetOptions' => [
					'inline' => true,
				],
			],

			['@col-break'],
			['dscMaxAmount',
				'fieldOptions' => [
					'addon' => [
						'append' => [
							'content' => 'تومان',
							'options' => [
								'id' => 'dscMaxAmountUnit',
							],
						],
					],
				],
				'widgetOptions' => [
					'maxlength' => true,
					'style' => 'direction:ltr',
				],
			],
		]);

		$fieldID = Html::getInputId($model, 'dscAmountType');
		$js = "\$('#{$fieldID}').on('change', function(e) {
			val = \$('#{$fieldID} :checked').val();
			\$('#dscMaxAmountUnit').text(val == '$' ? 'درصد' : 'تومان');
			return true;
		});";
		if ($model->dscAmountType == enuAmountType::Price)
			$js .= "\$('#dscMaxAmountUnit').text('درصد');";
		$this->registerJs($js, \yii\web\View::POS_READY);
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
