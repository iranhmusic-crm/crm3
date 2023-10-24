<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\frontend\common\helpers\Html;

$this->title = Yii::t('mha', 'Memberships');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="member-membership-index w-100">
  <div class='card'>
		<div class='card-header'>
			<div class="float-end">
        <?= Html::createButton('تمدید عضویت', [
						'/mha/accounting/membership/add-to-basket'
					], [
						// 'localdbs' => 'basketdata=basket',
					])
				?>
        <?php
					if (true) { //has non-expired membership?
						echo Html::createButton('درخواست صدور مجدد کارت عضویت', [
								'/mha/accounting/membership-card/add-to-basket'
							], [
								'btn' => 'primary',
							]);
					}
				?>
			</div>
      <div class='card-title'><?= Html::encode($this->title) ?></div>
			<div class="clearfix"></div>
		</div>

    <div class='card-body'>
      <?php
				echo Yii::$app->controller->renderPartial('_index.php', [
					'searchModel' => $searchModel,
					'dataProvider' => $dataProvider,
					// 'userid' => $userid,
				]);
			?>
    </div>
  </div>
</div>
