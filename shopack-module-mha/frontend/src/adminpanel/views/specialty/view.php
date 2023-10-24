<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\frontend\common\widgets\PopoverX;
use shopack\base\common\helpers\Url;
use shopack\base\frontend\common\widgets\DetailView;
use shopack\base\frontend\common\helpers\Html;
use iranhmusic\shopack\mha\common\enums\enuSpecialtyStatus;
use iranhmusic\shopack\mha\frontend\common\models\SpecialtyModel;

$this->title = Yii::t('mha', 'Specialty') . ': ' . $model->spcID;
$this->params['breadcrumbs'][] = Yii::t('mha', 'Music House');
$this->params['breadcrumbs'][] = ['label' => Yii::t('mha', 'Specialties'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="specialty-view w-100">
  <div class='card'>
		<div class='card-header'>
			<div class="float-end">
				<?= SpecialtyModel::canCreate() ? Html::createButton() : '' ?>
        <?= $model->canUpdate()   ? Html::updateButton(null,   ['id' => $model->spcID]) : '' ?>
        <?= $model->canDelete()   ? Html::deleteButton(null,   ['id' => $model->spcID]) : '' ?>
        <?= $model->canUndelete() ? Html::undeleteButton(null, ['id' => $model->spcID]) : '' ?>
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


          PopoverX::end();
        ?>
			</div>
      <div class='card-title'><?= Html::encode($this->title) ?></div>
			<div class="clearfix"></div>
		</div>

    <div class='card-body'>
      <div class='row'>
        <div class='col-8'>
          <?php
            echo DetailView::widget([
              'model' => $model,
              'enableEditMode' => false,
              'cols' => 2,
              'isVertical' => false,
              'attributes' => [
                'spcID',
                'spcRegisterCode',
                [
                  'attribute' => 'spcStatus',
                  'value' => enuSpecialtyStatus::getLabel($model->spcStatus),
                ],
                [
                  'group' => true,
                  // 'cols' => 1,
                  'label' => 'اطلاعات پایه',
                  'groupOptions' => ['class' => 'info-row'],
                ],
                [
                  'attribute' => 'usrEmail',
                  'valueColOptions' => ['class' => ['dir-ltr', 'text-start']],
                  'value' => $model->user->usrEmail,
                ],
                [
                  'attribute' => 'usrEmailApprovedAt',
                  'format' => 'jalaliWithTime',
                  'value' => $model->user->usrEmailApprovedAt,
                ],
                [
                  'attribute' => 'usrMobile',
                  'format' => 'phone',
                  'value' => $model->user->usrMobile,
                ],
                [
                  'attribute' => 'usrMobileApprovedAt',
                  'format' => 'jalaliWithTime',
                  'value' => $model->user->usrMobileApprovedAt,
                ],
                [
                  'attribute' => 'usrSSID',
                  'value' => $model->user->usrSSID,
                ],
                [
                  'attribute' => 'usrGender',
                  'value' => enuGender::getLabel($model->usrGender),
                ],
                [
                  'attribute' => 'usrFirstName',
                  'value' => $model->user->usrFirstName,
                ],
                [
                  'attribute' => 'usrFirstName_en',
                  'value' => $model->user->usrFirstName_en,
                ],
                [
                  'attribute' => 'usrLastName',
                  'value' => $model->user->usrLastName,
                ],
                [
                  'attribute' => 'usrLastName_en',
                  'value' => $model->user->usrLastName_en,
                ],
              ],
            ]);
          ?>
        </div>
        <div class='col-4'>
          <?php
            echo DetailView::widget([
              'model' => $model,
              'enableEditMode' => false,
              'attributes' => [
                'spcCreatedAt:jalaliWithTime',
                [
                  'attribute' => 'spcCreatedBy_User',
                  'format' => 'raw',
                  'value' => $model->createdByUser->actorName ?? '-',
                ],
                'spcUpdatedAt:jalaliWithTime',
                [
                  'attribute' => 'spcUpdatedBy_User',
                  'format' => 'raw',
                  'value' => $model->updatedByUser->actorName ?? '-',
                ],
                'spcRemovedAt:jalaliWithTime',
                [
                  'attribute' => 'spcRemovedBy_User',
                  'format' => 'raw',
                  'value' => $model->removedByUser->actorName ?? '-',
                ],
              ],
            ]);
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
