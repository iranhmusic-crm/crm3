<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use yii\web\JsExpression;
use shopack\base\frontend\common\widgets\Select2;
use shopack\base\common\helpers\ArrayHelper;
use shopack\base\common\helpers\Url;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\frontend\common\widgets\ActiveForm;
use shopack\base\frontend\common\widgets\FormBuilder;
use iranhmusic\shopack\mha\frontend\common\models\MembershipModel;
use iranhmusic\shopack\mha\common\enums\enuMemberMembershipStatus;
use iranhmusic\shopack\mha\frontend\common\models\MemberModel;
use iranhmusic\shopack\mha\frontend\common\widgets\form\MemberChooseFormField;
use shopack\base\frontend\common\widgets\datetime\DatePicker;
?>

<div class='membership-user-asset-form'>
	<?php
		$form = ActiveForm::begin([
			'model' => $model,
			'formConfig' => [
				'labelSpan' => 4,
			],
		]);

		$builder = $form->getBuilder();

		//from member view or side bar?
		if (empty($model->uasActorID)) {
			$builder->fields(MemberChooseFormField::field($this, $model, 'uasActorID'));
		} else {
			$builder->fields([
				[
					'uasActorID',
					'type' => FormBuilder::FIELD_STATIC,
					'staticValue' => $model->actor->displayName(),
				],
			]);
		}

		$builder->fields([
			'تاریخ شروع',
			'تاریخ پایان',
			'مدت',
			'فی',
			'کل',
			'چاپ کارت: فی',
			'ارسال: انتخاب',
			'جمع',
			'موجودی کیف پول',
			// [
			// 	'mbrshpMembershipID',
			// 	'type' => FormBuilder::FIELD_WIDGET,
			// 	'widget' => Select2::class,
			// 	'widgetOptions' => [
			// 		'data' => ArrayHelper::map(MembershipModel::find()->asArray()->noLimit()->all(), 'mshpID', 'mshpTitle'),
			// 		'options' => [
			// 			'placeholder' => Yii::t('app', '-- Choose --'),
			// 			'dir' => 'rtl',
			// 		],
			// 	],
			// ],
			// [
			// 	'mbrshpStartDate',
			// 	'type' => FormBuilder::FIELD_WIDGET,
			// 	'widget' => DatePicker::class,
			// 	'fieldOptions' => [
			// 		'addon' => [
			// 			'append' => [
			// 				'content' => '<i class="far fa-calendar-alt"></i>',
			// 			],
			// 		],
			// 	],
			// ],
			// 'mbrshpEndDate',
			// [
			// 	'mbrshpStatus',
			// 	'type' => FormBuilder::FIELD_WIDGET,
			// 	'widget' => Select2::class,
			// 	'widgetOptions' => [
			// 		'data' => enuMemberMembershipStatus::getList(),
			// 		'options' => [
			// 			'placeholder' => Yii::t('app', '-- Choose --'),
			// 			'dir' => 'rtl',
			// 		],
			// 	],
			// ],
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
