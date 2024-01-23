<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\common\helpers\StringHelper;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\frontend\common\widgets\grid\GridView;
use shopack\base\common\accounting\enums\enuAmountType;
use shopack\base\common\accounting\enums\enuSaleableStatus;
use shopack\base\common\accounting\enums\enuSaleableType;

$modelClass = Yii::$app->controller->modelClass;

$this->params['breadcrumbs'][] = Yii::t('mha', 'Music House');
$this->params['breadcrumbs'][] = Yii::t('mha', 'Services Definition and Fee');
$this->title = Yii::t('aaa', 'Saleables');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="saleable-index w-100">
  <div class='card'>
		<div class='card-header'>
			<div class="float-end">
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
          'slbID',
          [
            'attribute' => 'slbName',
            // 'format' => 'raw',
            // 'value' => function ($model, $key, $index, $widget) {
            //   return Html::a($model->slbName, ['view', 'id' => $model->slbID]);
            // },
          ],
          'slbBasePrice:toman',
          'discountAmount:toman',
          'discountedBasePrice:toman',
          [
            'attribute' => 'discountsInfo',
            'format' => 'raw',
            'value' => function ($model, $key, $index, $widget) {
              if (empty($model->discountsInfo))
                return null;

              $items = json_decode($model->discountsInfo, true);
              if (empty($items))
                return null;

              $result = [];
              $result[] = '<tr><td>' . implode('</td><td>', [
                '#',
                'کد',
                'نام',
                'مبلغ',
                'نوع',
              ]) . '</td></tr>';
              foreach ($items as $k => $item) {
                $result[] = '<tr><td>' . implode('</td><td>', [
                  $k + 1,
                  $item['id'],
                  $item['name'],
                  Yii::$app->formatter->asDecimal($item['amount']),
                  $item['type'],
                ]) . '</td></tr>';
              }
              return '<table class="table table-bordered table-striped">' . implode('', $result) . '</table>';
            },
          ],

          [
            'class' => \shopack\base\frontend\common\widgets\grid\EnumDataColumn::class,
            'enumClass' => enuSaleableStatus::class,
            'attribute' => 'slbStatus',
          ],
          // [
            // 'class' => \shopack\base\frontend\common\widgets\ActionColumn::class,
            // 'header' => $modelClass::canCreate() ? Html::createButton(null, null, [
            //   'data-popup-size' => 'lg',
            // ]) : Yii::t('app', 'Actions'),
            // 'template' => '{update} {delete}{undelete}',
            // 'updateOptions' => [
            //   'modal' => true,
            //   'data-popup-size' => 'lg',
            // ],
            // 'visibleButtons' => [
            //   'update' => function ($model, $key, $index) {
            //     return $model->canUpdate();
            //   },
            //   'delete' => function ($model, $key, $index) {
            //     return $model->canDelete();
            //   },
            //   'undelete' => function ($model, $key, $index) {
            //     return $model->canUndelete();
            //   },
            // ],
          // ],
          [
            'attribute' => 'rowDate',
            'noWrap' => true,
            'format' => 'raw',
            'label' => 'ایجاد / ویرایش',
            'value' => function($model) {
              return Html::formatRowDates(
                $model->slbCreatedAt,
                $model->createdByUser,
                $model->slbUpdatedAt,
                $model->updatedByUser,
                $model->slbRemovedAt,
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
