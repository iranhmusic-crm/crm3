<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\frontend\widgets\Select2;
use shopack\base\common\helpers\ArrayHelper;
use shopack\base\frontend\helpers\Html;
use shopack\base\frontend\widgets\ActiveForm;
use shopack\base\frontend\widgets\FormBuilder;
use iranhmusic\shopack\mha\frontend\common\models\KanoonModel;
?>

<div class='kanoon-sendmessage-form'>
	<?php
		$form = ActiveForm::begin([
			'model' => $model,
			// 'formConfig' => [
			// 	'labelSpan' => 4,
			// ],
		]);

		$builder = $form->getBuilder();

		$builder->fields([
			[
				'kanoonID',
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
		]);

		$builder->fields([
			['targetType',
				'type' => FormBuilder::FIELD_RADIOLIST,
				'data' => [
					'B' => Yii::t('mha', 'Board of Directors'),
					'M' => Yii::t('mha', 'Kanoon Members'),
				],
				'widgetOptions' => [
					'inline' => true,
				],
			],
			['message',
				'type' => FormBuilder::FIELD_TEXTAREA,
				'widgetOptions' => [
					'rows' => 4,
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
