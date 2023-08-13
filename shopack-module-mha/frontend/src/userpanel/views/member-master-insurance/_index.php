<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\frontend\widgets\grid\GridView;
use shopack\base\common\helpers\Url;
use shopack\base\frontend\helpers\Html;
use shopack\base\common\helpers\StringHelper;
use iranhmusic\shopack\mha\common\enums\enuMemberStatus;
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
        'attribute' => 'mbrminshstMasterInsTypeID',
        'value' => function ($model, $key, $index, $widget) {
          return $model->masterInsuranceType->masterInsurer->minsName . ' - ' . $model->masterInsuranceType->minstypName;
        },
      ],
      'mbrminshstSubstation',
      'mbrminshstStartDate:jalali',
      'mbrminshstEndDate:jalali',
      'mbrminshstInsuranceCode',
      'mbrminshstCoCode',
      'mbrminshstCoName',
      'mbrminshstIssuanceDate:jalali',
      [
        'class' => \shopack\base\frontend\widgets\ActionColumn::class,
        'header' => MemberModel::canCreate() ? Html::createButton() : Yii::t('app', 'Actions'),
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
            $model->mbrminshstCreatedAt,
            null, // $model->createdByUser,
            $model->mbrminshstUpdatedAt,
            // $model->updatedByUser,
            // $model->mbrminshstRemovedAt,
            // $model->removedByUser,
          );
        },
      ],
    ],
  ]);
?>
