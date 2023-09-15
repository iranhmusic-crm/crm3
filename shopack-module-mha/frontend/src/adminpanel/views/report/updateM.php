<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\frontend\common\helpers\Html;

$this->title = Yii::t('app', 'Update');
$this->params['breadcrumbs'][] = Yii::t('mha', 'Music House');
$this->params['breadcrumbs'][] = ['label' => Yii::t('mha', 'Reports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->rptName, 'url' => ['view', 'id' => $model->rptID]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div id='member-report-update' class='d-flex justify-content-center'>
	<div class='w-100 card border-primary'>

		<div class='card-header bg-primary text-white'>
			<div class='card-title'><?= Html::encode($this->title) ?></div>
		</div>

		<?= $this->render('_form_M', [
			'model' => $model,
		]) ?>
	</div>
</div>
