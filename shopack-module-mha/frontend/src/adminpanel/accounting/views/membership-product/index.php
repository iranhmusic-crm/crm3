<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\common\helpers\StringHelper;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\frontend\common\widgets\grid\GridView;
use shopack\base\common\accounting\enums\enuProductStatus;
use iranhmusic\shopack\mha\frontend\common\accounting\models\MembershipProductModel;

$this->title = Yii::t('mha', 'Membership Products');
$this->params['breadcrumbs'][] = Yii::t('mha', 'Music House');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="membership-product-index w-100">
  <div class='card'>
		<div class='card-header'>
			<div class="float-end">
        <?= MembershipProductModel::canCreate() ? Html::createButton() : '' ?>
			</div>
      <div class='card-title'><?= Html::encode($this->title) ?></div>
			<div class="clearfix"></div>
		</div>

    <div class='card-body'>
      <?php
      echo GridView::widget([
        'id' => StringHelper::generateRandomId(),
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'columns' => [
          [
            'class' => 'kartik\grid\SerialColumn',
          ],
          'prdID',
          'prdCode',
          [
            'attribute' => 'prdName',
            'format' => 'raw',
            'value' => function ($model, $key, $index, $widget) {
              return Html::a($model->prdName, ['view', 'id' => $model->prdID]);
            },
          ],
          // 'prdDesc',
          // 'prdValidFromDate',
          // 'prdValidToDate',
          // 'prdValidFromHour',
          // 'prdValidToHour',
          // 'prdDurationMinutes',
          // 'prdStartAtFirstUse',
          // 'prdPrivs',
          // 'prdVAT',
          // 'prdUnitID',
          // 'prdQtyIsDecimal',
          // 'prdInStockQty',
          // 'prdOrderedQty',
          // 'prdReturnedQty',
          // 'prdI18NData',
          [
            'class' => \shopack\base\frontend\common\widgets\grid\EnumDataColumn::class,
            'enumClass' => enuProductStatus::class,
            'attribute' => 'prdStatus',
          ],
          [
            'class' => \shopack\base\frontend\common\widgets\ActionColumn::class,
            'header' => MembershipProductModel::canCreate() ? Html::createButton() : Yii::t('app', 'Actions'),
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
                $model->prdCreatedAt,
                $model->createdByUser,
                $model->prdUpdatedAt,
                $model->updatedByUser,
                $model->prdRemovedAt,
                $model->removedByUser,
              );
            },
          ],
        ],
      ]);
      ?>
    </div>
  </div>
</div>
