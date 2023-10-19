<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\frontend\common\helpers\Html;
use iranhmusic\shopack\mha\frontend\common\accounting\models\MembershipUserAssetModel;

$this->title = Yii::t('mha', 'Memberships');
$this->params['breadcrumbs'][] = Yii::t('mha', 'Music House');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="membership-user-asset-index w-100">
  <div class='card'>
		<div class='card-header'>
			<div class="float-end">
        <?= MembershipUserAssetModel::canCreate() ? Html::createButton() : '' ?>
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
