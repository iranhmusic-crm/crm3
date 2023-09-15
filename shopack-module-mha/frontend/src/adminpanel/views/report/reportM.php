<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\frontend\common\helpers\Html;

$this->title = Yii::t('mha', 'Report Result') . ': ' . $model->rptID . '- ' . $model->rptName;
$this->params['breadcrumbs'][] = Yii::t('mha', 'Music House');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="report-run w-100">
  <div class='card border-default'>
		<div class='card-header bg-default'>
			<div class="float-end">
				<?= $model->canUpdate()   ? Html::updateButton(null,   ['id' => $model->rptID], ['modal' => false]) : '' ?>
			</div>
      <div class='card-title'><?= Html::encode($this->title) ?></div>
			<div class="clearfix"></div>
		</div>

    <div class='card-body'>
      <?php
				echo Yii::$app->controller->renderPartial('_report_M.php', [
					'dataProvider' => $dataProvider,
					'model' => $model,
				]);
			?>
    </div>
  </div>
</div>
