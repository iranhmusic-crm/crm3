<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\frontend\helpers\Html;
use iranhmusic\shopack\mha\frontend\common\models\MemberSupplementaryInsDocModel;

$this->title = Yii::t('mha', 'Supplementary Insurance Documents');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="member-supplementary-ins-doc-index w-100">
  <div class='card border-default'>
		<div class='card-header bg-default'>
			<div class="float-end">
			</div>
      <div class='card-title'><?= Html::encode($this->title) ?></div>
			<div class="clearfix"></div>
		</div>

    <div class='card-body'>
      <?php
				echo Yii::$app->controller->renderPartial('_index.php', [
					'searchModel' => $searchModel,
					'dataProvider' => $dataProvider,
					'mbrsinsdocMemberID' => $mbrsinsdocMemberID ?? $_GET['mbrsinsdocMemberID'] ?? null,
				]);
			?>
    </div>
  </div>
</div>
