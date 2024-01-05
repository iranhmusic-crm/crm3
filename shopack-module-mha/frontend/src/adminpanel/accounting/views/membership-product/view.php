<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\common\helpers\ArrayHelper;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\frontend\common\widgets\PopoverX;
use shopack\base\frontend\common\widgets\DetailView;
use shopack\base\common\accounting\enums\enuProductStatus;
use iranhmusic\shopack\mha\frontend\common\accounting\models\MembershipSaleableModel;

$modelClass = Yii::$app->controller->modelClass;

$this->params['breadcrumbs'][] = Yii::t('mha', 'Music House');
$this->params['breadcrumbs'][] = Yii::t('mha', 'Services Definition and Fee');
$this->params['breadcrumbs'][] = ['label' => Yii::t('mha', 'Membership Product'), 'url' => ['index']];
$this->title = Yii::t('mha', 'Membership Product') . ': ' . $model->prdID . ' - ' . $model->prdName;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="membership-product-view w-100">
  <div class='card'>
		<div class='card-header'>
			<div class="float-end">
				<?= $modelClass::canCreate() ? Html::createButton() : '' ?>
        <?= $model->canUpdate()   ? Html::updateButton(null,   ['id' => $model->prdID]) : '' ?>
        <?= $model->canDelete()   ? Html::deleteButton(null,   ['id' => $model->prdID]) : '' ?>
        <?= $model->canUndelete() ? Html::undeleteButton(null, ['id' => $model->prdID]) : '' ?>
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
              'prdCreatedAt:jalaliWithTime',
              [
                'attribute' => 'prdCreatedBy_User',
                'format' => 'raw',
                'value' => $model->createdByUser->actorName ?? '-',
              ],
              'prdUpdatedAt:jalaliWithTime',
              [
                'attribute' => 'prdUpdatedBy_User',
                'format' => 'raw',
                'value' => $model->updatedByUser->actorName ?? '-',
              ],
              'prdRemovedAt:jalaliWithTime',
              [
                'attribute' => 'prdRemovedBy_User',
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
          'cols' => 2,
          'isVertical' => false,
          'attributes' => [
            'prdID',
            [
              'attribute' => 'prdStatus',
              'value' => enuProductStatus::getLabel($model->prdStatus),
            ],
            'prdName',
            // 'prdCode',
            // 'prdDesc',
            // 'prdValidFromDate',
            // 'prdValidToDate',
            // 'prdValidFromHour',
            // 'prdValidToHour',
            // 'prdDurationMinutes',
            // 'prdStartAtFirstUse',
            // 'prdPrivs',
            // 'prdVAT',
            [
              'attribute' => 'prdUnitID',
              'value' => $model->unit->untName,
            ],
            // 'prdQtyIsDecimal',
            // 'prdInStockQty',
            // 'prdOrderedQty',
            // 'prdReturnedQty',
            // 'prdI18NData',
          ],
        ]);
      ?>
    </div>

    <div class='card-body'>
      <div class='card'>
        <div class='card-header'>
          <div class='card-title'><?= Yii::t('mha', 'Membership Saleables') ?></div>
        </div>
        <div class='card-body'>
          <?php
            echo Yii::$app->runAction('mha/accounting/membership-saleable', ArrayHelper::merge($_GET, [
              'isPartial' => true,
              'params' => [
                'slbProductID' => $model->prdID,
              ],
            ]));
          ?>
        </div>
      </div>
    </div>

  </div>

</div>
