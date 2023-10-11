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
use iranhmusic\shopack\mha\frontend\common\accounting\models\MembershipCardSaleableModel;
?>

<?php
  // $slbProductID = Yii::$app->request->queryParams['slbProductID'] ?? null;
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
      // 'slbCode',
      [
        'attribute' => 'slbName',
      //   'format' => 'raw',
      //   'value' => function ($model, $key, $index, $widget) {
      //     return Html::a($model->slbName, ['view', 'id' => $model->slbID]);
      //   },
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
        'class' => \shopack\base\frontend\common\widgets\ActionColumn::class,
        'header' => MembershipCardSaleableModel::canCreate() ? Html::createButton(null, [
          // 'card-saleable/create',
          'slbProductID' => $slbProductID,
        ], [
          'title' => yii::t('mha', 'Create Card Saleable'),
        ]) : Yii::t('app', 'Actions'),
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
