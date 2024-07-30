<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\helpers\Url;
use shopack\base\common\helpers\Json;
use shopack\base\common\helpers\ArrayHelper;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\frontend\common\widgets\Select2;
use shopack\base\frontend\common\widgets\ActiveForm;
use shopack\base\frontend\common\widgets\FormBuilder;
use iranhmusic\shopack\mha\frontend\common\widgets\form\MemberChooseFormField;
use iranhmusic\shopack\mha\frontend\common\models\DocumentModel;
?>

<div class='member-document-form'>
	<?php
		$form = ActiveForm::begin([
			'model' => $model,
			'formConfig' => [
				'labelSpan' => 4,
			],
		]);

		$formName = $model->formName();
    $formNameLower = strtolower($formName);

		$builder = $form->getBuilder();

		//from member view or side bar?
		if (empty($model->mbrdocMemberID)) {
			$builder->fields(MemberChooseFormField::field($this, $model, 'mbrdocMemberID', false));
		} else {
			$builder->fields([
				[
					'mbrdocMemberID',
					'type' => FormBuilder::FIELD_STATIC,
					'staticValue' => $model->member->displayName(),
				],
			]);
		}

		$loadingText = "<div class='text-center'>" . Yii::t('app', 'Loading...') . "</div>";

		$getParamsSchemaUrl = Url::to(['document/params-schema', 'field' => 'docExtraParamsSchema']) . '&id=';
		$extraParamsData = '{}';
		if ($model->mbrdocExtraParams !== null)
			$extraParamsData = Json::encode($model->mbrdocExtraParams);

		$builder->fields([
			[
				'mbrdocDocumentID',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'data' => ArrayHelper::map(DocumentModel::find()->asArray()->noLimit()->all(), 'docID', 'docName'),
					'options' => [
						'placeholder' => Yii::t('app', '-- Choose --'),
						'dir' => 'rtl',
					],
					'pluginEvents' => [
						'select2:select' => "function(e) {
							createDynamicParamsFormUI($(this).val(), \"{$loadingText}\", '{$getParamsSchemaUrl}', '{$formNameLower}', 'mbrdocExtraParams', '{$formName}', 'mbrdocExtraParams', {$extraParamsData}, 'params-container', 4);
							return true;
						}",
					],
				],
			],
			['mbrdocTitle'],
			[
				'mbrdocFileID',
				'type' => FormBuilder::FIELD_FILE,
				'widgetOptions' => [
					'accept' => 'image/png, image/gif, image/jpg, image/jpeg',
				],
			],
		]);

		if ($model->mbrdocDocumentID) {
			$js = "createDynamicParamsFormUI('{$model->mbrdocDocumentID}', \"{$loadingText}\", '{$getParamsSchemaUrl}', '{$formNameLower}', 'mbrdocExtraParams', '{$formName}', 'mbrdocExtraParams', {$extraParamsData}, 'params-container', 4);";
			$this->registerJs($js, \yii\web\View::POS_READY);
		}
	?>

	<?php $builder->beginField(); ?>
		<div id='params-container' class='row'></div>
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
