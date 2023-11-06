<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\frontend\common\widgets\grid\GridView;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\common\helpers\StringHelper;
// use shopack\aaa\common\enums\enuMemberMemberGroupStatus;
use iranhmusic\shopack\mha\frontend\common\models\MemberMemberGroupModel;
use iranhmusic\shopack\mha\frontend\common\models\MemberModel;
?>

<?php
  $usragpUserID = Yii::$app->request->queryParams['mbrmgpMemberID'] ?? null;
?>

<?php
	// echo Alert::widget(['key' => 'shoppingcart']);

	if (isset($statusReport))
		echo $statusReport;

  // (is_array($statusReport) ? Html::icon($statusReport[0], ['plugin' => 'glyph']) . ' ' . $statusReport[1] : $statusReport);

  $columns = [
    [
      'class' => 'kartik\grid\SerialColumn',
    ],
    'mbrmgpID',
  ];

  if (empty($usragpUserID)) {
    $columns = array_merge($columns, [
      [
        'class' => \iranhmusic\shopack\mha\frontend\common\widgets\grid\MemberDataColumn::class,
        'attribute' => 'mbrmgpMemberID',
        'format' => 'raw',
        'value' => function ($model, $key, $index, $widget) {
          return Html::a($model->member->displayName(), ['/mha/member/view', 'id' => $model->mbrmgpMemberID]); //, ['class' => ['btn', 'btn-sm', 'btn-outline-secondary']]);
        },
      ],
    ]);
  }

  $columns = array_merge($columns, [
    [
      'attribute' => 'mbrmgpMemberGroupID',
      'format' => 'raw',
      'value' => function ($model, $key, $index, $widget) {
        return Html::a($model->memberGroup->mgpName, ['/mha/member-group/view', 'id' => $model->mbrmgpMemberGroupID]);
      },
    ],
    'mbrmgpStartAt:jalaliWithTime',
    'mbrmgpEndAt:jalaliWithTime',
    // [
    //   'class' => \shopack\base\frontend\common\widgets\grid\EnumDataColumn::class,
    //   'enumClass' => enuMemberMemberGroupStatus::class,
    //   'attribute' => 'mbrmgpStatus',
    // ],
    [
      'class' => \shopack\base\frontend\common\widgets\ActionColumn::class,
      'header' => MemberMemberGroupModel::canCreate() ? Html::createButton(null, [
        'create',
        'mbrmgpMemberID' => $usragpUserID ?? $_GET['mbrmgpMemberID'] ?? null,
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
          $model->mbrmgpCreatedAt,
          $model->createdByUser,
          $model->mbrmgpUpdatedAt,
          $model->updatedByUser,
          $model->mbrmgpRemovedAt,
          $model->removedByUser,
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
