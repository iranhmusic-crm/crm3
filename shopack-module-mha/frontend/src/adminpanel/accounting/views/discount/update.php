<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\frontend\common\helpers\Html;

$this->params['breadcrumbs'][] = Yii::t('mha', 'Music House');
$this->params['breadcrumbs'][] = Yii::t('mha', 'Services Definition and Fee');
$this->params['breadcrumbs'][] = ['label' => $model->dscName, 'url' => ['view', 'id' => $model->dscID]];
$this->title = Yii::t('aaa', 'Update Discount');
$this->params['breadcrumbs'][] = $this->title;
?>

<div id='discount-update' class='d-flex justify-content-center'>
	<div class='w-sm-75 card border-primary'>

		<div class='card-header bg-primary text-white'>
			<div class='card-title'><?= Html::encode($this->title) ?></div>
		</div>

		<?= $this->render('_form', [
			'model' => $model,
		]) ?>
	</div>
</div>
