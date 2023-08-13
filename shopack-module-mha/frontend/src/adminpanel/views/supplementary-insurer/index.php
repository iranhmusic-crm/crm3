<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\frontend\widgets\grid\GridView;
use shopack\base\frontend\helpers\Html;
use shopack\base\common\helpers\StringHelper;
use iranhmusic\shopack\mha\common\enums\enuInsurerStatus;
use iranhmusic\shopack\mha\frontend\common\models\SupplementaryInsurerModel;

$this->title = Yii::t('mha', 'Supplementary Insurers');
$this->params['breadcrumbs'][] = Yii::t('mha', 'Music House');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="supplementary-insurer-index w-100">
  <div class='card border-default'>
		<div class='card-header bg-default'>
			<div class="float-end">
        <?= SupplementaryInsurerModel::canCreate() ? Html::createButton() : '' ?>
			</div>
      <div class='card-title'><?= Html::encode($this->title) ?></div>
			<div class="clearfix"></div>
		</div>

    <div class='card-body'>
      <?php
      echo GridView::widget([
        'id' => StringHelper::generateRandomId(),
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'columns' => [
          [
            'class' => 'kartik\grid\SerialColumn',
          ],
          'sinsID',
          [
            'attribute' => 'sinsName',
            'format' => 'raw',
            'value' => function ($model, $key, $index, $widget) {
              return Html::a($model->sinsName, ['view', 'id' => $model->sinsID]);
            },
          ],
          [
            'class' => \shopack\base\frontend\widgets\grid\EnumDataColumn::class,
            'enumClass' => enuInsurerStatus::class,
            'attribute' => 'sinsStatus',
          ],
          [
            'class' => \shopack\base\frontend\widgets\ActionColumn::class,
            'header' => SupplementaryInsurerModel::canCreate() ? Html::createButton() : Yii::t('app', 'Actions'),
            'template' => '{update} {delete}{undelete}',
            'visibleButtons' => [
              'update' => function ($model, $key, $index) {
                return $model->canUpdate();
              },
              'delete' => function ($model, $key, $index) {
                return $model->canDelete();
              },
              'undelete' => function ($model, $key, $index) {
                return $model->canUndelete();
              },
            ],
          ],
          [
            'attribute' => 'rowDate',
            'noWrap' => true,
            'format' => 'raw',
            'label' => 'ایجاد / ویرایش',
            'value' => function($model) {
              return Html::formatRowDates(
                $model->sinsCreatedAt,
                $model->createdByUser,
                $model->sinsUpdatedAt,
                $model->updatedByUser,
                $model->sinsRemovedAt,
                $model->removedByUser,
              );
            },
          ],
        ],
      ]);
      ?>
    </div>
  </div>
</div>
