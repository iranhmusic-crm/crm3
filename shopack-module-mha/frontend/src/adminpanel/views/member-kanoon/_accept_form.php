<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\frontend\common\widgets\Select2;
use shopack\base\common\helpers\ArrayHelper;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\frontend\common\widgets\ActiveForm;
use shopack\base\frontend\common\widgets\FormBuilder;
use iranhmusic\shopack\mha\frontend\common\models\KanoonModel;
use iranhmusic\shopack\mha\common\enums\enuKanoonMembershipDegree;
use iranhmusic\shopack\mha\common\enums\enuMemberKanoonStatus;
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

		$builder->fields([
			[
				'mbrknnMemberID',
				'type' => FormBuilder::FIELD_STATIC,
				'staticValue' => $model->member->displayName(),
			],
			[
				'mbrknnKanoonID',
				'type' => FormBuilder::FIELD_STATIC,
				'staticValue' => $model->kanoon->knnName,
			],
			[
				'mbrknnStatus',
				'type' => FormBuilder::FIELD_STATIC,
				'staticValue' => enuMemberKanoonStatus::getLabel($model->mbrknnStatus),
			],
		]);

		$builder->fields([
			[
				'mbrknnMembershipDegree',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'data' => enuKanoonMembershipDegree::getList(),
					'options' => [
						'placeholder' => Yii::t('app', '-- Choose --'),
						'dir' => 'rtl',
					],
				],
			],
			[
				'mbrknnComment',
			],

			//TODO: make mbrRegisterCode unique in DB
			// [
			// 	'mbrRegisterCode',
			// ]

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
