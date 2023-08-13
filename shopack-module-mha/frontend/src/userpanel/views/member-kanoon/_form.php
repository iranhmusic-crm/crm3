<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use yii\web\JsExpression;
use shopack\base\frontend\widgets\Select2;
use shopack\base\common\helpers\ArrayHelper;
use shopack\base\common\helpers\Url;
use shopack\base\frontend\helpers\Html;
use shopack\base\frontend\widgets\ActiveForm;
use shopack\base\frontend\widgets\FormBuilder;
use iranhmusic\shopack\mha\frontend\common\models\KanoonModel;
use iranhmusic\shopack\mha\common\enums\enuKanoonMembershipDegree;
use iranhmusic\shopack\mha\common\enums\enuMemberKanoonStatus;
use iranhmusic\shopack\mha\frontend\common\models\MemberModel;
?>

<div class='member-kanoon-form'>
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
		// 		'mbrknnMemberID',
		// 		'type' => FormBuilder::FIELD_STATIC,
		// 		'staticValue' => $model->member->displayName(),
		// 	],
		// ]);

		$builder->fields([
			[
				'mbrknnKanoonID',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'data' => ArrayHelper::map(KanoonModel::find()->asArray()->all(), 'knnID', 'knnName'),
					'options' => [
						'placeholder' => Yii::t('app', '-- Choose --'),
						'dir' => 'rtl',
					],
				],
			],
			// [
			// 	'mbrknnStatus',
			// 	'type' => FormBuilder::FIELD_WIDGET,
			// 	'widget' => Select2::class,
			// 	'widgetOptions' => [
			// 		'data' => enuMemberKanoonStatus::getList(),
			// 		'options' => [
			// 			'placeholder' => Yii::t('app', '-- Choose --'),
			// 			'dir' => 'rtl',
			// 		],
			// 	],
			// ],
			// [
			// 	'mbrknnMembershipDegree',
			// 	'type' => FormBuilder::FIELD_WIDGET,
			// 	'widget' => Select2::class,
			// 	'widgetOptions' => [
			// 		'data' => enuKanoonMembershipDegree::getList(),
			// 		'options' => [
			// 			'placeholder' => Yii::t('app', '-- Choose --'),
			// 			'dir' => 'rtl',
			// 		],
			// 	],
			// 	'visibleConditions' => [
			// 		'mbrknnStatus' => enuMemberKanoonStatus::Accepted,
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
