<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\frontend\common\widgets\grid\GridView;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\common\helpers\StringHelper;
use shopack\aaa\frontend\common\models\SaleableModel;
use shopack\base\common\accounting\enums\enuSaleableStatus;
?>

<?php
  $slbOwnerUserID = Yii::$app->request->queryParams['slbOwnerUserID'] ?? null;
?>

<?php
	// echo Alert::widget(['key' => 'shoppingcart']);

	if (isset($statusReport))
		echo $statusReport;

    // (is_array($statusReport) ? Html::icon($statusReport[0], ['plugin' => 'glyph']) . ' ' . $statusReport[1] : $statusReport);

  echo GridView::widget([
    'id' => StringHelper::generateRandomId(),
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
      [
        'class' => 'kartik\grid\SerialColumn',
      ],
      'slbID',
      // 'slbProductID',
      'slbCode',
      [
        'attribute' => 'slbName',
        'format' => 'raw',
        'value' => function ($model, $key, $index, $widget) {
          return Html::a($model->slbName, ['view', 'id' => $model->slbID]);
        },
      ],
      // 'slbDesc',
      'slbAvailableFromDate:jalaliWithTime',
      // 'slbAvailableToDate:jalaliWithTime',
      // 'slbPrivs',
      [
        'attribute' => 'slbBasePrice',
        'format' => 'toman',
        'contentOptions' => [
          'class' => ['text-nowrap', 'tabular-nums'],
        ],
      ],
      // 'slbAdditives',
      // 'slbProductCount',
      // 'slbMaxSaleCountPerUser',
      // 'slbInStockQty',
      // 'slbOrderedQty',
      // 'slbReturnedQty',
      // 'slbVoucherTemplate',
      // 'slbI18NData',

      [
        'class' => \shopack\base\frontend\common\widgets\grid\EnumDataColumn::class,
        'enumClass' => enuSaleableStatus::class,
        'attribute' => 'slbStatus',
      ],
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
