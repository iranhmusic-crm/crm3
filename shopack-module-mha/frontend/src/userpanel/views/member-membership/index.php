<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\frontend\helpers\Html;
use iranhmusic\shopack\mha\frontend\common\models\MemberMembershipModel;

$this->title = Yii::t('mha', 'Memberships');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="member-membership-index w-100">
  <div class='card border-default'>
		<div class='card-header bg-default'>
			<div class="float-end">
        <?= MemberMembershipModel::canCreate()
					? Html::createButton('تمدید عضویت', ['/mha/membership/add-to-basket'], [
						// 'localdbs' => 'basketdata=basket',
					])
					: '' ?>
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
