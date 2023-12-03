<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\frontend\common\widgets\grid\GridView;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\common\helpers\StringHelper;
use iranhmusic\shopack\mha\frontend\common\models\MemberModel;
use iranhmusic\shopack\mha\common\enums\enuKanoonMembershipDegree;
use iranhmusic\shopack\mha\common\enums\enuMemberKanoonStatus;
use iranhmusic\shopack\mha\common\enums\enuBasicDefinitionType;
use iranhmusic\shopack\mha\frontend\common\models\BasicDefinitionModel;
?>

<?php
  $mbrknnMemberID = Yii::$app->request->queryParams['mbrknnMemberID'] ?? null;
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
      'value' => function ($model, $key, $index, $column) {
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
          'توضیح',
        ]) . '</td></tr>';
        if (empty($model->mbrknnHistory == false)) {
          $items = array_reverse($model->mbrknnHistory);
          foreach ($items as $k => $item)
          {
            $result[] = '<tr><td>' . implode('</td><td>', [
              $k + 1,
              empty($item['at']) ? '' : Yii::$app->formatter->asJalaliWithTime($item['at']),
              empty($item['status']) ? '' : enuMemberKanoonStatus::getLabel($item['status']),
              $item['comment'] ?? '',
            ]) . '</td></tr>';
          }
        }
        return '<table class="table table-bordered table-striped">' . implode('', $result) . '</table>';
      },
    ],
  ];

  if (empty($mbrknnMemberID)) {
    $columns = array_merge($columns, [
      [
        'class' => \iranhmusic\shopack\mha\frontend\common\widgets\grid\MemberDataColumn::class,
        'attribute' => 'mbrknnMemberID',
        'format' => 'raw',
        'value' => function ($model, $key, $index, $widget) {
          return Html::a($model->member->displayName(), ['/mha/member/view', 'id' => $model->mbrknnMemberID]); //, ['class' => ['btn', 'btn-sm', 'btn-outline-secondary']]);
        },
      ],
    ]);
  }

  $columns = array_merge($columns, [
    [
      'class' => \iranhmusic\shopack\mha\frontend\common\widgets\grid\KanoonDataColumn::class,
      'attribute' => 'mbrknnKanoonID',
      'value' => function ($model, $key, $index, $widget) {
        return $model->kanoon->knnName;
      },
    ],
    // [
    //   'attribute' => 'mbrknnParams',
    //   'value' => function ($model, $key, $index, $widget) {
    //     if (empty($model->mbrknnKanoonID)
    //       || empty($model->mbrknnParams)
    //       || empty($model->kanoon->knnDescFieldType)
    //     )
    //       return null;

    //     $desc = $model->mbrknnParams['desc'];
    //     $fieldType = $model->kanoon->knnDescFieldType;
    //     if ($fieldType == 'text')
    //       return $desc;

    //     if (str_starts_with($fieldType, 'mha:')) {
    //       $bdf = substr($fieldType, 4);

    //       $basicDefinitionModel = BasicDefinitionModel::find()
    //         ->andWhere(['bdfID' => $desc])
    //         // ->andWhere(['bdfType' => $bdf])
    //         ->one()
    //       ;

    //       if ($basicDefinitionModel)
    //         return enuBasicDefinitionType::getLabel($bdf) . ': ' . $basicDefinitionModel->bdfName;

    //       return enuBasicDefinitionType::getLabel($bdf) . ': ' . $desc;
    //     }

    //     // $mhaList = enuBasicDefinitionType::getList();
    //     // foreach($mhaList as $k => $v) {
    //     //   if ($fieldType == 'mha:' . $k) {
    //     //     return $v . ': ' . $desc;
    //     //   }
    //     // }

    //     return $desc;
    //   },
    // ],
    'mbrknnIsMaster:boolean',
    [
      'class' => \shopack\base\frontend\common\widgets\grid\EnumDataColumn::class,
      'enumClass' => enuMemberKanoonStatus::class,
      'attribute' => 'mbrknnStatus',
    ],
    [
      'attribute' => 'mbrknnMembershipDegree',
      'value' => function ($model, $key, $index, $widget) {
        return enuKanoonMembershipDegree::getLabel($model->mbrknnMembershipDegree);
      },
      'filter' => Html::activeDropDownList(
        $searchModel,
        'mbrknnMembershipDegree',
        enuKanoonMembershipDegree::getList(),
        [
          'class' => 'form-control',
          'prompt' => '-- همه --',
          'encode' => false,
          // 'options' => $catOptions,
        ]
      ),
    ],
    'mbrknnComment',
    [
      'class' => \shopack\base\frontend\common\widgets\ActionColumn::class,
      'header' => MemberModel::canCreate() ? Html::createButton(null, [
        'create',
        'mbrknnMemberID' => $mbrknnMemberID ?? $_GET['mbrknnMemberID'] ?? null,
      ]) : Yii::t('app', 'Actions'),

      'template' => '{waitForSurvey} {waitForResurvey} {azmoon} {accept} {reject} {changeDegree} {cancel}',

      'buttons' => [
        'waitForSurvey' => function ($url, $model, $key) {
          return Html::confirmButton(enuMemberKanoonStatus::getActionLabel(enuMemberKanoonStatus::WaitForSurvey), [
            'change-status',
            'id' => $model->mbrknnID,
            'status' => enuMemberKanoonStatus::WaitForSurvey,
            // 'ref' => Url::toRoute(['view', 'id' => $model->mbrUserID], true),
          ], Yii::t('mha', 'Are you sure you want to change status to {status}', ['status' => enuMemberKanoonStatus::getActionLabel(enuMemberKanoonStatus::WaitForSurvey)]), [
            'class' => 'btn btn-sm btn-primary',
          ]);
        },
        'waitForResurvey' => function ($url, $model, $key) {
          return Html::confirmButton(enuMemberKanoonStatus::getActionLabel(enuMemberKanoonStatus::WaitForResurvey), [
            'change-status',
            'id' => $model->mbrknnID,
            'status' => enuMemberKanoonStatus::WaitForResurvey,
            // 'ref' => Url::toRoute(['view', 'id' => $model->mbrUserID], true),
          ], Yii::t('mha', 'Are you sure you want to change status to {status}', ['status' => enuMemberKanoonStatus::getActionLabel(enuMemberKanoonStatus::WaitForResurvey)]), [
            'class' => 'btn btn-sm btn-primary',
          ]);
        },
        'azmoon' => function ($url, $model, $key) {
          return Html::confirmButton(enuMemberKanoonStatus::getActionLabel(enuMemberKanoonStatus::Azmoon), [
            'change-status',
            'id' => $model->mbrknnID,
            'status' => enuMemberKanoonStatus::Azmoon,
            // 'ref' => Url::toRoute(['view', 'id' => $model->mbrUserID], true),
          ], Yii::t('mha', 'Are you sure you want to change status to {status}', ['status' => enuMemberKanoonStatus::getActionLabel(enuMemberKanoonStatus::Azmoon)]), [
            'class' => 'btn btn-sm btn-primary',
          ]);
        },
        'accept' => function ($url, $model, $key) {
          return Html::a(enuMemberKanoonStatus::getActionLabel(enuMemberKanoonStatus::Accepted), [
            'accept',
            'id' => $model->mbrknnID,
          ], [
            'class' => 'btn btn-sm btn-success',
            'modal' => true,
            'title' => enuMemberKanoonStatus::getActionLabel(enuMemberKanoonStatus::Accepted),
          ]);
        },
        'reject' => function ($url, $model, $key) {
          return Html::a(enuMemberKanoonStatus::getActionLabel(enuMemberKanoonStatus::Rejected), [
            'reject',
            'id' => $model->mbrknnID,
          ], [
            'class' => 'btn btn-sm btn-warning',
            'modal' => true,
            'title' => enuMemberKanoonStatus::getActionLabel(enuMemberKanoonStatus::Rejected),
          ]);
        },
        'changeDegree' => function ($url, $model, $key) {
          return Html::a('تغیر رده', [
            'change-degree',
            'id' => $model->mbrknnID,
          ], [
            'class' => 'btn btn-sm btn-primary',
            'modal' => true,
            'title' => 'تغیر رده',
          ]);
        },
        'cancel' => function ($url, $model, $key) {
          return Html::confirmButton(enuMemberKanoonStatus::getActionLabel(enuMemberKanoonStatus::Cancelled), [
            'change-status',
            'id' => $model->mbrknnID,
            'status' => enuMemberKanoonStatus::Cancelled,
          ], Yii::t('mha', 'Are you sure you want to change status to {status}', ['status' => enuMemberKanoonStatus::getActionLabel(enuMemberKanoonStatus::Cancelled)]), [
            'class' => 'btn btn-sm btn-warning',
          ]);
        },
      ],

      'visibleButtons' => [
        'waitForSurvey' => function ($model, $key, $index) {
          return $model->canWaitForSurvey();
        },
        'waitForResurvey' => function ($model, $key, $index) {
          return $model->canWaitForResurvey();
        },
        'azmoon' => function ($model, $key, $index) {
          return $model->canAzmoon();
        },
        'accept' => function ($model, $key, $index) {
          return $model->canAccept();
        },
        'reject' => function ($model, $key, $index) {
          return $model->canReject();
        },
        'changeDegree' => function ($model, $key, $index) {
          return $model->canChangeDegree();
        },
        'cancel' => function ($model, $key, $index) {
          return $model->canCancel();
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
          $model->mbrknnCreatedAt,
          $model->createdByUser,
          $model->mbrknnUpdatedAt,
          $model->updatedByUser,
          // $model->mbrknnRemovedAt,
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
