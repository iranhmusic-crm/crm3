<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\frontend\widgets\Select2;
use shopack\base\common\helpers\ArrayHelper;
use shopack\base\frontend\helpers\Html;
use shopack\base\frontend\widgets\ActiveForm;
use shopack\base\frontend\widgets\FormBuilder;
use iranhmusic\shopack\mha\frontend\common\models\DocumentModel;
use iranhmusic\shopack\mha\common\enums\enuDocumentMembershipDegree;
use iranhmusic\shopack\mha\common\enums\enuMemberDocumentStatus;
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

		$builder->fields([
			[
				'mbrdocMemberID',
				'type' => FormBuilder::FIELD_STATIC,
				'staticValue' => $model->member->displayName(),
			],
			[
				'mbrdocDocumentID',
				'type' => FormBuilder::FIELD_STATIC,
				'staticValue' => $model->document->docName,
			],
			[
				'mbrdocStatus',
				'type' => FormBuilder::FIELD_STATIC,
				'staticValue' => enuMemberDocumentStatus::getLabel($model->mbrdocStatus),
			],
		]);

		$builder->fields([
			[
				'mbrdocComment',
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
