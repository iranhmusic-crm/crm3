<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\frontend\widgets\Select2;
use shopack\base\frontend\widgets\datetime\DatePicker;
use shopack\base\frontend\helpers\Html;
use shopack\base\frontend\widgets\ActiveForm;
use shopack\base\frontend\widgets\FormBuilder;
use shopack\aaa\common\enums\enuGender;
use iranhmusic\shopack\mha\frontend\common\models\MasterInsurerTypeModel;
use iranhmusic\shopack\mha\common\enums\enuSponsorshipType;
?>

<div class='member-sponsorship-form'>
	<?php
		$form = ActiveForm::begin([
			'model' => $model,
			'formConfig' => [
				'labelSpan' => 4,
			],
		]);

		$builder = $form->getBuilder();

		$builder->fields([
			// [
			// 	'mbrspsMemberID',
			// 	'type' => FormBuilder::FIELD_STATIC,
			// 	'staticValue' => $model->member->displayName(),
			// ],
			[
				'mbrspsType',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'data' => enuSponsorshipType::listData(),
					'options' => [
						'placeholder' => Yii::t('app', '-- Choose --'),
						'dir' => 'rtl',
					],
					'pluginOptions' => [
						'allowClear' => true,
					],
				],
			],
			[
				'mbrspsGender',
				'type' => FormBuilder::FIELD_RADIOLIST,
				'data' => enuGender::listData(),
				'widgetOptions' => [
					'inline' => true,
				],
			],
			['mbrspsFirstName'],
			['mbrspsLastName'],
			['mbrspsFatherName'],
			['mbrspsShID'],
			['mbrspsSSN'],
			[
				'mbrspsBirthDate',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => DatePicker::class,
				'fieldOptions' => [
					'addon' => [
						'append' => [
							'content' => '<i class="far fa-calendar-alt"></i>',
						],
					],
				],
			],
			['mbrspsBirthLocation'],
		]);

		$list = [];
    $models = MasterInsurerTypeModel::find()->all();
		foreach ($models as $model) {
			$list[$model->minstypID] = $model->masterInsurer->minsName . ' - ' . $model->minstypName;
		}

		$builder->fields([
			[
				'mbrspsMasterInsTypeID',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'data' => $list,
					'options' => [
						'placeholder' => Yii::t('app', '-- Choose --'),
						'dir' => 'rtl',
					],
					'pluginOptions' => [
						'allowClear' => true,
					],
				],
			],
			[
				'mbrspsSubstation',
			],
			[
				'mbrspsInsuranceCode',
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
