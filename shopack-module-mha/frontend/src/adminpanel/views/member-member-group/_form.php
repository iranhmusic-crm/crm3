<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use yii\web\JsExpression;
use shopack\base\common\helpers\Url;
use shopack\base\common\helpers\ArrayHelper;
use shopack\base\frontend\common\widgets\Select2;
use shopack\base\frontend\common\widgets\DepDrop;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\frontend\common\widgets\ActiveForm;
use shopack\base\frontend\common\widgets\FormBuilder;
// use shopack\aaa\common\enums\enuMemberMemberGroupStatus;
use iranhmusic\shopack\mha\frontend\common\models\MemberModel;
use iranhmusic\shopack\mha\frontend\common\models\MemberGroupModel;
use iranhmusic\shopack\mha\frontend\common\widgets\grid\MemberChooseFormField;
use shopack\base\frontend\common\widgets\datetime\DatePicker;

// \shopack\base\frontend\common\DynamicParamsFormAsset::register($this);
?>

<div class='member-member-group-form'>
	<?php
		$form = ActiveForm::begin([
			'model' => $model,
			'formConfig' => [
				'labelSpan' => 4,
			],
		]);

		$builder = $form->getBuilder();

		// $builder->fields([
			// [
			// 	'mbrmgpStatus',
			// 	'type' => FormBuilder::FIELD_RADIOLIST,
			// 	'data' => enuMemberMemberGroupStatus::listData('form'),
			// 	'widgetOptions' => [
			// 		'inline' => true,
			// 	],
			// ],
		// ]);

		//from member view or side bar?
		if (empty($model->mbrmgpMemberID)) {
			$builder->fields(MemberChooseFormField::field($this, $model, 'mbrmgpMemberID', false));
		} else {
			$builder->fields([
				[
					'mbrmgpMemberID',
					'type' => FormBuilder::FIELD_STATIC,
					'staticValue' => $model->member->displayName(),
				],
			]);
		}

		$builder->fields([
			['mbrmgpMemberGroupID',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'data' => ArrayHelper::map(MemberGroupModel::find()->asArray()->noLimit()->all(), 'mgpID', 'mgpName'),
					'options' => [
						'placeholder' => Yii::t('app', '-- Choose --'),
						'dir' => 'rtl',
					],
				],
			],
			['mbrmgpStartAt',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => DatePicker::class,
				'fieldOptions' => [
					'addon' => [
						'append' => [
							'content' => '<i class="far fa-calendar-alt"></i>',
						],
					],
				],
				'widgetOptions' => [
					'withTime' => true,
				],
			],
			['mbrmgpEndAt',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => DatePicker::class,
				'fieldOptions' => [
					'addon' => [
						'append' => [
							'content' => '<i class="far fa-calendar-alt"></i>',
						],
					],
				],
				'widgetOptions' => [
					'withTime' => true,
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
