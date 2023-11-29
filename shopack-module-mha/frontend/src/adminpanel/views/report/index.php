<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\common\helpers\StringHelper;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\frontend\common\widgets\grid\GridView;
use iranhmusic\shopack\mha\common\enums\enuReportType;
use iranhmusic\shopack\mha\common\enums\enuReportStatus;
use iranhmusic\shopack\mha\frontend\common\models\ReportModel;

$this->title = Yii::t('mha', 'Reports');
$this->params['breadcrumbs'][] = Yii::t('mha', 'Music House');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="report-index w-100">
  <div class='card'>
		<div class='card-header'>
			<div class="float-end">
        <?php
          // ReportModel::canCreate() ? Html::createButton() : ''

          echo \yii\bootstrap5\ButtonDropdown::widget([
            'label' => Yii::t('app', 'Create') . '...',
            'buttonOptions' => [
              'class' => 'btn btn-success btn-sm',
            ],
            'dropdown' => [
              'items' => [
                [
                  'label' => Yii::t('mha', 'Member Report'),
                  'url' => ['create', 'rpttyp' => enuReportType::Members],
                ],
                [
                  'label' => Yii::t('mha', 'Financial Report'),
                  'url' => ['create', 'rpttyp' => enuReportType::Fiancial],
                ],
              ],
              'options' => [
                'class' => 'dropdown-menu-right',
              ],
            ],
          ]);

        ?>
			</div>
      <div class='card-title'><?= Html::encode($this->title) ?></div>
			<div class="clearfix"></div>
		</div>

    <div class='card-body'>
      <?php
        echo GridView::widget([
          'id' => StringHelper::generateRandomId(),
          'itemLabelSingle' => Yii::t('mha', 'Report'),
          'dataProvider' => $dataProvider,
          'filterModel' => $searchModel,

          'columns' => [
            [
              'class' => 'kartik\grid\SerialColumn',
            ],
            'rptID',
            [
              'attribute' => 'rptName',
              'format' => 'raw',
              'value' => function ($model, $key, $index, $widget) {
                return Html::a($model->rptName, ['view', 'id' => $model->rptID]);
              },
            ],
            [
              'class' => \shopack\base\frontend\common\widgets\grid\EnumDataColumn::class,
              'enumClass' => enuReportStatus::class,
              'attribute' => 'rptStatus',
            ],
            [
              'class' => \shopack\base\frontend\common\widgets\ActionColumn::class,
              'header' => /*ReportModel::canCreate() ? Html::createButton() :*/ Yii::t('app', 'Actions'),
              'template' => '{run} {export} {update} {delete}{undelete}',
              'updateOptions' => [
                'modal' => false,
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
                'run' => function ($model, $key, $index) {
                  return true;
                },
                'export' => function ($model, $key, $index) {
                  return true;
                },
              ],
              'buttons' => [
                'run' => function ($url, $model, $key) {
                  return Html::confirmButton(yii::t('mha', 'Run Report'), [
                    'run',
                    'id' => $model->rptID
                  ], 'آیا می‌خواهید این گزارش اجرا شود؟', [
                    'btn' => 'success',
                  ]);
                },
                'export' => function ($url, $model, $key) {
                  return Html::confirmButton(yii::t('mha', 'Export Report'), [
                    'export',
                    'id' => $model->rptID
                  ], 'آیا می‌خواهید فایل خروجی این گزارش را دریافت کنید؟', [
                    'btn' => 'success',
                  ]);
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
                  $model->rptCreatedAt,
                  $model->createdByUser,
                  $model->rptUpdatedAt,
                  $model->updatedByUser,
                  $model->rptRemovedAt,
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
