<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\frontend\widgets\grid\GridView;
use shopack\base\common\helpers\Url;
use shopack\base\frontend\helpers\Html;
use shopack\base\common\helpers\StringHelper;
use iranhmusic\shopack\mha\common\enums\enuInsurerDocStatus;
use iranhmusic\shopack\mha\frontend\common\models\MemberModel;
?>

<?php
	// echo Alert::widget(['key' => 'shoppingcart']);

	// if (isset($statusReport))
	// 	echo (is_array($statusReport) ? Html::icon($statusReport[0], ['plugin' => 'glyph']) . ' ' . $statusReport[1] : $statusReport);

  echo GridView::widget([
    'id' => StringHelper::generateRandomId(),
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,

    'columns' => [
      [
        'class' => 'kartik\grid\SerialColumn',
      ],
      [
        'class' => 'kartik\grid\ExpandRowColumn',
        'value' => function ($model, $key, $index, $column) /*use($selected_adngrpID)*/ {
          return GridView::ROW_COLLAPSED;
          // this bahaviour moved to gridview::run for covering initialize error
          // return ($selected_adngrpID == $model->adngrpID ? GridView::ROW_EXPANDED : GridView::ROW_COLLAPSED);
        },
        'expandOneOnly' => true,
        'detailAnimationDuration' => 150,
        'detailUrl' => Url::to(['/mha/member-master-ins-doc-history/detail-list']),
      ],

      // [
      //   'class' => \iranhmusic\shopack\mha\frontend\common\widgets\grid\MemberDataColumn::class,
      //   'attribute' => 'mbrminsdocMemberID',
      //   'format' => 'raw',
      //   'value' => function ($model, $key, $index, $widget) {
      //     return Html::a($model->member->displayName(), ['/mha/member/view', 'id' => $model->mbrminsdocMemberID]); //, ['class' => ['btn', 'btn-sm', 'btn-outline-secondary']]);
      //   },
      // ],
      'mbrminsdocDocNumber',
      'mbrminsdocDocDate:jalali',
      [
        'class' => \shopack\base\frontend\widgets\grid\EnumDataColumn::class,
        'enumClass' => enuInsurerDocStatus::class,
        'attribute' => 'mbrminsdocStatus',
      ],
      // [
      //   'class' => \shopack\base\frontend\widgets\ActionColumn::class,
      //   'header' => MemberModel::canCreate() ? Html::createButton(null, [
      //     'create',
      //     'mbrminsdocMemberID' => $mbrminsdocMemberID ?? $_GET['mbrminsdocMemberID'] ?? null,
      //   ]) : Yii::t('app', 'Actions'),

      //   'template' => '{accept} {reject} {waitForDocument} {documented} {delivered}',

      //   'buttons' => [
      //     'accept' => function ($url, $model, $key) {
      //       return Html::confirmButton(enuInsurerDocStatus::getLabel(enuInsurerDocStatus::Accepted), [
      //         'change-status',
      //         'status' => enuInsurerDocStatus::Accepted,
      //         'id' => $model->mbrminsdocID,
      //         // 'ref' => Url::toRoute(['view', 'id' => $model->mbrUserID], true),
      //       ], Yii::t('mha', 'Are you sure you want to change status to {status}', ['status' => enuInsurerDocStatus::getLabel(enuInsurerDocStatus::Accepted)]), [
      //         'class' => 'btn btn-sm btn-success',
      //       ]);
      //     },
      //     'reject' => function ($url, $model, $key) {
      //       return Html::confirmButton(enuInsurerDocStatus::getLabel(enuInsurerDocStatus::Rejected), [
      //         'change-status',
      //         'status' => enuInsurerDocStatus::Rejected,
      //         'id' => $model->mbrminsdocID,
      //         // 'ref' => Url::toRoute(['view', 'id' => $model->mbrUserID], true),
      //       ], Yii::t('mha', 'Are you sure you want to change status to {status}', ['status' => enuInsurerDocStatus::getLabel(enuInsurerDocStatus::Rejected)]), [
      //         'class' => 'btn btn-sm btn-warning',
      //       ]);
      //     },
      //     'waitForDocument' => function ($url, $model, $key) {
      //       return Html::confirmButton(enuInsurerDocStatus::getLabel(enuInsurerDocStatus::WaitForDocument), [
      //         'change-status',
      //         'status' => enuInsurerDocStatus::WaitForDocument,
      //         'id' => $model->mbrminsdocID,
      //         // 'ref' => Url::toRoute(['view', 'id' => $model->mbrUserID], true),
      //       ], Yii::t('mha', 'Are you sure you want to change status to {status}', ['status' => enuInsurerDocStatus::getLabel(enuInsurerDocStatus::WaitForDocument)]), [
      //         'class' => 'btn btn-sm btn-primary',
      //       ]);
      //     },
      //     'documented' => function ($url, $model, $key) {
      //       return Html::confirmButton(enuInsurerDocStatus::getLabel(enuInsurerDocStatus::Documented), [
      //         'change-status',
      //         'status' => enuInsurerDocStatus::Documented,
      //         'id' => $model->mbrminsdocID,
      //         // 'ref' => Url::toRoute(['view', 'id' => $model->mbrUserID], true),
      //       ], Yii::t('mha', 'Are you sure you want to change status to {status}', ['status' => enuInsurerDocStatus::getLabel(enuInsurerDocStatus::Documented)]), [
      //         'class' => 'btn btn-sm btn-primary',
      //       ]);
      //     },
      //     'delivered' => function ($url, $model, $key) {
      //       return Html::confirmButton(enuInsurerDocStatus::getLabel(enuInsurerDocStatus::DocumentDeliveredToMember), [
      //         'change-status',
      //         'status' => enuInsurerDocStatus::DocumentDeliveredToMember,
      //         'id' => $model->mbrminsdocID,
      //         // 'ref' => Url::toRoute(['view', 'id' => $model->mbrUserID], true),
      //       ], Yii::t('mha', 'Are you sure you want to change status to {status}', ['status' => enuInsurerDocStatus::getLabel(enuInsurerDocStatus::DocumentDeliveredToMember)]), [
      //         'class' => 'btn btn-sm btn-primary',
      //       ]);
      //     },
      //   ],

      //   'visibleButtons' => [
      //     'accept' => function ($model, $key, $index) {
      //       return $model->canAccept();
      //     },
      //     'reject' => function ($model, $key, $index) {
      //       return $model->canReject();
      //     },
      //     'waitForDocument' => function ($model, $key, $index) {
      //       return $model->canWaitForDocument();
      //     },
      //     'documented' => function ($model, $key, $index) {
      //       return $model->canSetAsDocumented();
      //     },
      //     'delivered' => function ($model, $key, $index) {
      //       return $model->canSetAsDelivered();
      //     },
      //   ],
      // ],
      [
        'attribute' => 'rowDate',
        'noWrap' => true,
        'format' => 'raw',
        'label' => 'ایجاد / ویرایش',
        'value' => function($model) {
          return Html::formatRowDates(
            $model->mbrminsdocCreatedAt,
            null, //$model->createdByUser,
            $model->mbrminsdocUpdatedAt,
            // $model->updatedByUser,
            // $model->mbrminsdocRemovedAt,
            // $model->removedByUser,
          );
        },
      ],
    ],
  ]);
?>
