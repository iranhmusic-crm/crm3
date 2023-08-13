<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\frontend\widgets\grid\GridView;
use shopack\base\frontend\helpers\Html;
use shopack\base\common\helpers\StringHelper;
use iranhmusic\shopack\mha\frontend\common\models\MemberModel;
use iranhmusic\shopack\mha\common\enums\enuMemberMembershipStatus;
?>

<?php
  $mbrshpMemberID = Yii::$app->request->queryParams['mbrshpMemberID'] ?? null;
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

  if (empty($mbrshpMemberID)) {
    $columns = array_merge($columns, [
      [
        'class' => \iranhmusic\shopack\mha\frontend\common\widgets\grid\MemberDataColumn::class,
        'attribute' => 'mbrshpMemberID',
        'format' => 'raw',
        'value' => function ($model, $key, $index, $widget) {
          return Html::a($model->member->displayName(), ['/mha/member/view', 'id' => $model->mbrshpMemberID]); //, ['class' => ['btn', 'btn-sm', 'btn-outline-secondary']]);
        },
      ],
    ]);
  }

  $columns = array_merge($columns, [
    [
      // 'class' => \iranhmusic\shopack\mha\frontend\common\widgets\grid\MembershipDataColumn::class,
      'attribute' => 'mbrshpMembershipID',
      'value' => function ($model, $key, $index, $widget) {
        return $model->membership->mshpTitle;
      },
    ],
    'mbrshpStartDate:jalali',
    'mbrshpEndDate:jalali',
    [
      'attribute' => 'mbrshpVoucherID',
      'format' => 'raw',
      'value' => function ($model, $key, $index, $widget) {
        return Html::a($model->mbrshpVoucherID, ['/aaa/voucher/view', 'id' => $model->mbrshpVoucherID]);
      },
    ],
    [
      'attribute' => 'mbrshpStatus',
      'class' => \shopack\base\frontend\widgets\grid\EnumDataColumn::class,
      'enumClass' => enuMemberMembershipStatus::class,
    ],
    [
      'class' => \shopack\base\frontend\widgets\ActionColumn::class,
      'header' => /* MemberModel::canCreate() ? Html::createButton(null, [
        'create',
        'mbrshpMemberID' => $mbrshpMemberID ?? $_GET['mbrshpMemberID'] ?? null,
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
          $model->mbrshpCreatedAt,
          $model->createdByUser,
          $model->mbrshpUpdatedAt,
          $model->updatedByUser,
          // $model->mbrshpRemovedAt,
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
