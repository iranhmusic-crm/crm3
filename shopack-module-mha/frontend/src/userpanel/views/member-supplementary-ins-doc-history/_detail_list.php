<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\frontend\widgets\grid\GridView;
use shopack\base\frontend\helpers\Html;
use shopack\base\common\helpers\StringHelper;
use iranhmusic\shopack\mha\common\enums\enuInsurerDocStatus;
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
      'mbrsinsdochstID',
      'mbrsinsdochstSupplementaryInsDocID',
      [
        'class' => \shopack\base\frontend\widgets\grid\EnumDataColumn::class,
        'enumClass' => enuInsurerDocStatus::class,
        'attribute' => 'mbrsinsdochstAction',
      ],
      [
        'attribute' => 'rowDate',
        'noWrap' => true,
        'format' => 'raw',
        'label' => 'ایجاد / ویرایش',
        'value' => function($model) {
          return Html::formatRowDates(
            $model->mbrsinsdochstCreatedAt,
            null, // $model->createdByUser,
            // $model->mbrsinsdocUpdatedAt,
            // $model->updatedByUser,
            // $model->mbrsinsdocRemovedAt,
            // $model->removedByUser,
          );
        },
      ],
    ],
  ]);
?>
