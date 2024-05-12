<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use yii\web\JsExpression;
use shopack\base\common\helpers\Url;
use shopack\base\frontend\common\widgets\Select2;
use shopack\base\frontend\common\widgets\DepDrop;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\frontend\common\widgets\ActiveForm;
use shopack\base\frontend\common\widgets\FormBuilder;
use iranhmusic\shopack\mha\common\enums\enuKanoonStatus;
use iranhmusic\shopack\mha\common\enums\enuBasicDefinitionType;
use iranhmusic\shopack\mha\frontend\common\widgets\form\MemberChooseFormField;
?>

<div class='kanoon-form'>
	<?php
		$form = ActiveForm::begin([
			'model' => $model,
			'formConfig' => [
				'labelSpan' => 4,
			],
		]);

		$builder = $form->getBuilder();

		$fildTypes = [
			'text' => 'متن',
		];
		$mhaList = enuBasicDefinitionType::getList();
		foreach($mhaList as $k => $v) {
			$fildTypes['mha:' . $k] = $v;
		}

		$builder->fields([
			['knnStatus',
				'type' => FormBuilder::FIELD_RADIOLIST,
				'data' => enuKanoonStatus::listData('form'),
				'widgetOptions' => [
					'inline' => true,
				],
			],
			['knnName'],
			['knnNameEn'],
			['knnDescFieldType',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'data' => $fildTypes,
					'options' => [
						'placeholder' => Yii::t('app', '-- Choose --'),
						'dir' => 'rtl',
					],
					'pluginOptions' => [
						'allowClear' => true,
					],
				],
			],
			['knnDescFieldLabel',
				'visibleConditions' => [
					'knnDescFieldType' => ['!=', ''],
				],
			],
			['@static' => '<hr>'],
		]);

		$builder->fields(MemberChooseFormField::field($this, $model, 'knnPresidentMemberID'));
		$builder->fields(MemberChooseFormField::field($this, $model, 'knnVicePresidentMemberID'));
		$builder->fields(MemberChooseFormField::field($this, $model, 'knnOzv1MemberID'));
		$builder->fields(MemberChooseFormField::field($this, $model, 'knnOzv2MemberID'));
		$builder->fields(MemberChooseFormField::field($this, $model, 'knnOzv3MemberID'));
		$builder->fields(MemberChooseFormField::field($this, $model, 'knnWardenMemberID'));
		$builder->fields(MemberChooseFormField::field($this, $model, 'knnTalkerMemberID'));
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
