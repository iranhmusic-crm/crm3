<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\common\helpers\ArrayHelper;
use shopack\base\common\accounting\enums\enuDiscountStatus;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\frontend\common\widgets\PopoverX;
use shopack\base\frontend\common\widgets\DetailView;

$modelClass = Yii::$app->controller->modelClass;

$this->params['breadcrumbs'][] = Yii::t('mha', 'Music House');
$this->params['breadcrumbs'][] = Yii::t('mha', 'Services Definition and Fee');
$this->title = Yii::t('aaa', 'Discount') . ': ' . $model->dscID . ' - ' . $model->dscName;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="discount-view w-100">
  <div class='card'>
		<div class='card-header'>
			<div class="float-end">
				<?= $modelClass::canCreate() ? Html::createButton() : '' ?>
        <?= $model->canUpdate()   ? Html::updateButton(null,   ['id' => $model->dscID]) : '' ?>
        <?= $model->canDelete()   ? Html::deleteButton(null,   ['id' => $model->dscID]) : '' ?>
        <?= $model->canUndelete() ? Html::undeleteButton(null, ['id' => $model->dscID]) : '' ?>
        <?php
          PopoverX::begin([
            // 'header' => 'Hello world',
            'closeButton' => false,
            'toggleButton' => [
              'label' => Yii::t('app', 'Logs'),
              'class' => 'btn btn-sm btn-outline-secondary',
            ],
            'placement' => PopoverX::ALIGN_AUTO_BOTTOM,
          ]);

          echo DetailView::widget([
            'model' => $model,
            'enableEditMode' => false,
            // 'isVertical' => false,
            'attributes' => [
              'dscCreatedAt:jalaliWithTime',
              [
                'attribute' => 'dscCreatedBy_User',
                'format' => 'raw',
                'value' => $model->createdByUser->actorName ?? '-',
              ],
              'dscUpdatedAt:jalaliWithTime',
              [
                'attribute' => 'dscUpdatedBy_User',
                'format' => 'raw',
                'value' => $model->updatedByUser->actorName ?? '-',
              ],
              'dscRemovedAt:jalaliWithTime',
              [
                'attribute' => 'dscRemovedBy_User',
                'format' => 'raw',
                'value' => $model->removedByUser->actorName ?? '-',
              ],
            ],
          ]);

          PopoverX::end();
        ?>
			</div>
      <div class='card-title'><?= Html::encode($this->title) ?></div>
			<div class="clearfix"></div>
		</div>
    <div class='card-body'>
      <?php
        echo DetailView::widget([
          'model' => $model,
          'enableEditMode' => false,
          // 'cols' => 2,
          // 'isVertical' => false,
          'attributes' => [
            'dscID',
            'dscName',
            'dscCodeString',
            'dscTotalMaxCount',
            'dscTotalMaxPrice',
            'dscPerUserMaxCount',
            'dscPerUserMaxPrice',
            'dscValidFrom',
            'dscValidTo',
            'dscAmount',
            'dscAmountType',
            'dscMaxAmount',
            'dscSaleableBasedMultiplier',
            'dscTotalUsedCount',
            'dscTotalUsedPrice',
            [
              'attribute' => 'dscStatus',
              'value' => enuDiscountStatus::getLabel($model->dscStatus),
            ],
          ],
        ]);
      ?>
    </div>
  </div>
</div>
