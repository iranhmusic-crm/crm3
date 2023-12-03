<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\helpers\ArrayHelper;
use shopack\base\common\helpers\Json;
use shopack\base\common\helpers\Url;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\frontend\common\widgets\Select2;
use shopack\base\frontend\common\widgets\ActiveForm;
use shopack\base\frontend\common\widgets\FormBuilder;
use iranhmusic\shopack\mha\common\enums\enuKanoonMembershipDegree;
use iranhmusic\shopack\mha\common\enums\enuMemberKanoonStatus;
use iranhmusic\shopack\mha\frontend\common\models\KanoonModel;
use iranhmusic\shopack\mha\frontend\common\widgets\form\MemberChooseFormField;
?>

<div class='member-kanoon-form'>
	<?php
		$form = ActiveForm::begin([
			'model' => $model,
			// 'formConfig' => [
			// 	'labelSpan' => 4,
			// ],
		]);

    $formName = $model->formName();
    $formNameLower = strtolower($formName);

		$builder = $form->getBuilder();

		//from member view or side bar?
		if (empty($model->mbrknnMemberID)) {
			$builder->fields(MemberChooseFormField::field($this, $model, 'mbrknnMemberID', false));
		} else {
			$builder->fields([
				[
					'mbrknnMemberID',
					'type' => FormBuilder::FIELD_STATIC,
					'staticValue' => $model->member->displayName(),
				],
			]);
		}

		$loadingText = "<div class='text-center'>" . Yii::t('app', 'Loading...') . "</div>";

		$getParamsSchemaUrl = Url::to(['kanoon/params-schema']) . '?id=';
		$strKanoonParameters = '{}';
		if ($model->mbrknnParams !== null)
			$strKanoonParameters = Json::encode($model->mbrknnParams);

		$builder->fields([
			[
				'mbrknnKanoonID',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'data' => ArrayHelper::map(KanoonModel::find()->asArray()->noLimit()->all(), 'knnID', 'knnName'),
					'options' => [
						'placeholder' => Yii::t('app', '-- Choose --'),
						'dir' => 'rtl',
					],
					'pluginEvents' => [
						'select2:select' => "function(e) {
							createDynamicParamsFormUI($(this).val(), \"{$loadingText}\", '{$getParamsSchemaUrl}', '{$formNameLower}', 'mbrknnParams', '{$formName}', 'mbrknnParams', {$strKanoonParameters}, 'params-container', 3);
							return true;
						}",
					],
				],
			],
		]);
	?>

	<?php $builder->beginField(); ?>
		<div id='params-container' class='row'></div>
	<?php $builder->endField(); ?>

	<?php
		$builder->fields([
			[
				'mbrknnStatus',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'data' => enuMemberKanoonStatus::getList(),
					'options' => [
						'placeholder' => Yii::t('app', '-- Choose --'),
						'dir' => 'rtl',
					],
				],
			],
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
				'visibleConditions' => [
					'mbrknnStatus' => enuMemberKanoonStatus::Accepted,
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
