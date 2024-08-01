<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\frontend\common\widgets\grid\GridView;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\common\helpers\StringHelper;
use iranhmusic\shopack\mha\common\enums\enuDocumentType;
use iranhmusic\shopack\mha\common\enums\enuDocumentStatus;
use iranhmusic\shopack\mha\frontend\common\models\DocumentModel;
use shopack\base\frontend\common\widgets\JsonTableGrid;

$this->title = Yii::t('mha', 'Document Types');
$this->params['breadcrumbs'][] = Yii::t('mha', 'Music House');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="document-index w-100">
  <div class='card'>
		<div class='card-header'>
			<div class="float-end">
        <?= DocumentModel::canCreate() ? Html::createButton() : '' ?>
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
          [
            'class' => 'shopack\base\frontend\common\widgets\grid\ExpandRowColumn',
            'value' => function ($model, $key, $index, $column) {
              return GridView::ROW_COLLAPSED;
              // this bahaviour moved to gridview::run for covering initialize error
              // return ($selected_adngrpID == $model->adngrpID ? GridView::ROW_EXPANDED : GridView::ROW_COLLAPSED);
            },
            'detail' => function ($model) {
              return Html::div($model->getAttributeLabel('docExtraParamsSchema') . ':')
                . JsonTableGrid::formatParamsSchemaAsTable($model, 'docExtraParamsSchema');
            },
          ],
          'docID',
          [
            'attribute' => 'docName',
            'format' => 'raw',
            'value' => function ($model, $key, $index, $widget) {
              return Html::a($model->docName, ['view', 'id' => $model->docID]);
            },
          ],
          [
            'class' => \shopack\base\frontend\common\widgets\grid\EnumDataColumn::class,
            'enumClass' => enuDocumentType::class,
            'attribute' => 'docType',
          ],
          [
            'class' => \shopack\base\frontend\common\widgets\grid\EnumDataColumn::class,
            'enumClass' => enuDocumentStatus::class,
            'attribute' => 'docStatus',
          ],
          [
            'class' => \shopack\base\frontend\common\widgets\ActionColumn::class,
            'header' => DocumentModel::canCreate() ? Html::createButton(null, null, ['data' => ['popup-size' => 'lg']]) : Yii::t('app', 'Actions'),
            'template' => '{update} {delete}{undelete}',
            'updateOptions' => [
              'modal' => true,
              'data-popup-size' => 'lg',
            ],
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
                $model->docCreatedAt,
                $model->createdByUser,
                $model->docUpdatedAt,
                $model->updatedByUser,
                $model->docRemovedAt,
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
