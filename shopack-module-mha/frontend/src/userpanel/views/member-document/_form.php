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
use iranhmusic\shopack\mha\frontend\common\models\DocumentModel;
use iranhmusic\shopack\mha\common\enums\enuMemberDocumentStatus;
use iranhmusic\shopack\mha\frontend\common\models\MemberModel;
use shopack\base\frontend\widgets\datetime\DatePicker;
?>

<div class='member-document-form'>
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
		// 		'mbrdocMemberID',
		// 		'type' => FormBuilder::FIELD_STATIC,
		// 		'staticValue' => $model->member->displayName(),
		// 	],
		// ]);

		$builder->fields([
			[
				'mbrdocDocumentID',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'data' => ArrayHelper::map(DocumentModel::find()->asArray()->all(), 'docID', 'docName'),
					'options' => [
						'placeholder' => Yii::t('app', '-- Choose --'),
						'dir' => 'rtl',
					],
				],
			],
			['mbrdocTitle'],
			[
				'mbrdocFileID',
				'type' => FormBuilder::FIELD_FILE,
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
