<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\frontend\common\widgets\grid\GridView;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\common\helpers\StringHelper;
// use iranhmusic\shopack\mha\frontend\common\models\MemberModel;
// use iranhmusic\shopack\mha\common\enums\enuMemberMembershipStatus;
use shopack\base\common\accounting\enums\enuUserAssetStatus;
?>

<?php
//todo: rename mbrshpMemberID to uasActorID
  $uasActorID = Yii::$app->request->queryParams['uasActorID'] ?? null;
?>

<?php
	// echo Alert::widget(['key' => 'shoppingcart']);

	// if (isset($statusReport))
	// 	echo (is_array($statusReport) ? Html::icon($statusReport[0], ['plugin' => 'glyph']) . ' ' . $statusReport[1] : $statusReport);

  $columns = [
    [
      'class' => 'kartik\grid\SerialColumn',
    ],
  ];

  if (empty($uasActorID)) {
    $columns = array_merge($columns, [
      [
        'class' => \iranhmusic\shopack\mha\frontend\common\widgets\grid\MemberDataColumn::class,
        'attribute' => 'uasActorID',
        'format' => 'raw',
        'value' => function ($model, $key, $index, $widget) {
          return Html::a($model->actor->displayName(), ['/mha/member/view', 'id' => $model->uasActorID]); //, ['class' => ['btn', 'btn-sm', 'btn-outline-secondary']]);
        },
      ],
    ]);
  }

  $columns = array_merge($columns, [
    [
      // 'class' => \iranhmusic\shopack\mha\frontend\common\widgets\grid\MembershipDataColumn::class,
      'attribute' => 'uasSaleableID',
      'value' => function ($model, $key, $index, $widget) {
        return $model->saleable->slbName;
      },
    ],
    'uasValidFromDate:jalali',
    'uasValidToDate:jalali',
    [
      'attribute' => 'uasVoucherID',
      'format' => 'raw',
      'value' => function ($model, $key, $index, $widget) {
        return Html::a($model->uasVoucherID, ['/aaa/voucher/view', 'id' => $model->uasVoucherID]);
      },
    ],
    [
      'attribute' => 'uasStatus',
      'class' => \shopack\base\frontend\common\widgets\grid\EnumDataColumn::class,
      'enumClass' => enuUserAssetStatus::class,
    ],
    [
      'class' => \shopack\base\frontend\common\widgets\ActionColumn::class,
      'header' => /* MemberModel::canCreate() ? Html::createButton(null, [
        'create',
        'uasActorID' => $uasActorID ?? $_GET['uasActorID'] ?? null,
      ]) : */ Yii::t('app', 'Actions'),
      'template' => '',
    ],
    [
      'attribute' => 'rowDate',
      'noWrap' => true,
      'format' => 'raw',
      'label' => 'ایجاد / ویرایش',
      'value' => function($model) {
        return Html::formatRowDates(
          $model->uasCreatedAt,
          $model->createdByUser,
          $model->uasUpdatedAt,
          $model->updatedByUser,
          // $model->uasRemovedAt,
          // $model->removedByUser,
        );
      },
    ],
  ]);

  echo GridView::widget([
    'id' => StringHelper::generateRandomId(),
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $columns,
  ]);
?>
