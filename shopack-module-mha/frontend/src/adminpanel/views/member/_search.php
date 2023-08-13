<?php
use shopack\base\frontend\helpers\Html;
use shopack\base\frontend\widgets\ActiveForm;
use iranhmusic\shopack\mha\frontend\common\models\MemberSearchModel;
?>

<div class='member-model-search'>
	<?php
		$form = ActiveForm::begin([
			'model' => $searchModel,
			'action' => ['index'],
			'method' => 'get',
			'options' => [
				'data-pjax' => 1,
			],
			'formConfig' => [
				'labelSpan' => 12, //\kartik\form\ActiveField::NotSet,
			],
			'type' => ActiveForm::TYPE_VERTICAL,
			'template' =>"{beginLabel}{labelTitle}:{endLabel}\n<br>{beginWrapper}\n{input}\n{error}\n{hint}\n{endWrapper}",
		]);

		// echo Html::hiddenInput('maincmd', $maincmd ?? null);
	?>

	<div>
		<div class='pull-left'>
			<div>
				<div style='display:inline-block; margin-left: 20px;'>
					<?php
						echo $form->field($searchModel, 'filter_mode', [
								'inputOptions' => [
									'class' => [],
								],
							])
							->widget(\yii\bootstrap5\ToggleButtonGroup::class, [
								'type' => 'radio',
								'labelOptions' => ['class' => ['btn-outline-secondary', 'btn-sm']],
								'items' => [
									MemberSearchModel::FILTER_MODE_ALL => 'همه',
									MemberSearchModel::FILTER_MODE_WAIT_FOR_BASE_APPROVAL => 'منتظر تایید مدارک',
									MemberSearchModel::FILTER_MODE_WAIT_FOR_KANOON_APPROVAL => 'منتظر تایید کانون',
								],
							])
						;
					?>
				</div>
			</div>
		</div>

		<div class='form-group pull-right'>
			<?php
				echo Html::submitButton(Yii::t('appmgmt', 'Filter'), ['class' => 'btn btn-primary', 'style' => [
					'min-width' => '100px',
				]]);
				// echo Html::resetButton(Yii::t('yii', 'Reset'), ['class' => 'btn btn-outline-secondary']);
			?>
		</div>

		<div class='clearfix'></div>
	</div>

	<?php
		$form->endForm(); //ActiveForm::end();
	?>
</div>
