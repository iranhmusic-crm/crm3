<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\frontend\widgets\PopoverX;
use shopack\base\frontend\widgets\DetailView;
use shopack\base\frontend\helpers\Html;
use iranhmusic\shopack\mha\common\enums\enuKanoonStatus;
use iranhmusic\shopack\mha\frontend\common\models\KanoonModel;
use iranhmusic\shopack\mha\common\enums\enuBasicDefinitionType;

$this->title = Yii::t('mha', 'Kanoon') . ': ' . $model->knnID . ' - ' . $model->knnName;
$this->params['breadcrumbs'][] = Yii::t('mha', 'Music House');
$this->params['breadcrumbs'][] = ['label' => Yii::t('mha', 'Kanoons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="kanoon-view w-100">
  <div class='card border-default'>
		<div class='card-header bg-default'>
			<div class="float-end">
				<?= KanoonModel::canCreate() ? Html::createButton() : '' ?>
        <?= $model->canUpdate()   ? Html::updateButton(null,   ['id' => $model->knnID]) : '' ?>
        <?= $model->canDelete()   ? Html::deleteButton(null,   ['id' => $model->knnID]) : '' ?>
        <?= $model->canUndelete() ? Html::undeleteButton(null, ['id' => $model->knnID]) : '' ?>
        <?= Html::a(Yii::t('aaa', 'Send Message'), [
          'send-message',
          'id' => $model->knnID,
          // 'ref' => Url::toRoute(['view', 'id' => $model->mbrUserID], true),
        ], [
          'class' => 'btn btn-sm btn-success',
          'modal' => true,
        ]); ?>
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
              'knnCreatedAt:jalaliWithTime',
              [
                'attribute' => 'knnCreatedBy_User',
                'format' => 'raw',
                'value' => $model->createdByUser->actorName ?? '-',
              ],
              'knnUpdatedAt:jalaliWithTime',
              [
                'attribute' => 'knnUpdatedBy_User',
                'format' => 'raw',
                'value' => $model->updatedByUser->actorName ?? '-',
              ],
              'knnRemovedAt:jalaliWithTime',
              [
                'attribute' => 'knnRemovedBy_User',
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
        $fildTypes = [
          'text' => 'متن',
        ];
        $mhaList = enuBasicDefinitionType::getList();
        foreach($mhaList as $k => $v) {
          $fildTypes['mha:' . $k] = $v;
        }

        echo DetailView::widget([
          'model' => $model,
          'enableEditMode' => false,
          'attributes' => [
            'knnID',
            [
              'attribute' => 'knnStatus',
              'value' => enuKanoonStatus::getLabel($model->knnStatus),
            ],
            'knnName',
            'knnNameEn',
            [
              'attribute' => 'knnDescFieldType',
              'value' => (empty($model->knnDescFieldType) ? null
                : $fildTypes[$model->knnDescFieldType] ?? $model->knnDescFieldType
              ),
            ],
            'knnDescFieldLabel',
            [
              'attribute' => 'knnPresidentMemberID',
              'format' => 'raw',
              'value' => (isset($model->knnPresidentMemberID)
                ? $model->president->displayName() : null),
            ],
            [
              'attribute' => 'knnVicePresidentMemberID',
              'format' => 'raw',
              'value' => (isset($model->knnVicePresidentMemberID)
                ? $model->vicePresident->displayName() : null),
            ],
            [
              'attribute' => 'knnOzv1MemberID',
              'format' => 'raw',
              'value' => (isset($model->knnOzv1MemberID)
                ? $model->ozv1->displayName() : null),
            ],
            [
              'attribute' => 'knnOzv2MemberID',
              'format' => 'raw',
              'value' => (isset($model->knnOzv2MemberID)
                ? $model->ozv2->displayName() : null),
            ],
            [
              'attribute' => 'knnOzv3MemberID',
              'format' => 'raw',
              'value' => (isset($model->knnOzv3MemberID)
                ? $model->ozv3->displayName() : null),
            ],
            [
              'attribute' => 'knnWardenMemberID',
              'format' => 'raw',
              'value' => (isset($model->knnWardenMemberID)
                ? $model->warden->displayName() : null),
            ],
            [
              'attribute' => 'knnTalkerMemberID',
              'format' => 'raw',
              'value' => (isset($model->knnTalkerMemberID)
                ? $model->talker->displayName() : null),
            ],
          ],
        ]);
      ?>
    </div>
  </div>
</div>
