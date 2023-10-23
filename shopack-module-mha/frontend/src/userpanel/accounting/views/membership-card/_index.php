<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\frontend\common\widgets\grid\GridView;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\common\helpers\StringHelper;
use iranhmusic\shopack\mha\common\enums\enuMemberMembershipStatus;
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
        'attribute' => 'uasSaleableID',
        'label' => 'عنوان',
        'value' => function ($model, $key, $index, $widget) {
          return $model->saleable->slbName;
        },
      ],
      [
        'attribute' => 'uasValidFromDate',
        'label' => 'اعتبار از',
        'format' => 'jalali',
      ],
      [
        'attribute' => 'uasValidToDate',
        'label' => 'اعتبار تا',
        'format' => 'jalali',
      ],
      // 'uasVoucherID',
      // [
      //   'attribute' => 'uasStatus',
      //   'class' => \shopack\base\frontend\common\widgets\grid\EnumDataColumn::class,
      //   'enumClass' => enuMemberMembershipStatus::class,
      // ],
      // [
      //   'class' => \shopack\base\frontend\common\widgets\ActionColumn::class,
      //   'header' => Html::createButton('تمدید عضویت', [
      //     '/mha/accounting/membership/add-to-basket'], [
      //       // 'localdbs' => 'basketdata=basket',
      //     ]),
      //   'template' => false,
      // ],
      [
        'attribute' => 'rowDate',
        'noWrap' => true,
        'format' => 'raw',
        'label' => 'ایجاد / ویرایش',
        'value' => function($model) {
          return Html::formatRowDates(
            $model->uasCreatedAt,
            null, //$model->createdByUser,
            $model->uasUpdatedAt,
            // $model->updatedByUser,
            // $model->uasRemovedAt,
            // $model->removedByUser,
          );
        },
      ],
    ],
  ]);
?>
