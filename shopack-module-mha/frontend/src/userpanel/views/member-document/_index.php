<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use iranhmusic\shopack\mha\common\enums\enuBasicDefinitionType;
use shopack\base\frontend\common\widgets\grid\GridView;
use shopack\base\common\helpers\StringHelper;
use shopack\base\frontend\common\helpers\Html;
use iranhmusic\shopack\mha\common\enums\enuMemberDocumentStatus;
use iranhmusic\shopack\mha\frontend\common\models\BasicDefinitionModel;
use iranhmusic\shopack\mha\frontend\common\models\DocumentSearchModel;
use iranhmusic\shopack\mha\frontend\common\models\KanoonModel;
use iranhmusic\shopack\mha\frontend\common\models\MemberDocumentModel;
use shopack\base\common\helpers\ArrayHelper;
use shopack\base\frontend\common\widgets\JsonTableGrid;

?>

<div class='row'>
  <div class='col-8'>
    <?php
      // echo Alert::widget(['key' => 'shoppingcart']);

      // if (isset($statusReport))
      // 	echo (is_array($statusReport) ? Html::icon($statusReport[0], ['plugin' => 'glyph']) . ' ' . $statusReport[1] : $statusReport);

      $rejectReasons = ArrayHelper::map(BasicDefinitionModel::find()
        ->where(['bdfType' => enuBasicDefinitionType::MemberDocumentyRejectReason])
        ->noLimit()
        ->asArray()
        ->all(),
        'bdfID',
        'bdfName'
      );

      echo GridView::widget([
        'id' => StringHelper::generateRandomId(),
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'columns' => [
          [
            'class' => 'kartik\grid\SerialColumn',
          ],
          [
            'class' => 'shopack\base\frontend\common\widgets\grid\ExpandRowColumn',
            'value' => function ($model, $key, $index, $column) {
              return GridView::ROW_COLLAPSED;
              // this bahaviour moved to gridview::run for covering initialize error
              // return ($selected_adngrpID == $model->adngrpID ? GridView::ROW_EXPANDED : GridView::ROW_COLLAPSED);
            },
            'detail' => function ($model) use($rejectReasons) {
              $result = [];
              $result[] = '<tr><td>' . implode('</td><td>', [
                '#',
                'تاریخ',
                'وضعیت',
                'توضیح',
              ]) . '</td></tr>';
              if (empty($model->mbrdocHistory == false)) {
                $items = array_reverse($model->mbrdocHistory);
                foreach ($items as $k => $item) {
                  $status = empty($item['status']) ? '' : enuMemberDocumentStatus::getLabel($item['status']);

                  if ($item['status'] == enuMemberDocumentStatus::Rejected) {
                    $reasons = [];

                    foreach ($model->mbrdocRejectReasonIDs as $r) {
                      if (isset($rejectReasons[$r]))
                        $reasons[] = $rejectReasons[$r];
                    }

                    if (empty($reasons) == false) {
                      $status .= ' (' . implode(' - ', $reasons) . ')';
                    }
                  }

                  $result[] = '<tr><td>' . implode('</td><td>', [
                    $k + 1,
                    empty($item['at']) ? '' : Yii::$app->formatter->asJalaliWithTime($item['at']),
                    $status,
                    $item['comment'] ?? '',
                  ]) . '</td></tr>';
                }
              }

              $result = implode('', $result);

              $paramsTable = JsonTableGrid::formatParamsDataAsTable($model->mbrdocExtraParams, $model->document->docExtraParamsSchema, function($value, $schema) {
                if (str_starts_with($schema['type'], 'mha:bdef:')) {
                  $model = BasicDefinitionModel::findOne($value);
                  return $model->bdfName;
                }

                if ($schema['type'] == 'mha:kanoon') {
                  $model = KanoonModel::findOne($value);
                  return $model->knnName;
                }

                return null;
              });

              return "<div class='row'>"

                . "<div class='col'>"
                . Html::div($model->getAttributeLabel('mbrdocHistory') . ':')
                . '<table class="table table-bordered table-striped">'
                . $result
                . '</table>'
                . "</div>"

                . "<div class='col'>"
                . Html::div($model->getAttributeLabel('mbrdocExtraParams') . ':')
                . $paramsTable
                . "</div>"

                . "</div>";

              // return Html::div($model->getAttributeLabel('mbrdocHistory') . ':')
              //   . '<table class="table table-bordered table-striped">'
              //   . $result
              //   . '</table>'
              //   . Html::div($model->getAttributeLabel('mbrdocExtraParams') . ':')
              //   . $paramsTable;
            },
          ],
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
            'class' => \shopack\base\frontend\common\widgets\grid\EnumDataColumn::class,
            'enumClass' => enuMemberDocumentStatus::class,
          ],
          [
            'class' => \shopack\base\frontend\common\widgets\ActionColumn::class,
            'header' => MemberDocumentModel::canCreate() ? Html::createButton() : Yii::t('app', 'Actions'),
            'template' => '{delete}',

            'visibleButtons' => [
              'update' => function ($model, $key, $index) {
                return $model->canUpdate();
              },
              'delete' => function ($model, $key, $index) {
                return $model->canDelete();
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
                null, // $model->createdByUser,
                $model->mbrdocUpdatedAt,
                // $model->updatedByUser,
                // $model->mbrdocRemovedAt,
                // $model->removedByUser,
              );
            },
          ],
        ],
      ]);
    ?>
  </div>
  <div class='col-4'>
    <div class='card'>
      <div class='card-header'>
        <div class='card-title'><?= Yii::t('mha', 'Required Documents') ?></div>
      </div>
      <div class='card-body'>
        <?php
          $doctypesSearchModel = new DocumentSearchModel();
          $doctypesDataProvider = $doctypesSearchModel->getDocumentTypesForMember(Yii::$app->user->id);

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
                'format' => 'raw',
                'value' => function ($model, $key, $index, $widget) {
                  $providedCount = $model->providedCount ?? 0;

                  if ($providedCount == 0)
                    return "<div class='badge bg-danger'>{$providedCount}</div>";

                  return "<div class='badge bg-success'>{$providedCount}</div>";
                },
              ],
              [
                'class' => \shopack\base\frontend\common\widgets\ActionColumn::class,
                'template' => '{create}',
                'buttons' => [
                  'create' => function ($url, $model, $key) {
                    return Html::createButton('افزودن', [
                      'docID' => $model->docID,
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
</div>
