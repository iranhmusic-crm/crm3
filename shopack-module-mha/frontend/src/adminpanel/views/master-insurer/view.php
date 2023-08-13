<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\frontend\widgets\PopoverX;
use shopack\base\common\helpers\Url;
use shopack\base\frontend\widgets\tabs\Tabs;
use shopack\base\frontend\widgets\DetailView;
use shopack\base\frontend\helpers\Html;
use iranhmusic\shopack\mha\common\enums\enuInsurerStatus;
use iranhmusic\shopack\mha\frontend\common\models\MasterInsurerModel;

$this->title = Yii::t('mha', 'Master Insurer') . ': ' . $model->minsID . ' - ' . $model->minsName;
$this->params['breadcrumbs'][] = Yii::t('mha', 'Music House');
$this->params['breadcrumbs'][] = ['label' => Yii::t('mha', 'Master Insurers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="master-insurer-view w-100">
  <div class='card border-default'>
		<div class='card-header bg-default'>
			<div class="float-end">
				<?= MasterInsurerModel::canCreate() ? Html::createButton() : '' ?>
        <?= $model->canUpdate()   ? Html::updateButton(null,   ['id' => $model->minsID]) : '' ?>
        <?= $model->canDelete()   ? Html::deleteButton(null,   ['id' => $model->minsID]) : '' ?>
        <?= $model->canUndelete() ? Html::undeleteButton(null, ['id' => $model->minsID]) : '' ?>
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
            'attributes' => [
              'minsCreatedAt:jalaliWithTime',
              [
                'attribute' => 'minsCreatedBy_User',
                'format' => 'raw',
                'value' => $model->createdByUser->actorName ?? '-',
              ],
              'minsUpdatedAt:jalaliWithTime',
              [
                'attribute' => 'minsUpdatedBy_User',
                'format' => 'raw',
                'value' => $model->updatedByUser->actorName ?? '-',
              ],
              'minsRemovedAt:jalaliWithTime',
              [
                'attribute' => 'minsRemovedBy_User',
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

    <div class='card-tabs'>
      <?php $tabs = Tabs::begin($this); ?>

      <?php $tabs->beginTabPage('مشخصات'); ?>
        <?php
          echo DetailView::widget([
            'model' => $model,
            'enableEditMode' => false,
            'attributes' => [
              'minsID',
              [
                'attribute' => 'minsStatus',
                'value' => enuInsurerStatus::getLabel($model->minsStatus),
              ],
              'minsName',
            ],
          ]);
        ?>

      <?php $tabs->endTabPage(); ?>

      <?php $tabs->newAjaxTabPage(Yii::t('mha', 'Master Insurer Types'), [
          '/mha/master-insurer-type/index',
          'minstypMasterInsurerID' => $model->minsID,
        ],
        'master-insurer-type'
      ); ?>

      <?php $tabs->end(); ?>
    </div>
  </div>
</div>
