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
        [
          'class' => \shopack\base\frontend\widgets\ActionColumn::class,
          'header' => MemberDocumentModel::canCreate() ? Html::createButton(null, [
            'create',
            'mbrdocMemberID' => $mbrdocMemberID ?? $_GET['mbrdocMemberID'] ?? null,
          ]) : Yii::t('app', 'Actions'),
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
        <div class='card-title'><?= Yii::t('mha', 'Documents Types') ?></div>
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
                'value' => function ($model, $key, $index, $widget) {
                  return $model->providedCount ?? 0;
                },
              ],
              [
                'class' => \shopack\base\frontend\widgets\ActionColumn::class,
                'template' => '{create}',
                'buttons' => [
                  'create' => function ($url, $model, $key) use ($mbrdocMemberID) {
                    return Html::createButton('درج', [
                      'docID' => $model->docID,
                      'mbrdocMemberID' => $mbrdocMemberID,
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
