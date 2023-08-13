<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\frontend\widgets\grid\GridView;
use shopack\base\frontend\helpers\Html;
use shopack\base\common\helpers\StringHelper;
use iranhmusic\shopack\mha\frontend\common\models\MemberMembershipModel;
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
        // 'class' => \iranhmusic\shopack\mha\frontend\common\widgets\grid\MembershipDataColumn::class,
        'attribute' => 'mbrshpMembershipID',
        // 'label' => 'طرح فروش',
        'value' => function ($model, $key, $index, $widget) {
          return $model->membership->mshpTitle;
        },
      ],
      'mbrshpStartDate:jalali',
			'mbrshpEndDate:jalali',
      // 'mbrshpVoucherID',
      // [
      //   'attribute' => 'mbrshpStatus',
      //   'class' => \shopack\base\frontend\widgets\grid\EnumDataColumn::class,
      //   'enumClass' => enuMemberMembershipStatus::class,
      // ],
      [
        'class' => \shopack\base\frontend\widgets\ActionColumn::class,
        'header' => MemberMembershipModel::canCreate()
          ? Html::createButton('تمدید عضویت', ['/mha/membership/add-to-basket'], [
            // 'localdbs' => 'basketdata=basket',
          ])
          : Yii::t('app', 'Actions'),
        'template' => false,
      ],
      [
        'attribute' => 'rowDate',
        'noWrap' => true,
        'format' => 'raw',
        'label' => 'ایجاد / ویرایش',
        'value' => function($model) {
          return Html::formatRowDates(
            $model->mbrshpCreatedAt,
            null, //$model->createdByUser,
            $model->mbrshpUpdatedAt,
            // $model->updatedByUser,
            // $model->mbrshpRemovedAt,
            // $model->removedByUser,
          );
        },
      ],
    ],
  ]);
?>
