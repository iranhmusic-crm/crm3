<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\frontend\widgets\grid\GridView;
use shopack\base\common\helpers\StringHelper;
use shopack\base\frontend\helpers\Html;
use iranhmusic\shopack\mha\common\enums\enuMemberDocumentStatus;
use iranhmusic\shopack\mha\frontend\common\models\DocumentSearchModel;
use iranhmusic\shopack\mha\frontend\common\models\MemberDocumentModel;
?>

<?php
  $mbrdocMemberID = Yii::$app->request->queryParams['mbrdocMemberID'] ?? null;
?>

<div class='row'>
  <div class='col'>
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
            if (empty($model->mbrdocHistory == false)) {
              $items = array_reverse($model->mbrdocHistory);
              foreach ($items as $k => $item)
              {
                $result[] = '<tr><td>' . implode('</td><td>', [
                  $k + 1,
                  empty($item['at']) ? '' : Yii::$app->formatter->asJalaliWithTime($item['at']),
                  empty($item['status']) ? '' : enuMemberDocumentStatus::getLabel($item['status']),
                  $item['comment'] ?? '',
                ]) . '</td></tr>';
              }
            }
            return '<table class="table table-bordered table-striped">' . implode('', $result) . '</table>';
          },
        ],
      ];

      if (empty($mbrdocMemberID)) {
        $columns = array_merge($columns, [
          [
            'class' => \iranhmusic\shopack\mha\frontend\common\widgets\grid\MemberDataColumn::class,
            'attribute' => 'mbrdocMemberID',
            'format' => 'raw',
            'value' => function ($model, $key, $index, $widget) {
              return Html::a($model->member->displayName(), ['/mha/member/view', 'id' => $model->mbrdocMemberID]); //, ['class' => ['btn', 'btn-sm', 'btn-outline-secondary']]);
            },
          ],
        ]);
      }

      $columns = array_merge($columns, [
        [
          'attribute' => 'mbrdocFileID',
          'format' => 'raw',
          'value' => function ($model, $key, $index, $widget) {
            if ($model->mbrdocFileID == null)
              return null;
            elseif (empty($model->file->fullFileUrl))
              return Yii::t('aaa', 'Uploading...');
            elseif ($model->file->isImage())
              return Html::img($model->file->fullFileUrl, ['style' => ['width' => '75px']]);
            else
              return Html::a(Yii::t('app', 'Download'), $model->file->fullFileUrl);
          },
        ],
        [
          // 'class' => \iranhmusic\shopack\mha\frontend\common\widgets\grid\DocumentDataColumn::class,
          'attribute' => 'mbrdocDocumentID',
          'value' => function ($model, $key, $index, $widget) {
            return $model->document->docName;
          },
        ],
        'mbrdocTitle',
        [
          'attribute' => 'mbrdocStatus',
          'class' => \shopack\base\frontend\widgets\grid\EnumDataColumn::class,
          'enumClass' => enuMemberDocumentStatus::class,
        ],
        'mbrdocComment',
        [
          'class' => \shopack\base\frontend\widgets\ActionColumn::class,
          'header' => MemberDocumentModel::canCreate() ? Html::createButton(null, [
            'create',
            'mbrdocMemberID' => $mbrdocMemberID ?? $_GET['mbrdocMemberID'] ?? null,
          ]) : Yii::t('app', 'Actions'),
          'template' => '{accept} {reject} {delete}{undelete}',

          'buttons' => [
            'accept' => function ($url, $model, $key) {
              return Html::confirmButton(Yii::t('aaa', 'Approve'), [
                'approve',
                'id' => $model->mbrdocID,
              ], Yii::t('aaa', 'Are you sure you want to APPROVE this item?'), [
                'class' => 'btn btn-sm btn-success',
                'ajax' => 'post',
              ]);
            },
            'reject' => function ($url, $model, $key) {
              return Html::a(Yii::t('aaa', 'Reject'), [
                'reject',
                'id' => $model->mbrdocID,
              ], [
                'class' => 'btn btn-sm btn-warning',
                'modal' => true,
                // 'ajax' => 'post',
              ]);
            },
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
            'accept' => function ($model, $key, $index) {
              return $model->canAccept();
            },
            'reject' => function ($model, $key, $index) {
              return $model->canReject();
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
              $model->mbrdocCreatedAt,
              $model->createdByUser,
              $model->mbrdocUpdatedAt,
              $model->updatedByUser,
              // $model->mbrdocRemovedAt,
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
  </div>
<?php if (empty($mbrdocMemberID) == false): ?>
  <div class='col-4'>
    <div class='card border-default'>
      <div class='card-header bg-default'>
        <div class='card-title'><?= Yii::t('mha', 'Required Documents') ?></div>
      </div>
      <div class='card-body'>
        <?php
          $doctypesSearchModel = new DocumentSearchModel();
          $doctypesDataProvider = $doctypesSearchModel->getDocumentTypesForMember($mbrdocMemberID);

          echo GridView::widget([
            'id' => StringHelper::generateRandomId(),
            'dataProvider' => $doctypesDataProvider,
            // 'filterModel' => $doctypesSearchModel,
            'columns' => [
              [
                'class' => 'kartik\grid\SerialColumn',
              ],
              'docName',
              [
                'attribute' => 'providedCount',
                'value' => function ($model, $key, $index, $widget) {
                  if (empty($model->providedCount))
                    return null;
                  return $model->providedCount ?? 0;
                },
              ],
              [
                'class' => \shopack\base\frontend\widgets\ActionColumn::class,
                'template' => '{create}',
                'buttons' => [
                  'create' => function ($url, $model, $key) use ($mbrdocMemberID) {
                    return Html::createButton('افزودن', [
                      'docID' => $model->docID,
                      'mbrdocMemberID' => $mbrdocMemberID,
                    ], [
                      'class' => [
                        'btn',
                        'btn-sm',
                        $model->providedCount > 0 ? 'btn-outline-success' : 'btn-success'
                      ],
                    ]);
                  },
                ],
              ],
            ],
          ]);
        ?>
      </div>
    </div>
  </div>
<?php endif; ?>
</div>
