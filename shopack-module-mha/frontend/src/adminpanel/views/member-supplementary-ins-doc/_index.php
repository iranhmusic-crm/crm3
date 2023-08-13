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

  $columns = [
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
      'detail' => function ($model) {
        $result = [];
        $result[] = '<tr><td>' . implode('</td><td>', [
          '#',
          'تاریخ',
          'وضعیت',
        ]) . '</td></tr>';
        if (empty($model->mbrsinsdocHistory == false)) {
          $items = array_reverse($model->mbrsinsdocHistory);
          foreach ($items as $k => $item)
          {
            $result[] = '<tr><td>' . implode('</td><td>', [
              $k + 1,
              Yii::$app->formatter->asJalaliWithTime($item['at']),
              enuInsurerDocStatus::getLabel($item['status']),
            ]) . '</td></tr>';
          }
        }
        return '<table class="table table-bordered table-striped">' . implode('', $result) . '</table>';
      },
    ],
  ];

  if (empty($mbrsinsdocMemberID)) {
    $columns = array_merge($columns, [
      [
        'class' => \iranhmusic\shopack\mha\frontend\common\widgets\grid\MemberDataColumn::class,
        'attribute' => 'mbrsinsdocMemberID',
        'format' => 'raw',
        'value' => function ($model, $key, $index, $widget) {
          return Html::a($model->member->displayName(), ['/mha/member/view', 'id' => $model->mbrsinsdocMemberID]); //, ['class' => ['btn', 'btn-sm', 'btn-outline-secondary']]);
        },
      ],
    ]);
  }

  $columns = array_merge($columns, [
    [
      'class' => \iranhmusic\shopack\mha\frontend\common\widgets\grid\SupplementaryInsurerDataColumn::class,
      'attribute' => 'mbrsinsdocSupplementaryInsurerID',
      'format' => 'raw',
      'value' => function ($model, $key, $index, $widget) {
        return $model->supplementaryInsurer->sinsName;
      },
    ],

    'mbrsinsdocDocNumber',
    'mbrsinsdocDocDate:jalali',
    [
      'attribute' => 'mbrsinsdocStatus',
      'value' => function ($model, $key, $index, $widget) {
        return enuInsurerDocStatus::getLabel($model->mbrsinsdocStatus);
      },
      'filter' => Html::activeDropDownList(
        $searchModel,
        'mbrsinsdocStatus',
        enuInsurerDocStatus::getList(),
        [
          'class' => 'form-control',
          'prompt' => '-- همه --',
          'encode' => false,
          // 'options' => $catOptions,
        ]
      ),
    ],
    [
      'class' => \shopack\base\frontend\widgets\ActionColumn::class,
      'header' => MemberModel::canCreate() ? Html::createButton(null, [
        'create',
        'mbrsinsdocMemberID' => $mbrsinsdocMemberID ?? $_GET['mbrsinsdocMemberID'] ?? null,
      ]) : Yii::t('app', 'Actions'),
      // 'template' => '{update} {delete}{undelete}',
      'template' => '{accept} {reject} {waitForDocument} {documented} {delivered}',

      'buttons' => [
        'accept' => function ($url, $model, $key) {
          return Html::confirmButton(enuInsurerDocStatus::getLabel(enuInsurerDocStatus::Accepted), [
            'change-status',
            'status' => enuInsurerDocStatus::Accepted,
            'id' => $model->mbrsinsdocID,
            // 'ref' => Url::toRoute(['view', 'id' => $model->mbrUserID], true),
          ], Yii::t('mha', 'Are you sure you want to change status to {status}', ['status' => enuInsurerDocStatus::getLabel(enuInsurerDocStatus::Accepted)]), [
            'class' => 'btn btn-sm btn-success',
          ]);
        },
        'reject' => function ($url, $model, $key) {
          return Html::confirmButton(enuInsurerDocStatus::getLabel(enuInsurerDocStatus::Rejected), [
            'change-status',
            'status' => enuInsurerDocStatus::Rejected,
            'id' => $model->mbrsinsdocID,
            // 'ref' => Url::toRoute(['view', 'id' => $model->mbrUserID], true),
          ], Yii::t('mha', 'Are you sure you want to change status to {status}', ['status' => enuInsurerDocStatus::getLabel(enuInsurerDocStatus::Rejected)]), [
            'class' => 'btn btn-sm btn-warning',
          ]);
        },
        'waitForDocument' => function ($url, $model, $key) {
          return Html::confirmButton(enuInsurerDocStatus::getLabel(enuInsurerDocStatus::WaitForDocument), [
            'change-status',
            'status' => enuInsurerDocStatus::WaitForDocument,
            'id' => $model->mbrsinsdocID,
            // 'ref' => Url::toRoute(['view', 'id' => $model->mbrUserID], true),
          ], Yii::t('mha', 'Are you sure you want to change status to {status}', ['status' => enuInsurerDocStatus::getLabel(enuInsurerDocStatus::WaitForDocument)]), [
            'class' => 'btn btn-sm btn-primary',
          ]);
        },
        'documented' => function ($url, $model, $key) {
          return Html::confirmButton(enuInsurerDocStatus::getLabel(enuInsurerDocStatus::Documented), [
            'change-status',
            'status' => enuInsurerDocStatus::Documented,
            'id' => $model->mbrsinsdocID,
            // 'ref' => Url::toRoute(['view', 'id' => $model->mbrUserID], true),
          ], Yii::t('mha', 'Are you sure you want to change status to {status}', ['status' => enuInsurerDocStatus::getLabel(enuInsurerDocStatus::Documented)]), [
            'class' => 'btn btn-sm btn-primary',
          ]);
        },
        'delivered' => function ($url, $model, $key) {
          return Html::confirmButton(enuInsurerDocStatus::getLabel(enuInsurerDocStatus::DocumentDeliveredToMember), [
            'change-status',
            'status' => enuInsurerDocStatus::DocumentDeliveredToMember,
            'id' => $model->mbrsinsdocID,
            // 'ref' => Url::toRoute(['view', 'id' => $model->mbrUserID], true),
          ], Yii::t('mha', 'Are you sure you want to change status to {status}', ['status' => enuInsurerDocStatus::getLabel(enuInsurerDocStatus::DocumentDeliveredToMember)]), [
            'class' => 'btn btn-sm btn-primary',
          ]);
        },
      ],

      'visibleButtons' => [
        'accept' => function ($model, $key, $index) {
          return $model->canAccept();
        },
        'reject' => function ($model, $key, $index) {
          return $model->canReject();
        },
        'waitForDocument' => function ($model, $key, $index) {
          return $model->canWaitForDocument();
        },
        'documented' => function ($model, $key, $index) {
          return $model->canSetAsDocumented();
        },
        'delivered' => function ($model, $key, $index) {
          return $model->canSetAsDelivered();
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
          $model->mbrsinsdocCreatedAt,
          $model->createdByUser,
          $model->mbrsinsdocUpdatedAt,
          $model->updatedByUser,
          // $model->mbrsinsdocRemovedAt,
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
