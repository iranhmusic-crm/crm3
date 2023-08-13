<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use yii\web\JsExpression;
use shopack\base\frontend\widgets\Select2;
use shopack\base\frontend\widgets\DepDrop;
use shopack\base\frontend\widgets\datetime\DatePicker;
use shopack\base\common\helpers\Url;
use shopack\base\frontend\helpers\Html;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\frontend\widgets\ActiveForm;
use shopack\base\frontend\widgets\FormBuilder;
use iranhmusic\shopack\mha\frontend\common\models\MasterInsuranceModel;
use iranhmusic\shopack\mha\frontend\common\models\MasterInsurerTypeModel;
?>

<div class='member-master-insurance-form'>
	<?php
		$form = ActiveForm::begin([
			'model' => $model,
			'formConfig' => [
				'labelSpan' => 4,
			],
		]);

		$builder = $form->getBuilder();

		// $builder->fields([
		// 	[
		// 		'mbrminshstMemberID',
		// 		'type' => FormBuilder::FIELD_STATIC,
		// 		'staticValue' => $model->member->displayName(),
		// 	],
		// ]);

		$list = [];
    $models = MasterInsurerTypeModel::find()->all();
		foreach ($models as $model) {
			$list[$model->minstypID] = $model->masterInsurer->minsName . ' - ' . $model->minstypName;
		}

		$builder->fields([
			[
				'mbrminshstMasterInsTypeID',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'data' => $list,
					'options' => [
						'placeholder' => Yii::t('app', '-- Choose --'),
						'dir' => 'rtl',
					],
				]
			],
			[
				'mbrminshstSubstation',
			],
			[
				'mbrminshstStartDate',
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
			[
				'mbrminshstEndDate',
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
			[
				'mbrminshstInsuranceCode',
			],
			[
				'mbrminshstCoCode',
			],
			[
				'mbrminshstCoName',
			],
			[
				'mbrminshstIssuanceDate',
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

		]);
	?>

	<?php $builder->beginField(); ?>
		<div id='params-container' class='row offset-md-2'></div>
	<?php $builder->endField(); ?>

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
