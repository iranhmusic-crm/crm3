<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\frontend\common\helpers\Html;
use shopack\base\frontend\common\widgets\PopoverX;
use shopack\base\frontend\common\widgets\DetailView;
use shopack\aaa\frontend\common\models\UserModel;
use iranhmusic\shopack\mha\frontend\common\models\MemberModel;
use iranhmusic\shopack\mha\frontend\common\models\MemberKanoonModel;
use iranhmusic\shopack\mha\frontend\common\models\KanoonModel;
use iranhmusic\shopack\mha\frontend\common\models\ReportModel;

$this->title = Yii::t('mha', 'Member Report') . ': ' . $model->rptID . ' - ' . $model->rptName;
$this->params['breadcrumbs'][] = Yii::t('mha', 'Music House');
$this->params['breadcrumbs'][] = ['label' => Yii::t('mha', 'Reports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div id='member-report-view' class='w-100'>
	<div class='card border-default'>
		<div class='card-header bg-default'>
			<div class="float-end">
				<?= Html::confirmButton(yii::t('mha', 'Run Report'), [
					'run',
					'id' => $model->rptID
				], 'آیا می‌خواهید این گزارش اجرا شود؟', [
					'btn' => 'success',
				]) ?>
				<?= $model->canUpdate()   ? Html::updateButton(null,   ['id' => $model->rptID], ['modal' => false]) : '' ?>
				<?= $model->canDelete()   ? Html::deleteButton(null,   ['id' => $model->rptID]) : '' ?>
				<?= $model->canUndelete() ? Html::undeleteButton(null, ['id' => $model->rptID]) : '' ?>
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
							'rptCreatedAt:jalaliWithTime',
							[
								'attribute' => 'rptCreatedBy_User',
								'format' => 'raw',
								'value' => $model->createdByUser->actorName ?? '-',
							],
							'rptUpdatedAt:jalaliWithTime',
							[
								'attribute' => 'rptUpdatedBy_User',
								'format' => 'raw',
								'value' => $model->updatedByUser->actorName ?? '-',
							],
							'rptRemovedAt:jalaliWithTime',
							[
								'attribute' => 'rptRemovedBy_User',
								'format' => 'raw',
								'value' => $model->removedByUser->actorName ?? '-',
							],
						],
					]);

					PopoverX::end();
				?>
			</div>
			<div class='card-title'><?= $this->title ?></div>
			<div class="clearfix"></div>
		</div>

		<div class='card-body'>
			<?php
				$userModel = new UserModel();
				$memberModel = new MemberModel();
				$memberKanoonModel = new MemberKanoonModel();
				$kanoonModel = new KanoonModel();

				$rptOutputFields = array_keys($model->rptOutputFields);
				foreach ($rptOutputFields as $k => &$v) {
					if ($v == 'hasPassword')
						$v = yii::t('aaa', 'User') . ': ' . $userModel->getAttributeLabel($v);
					else if (str_starts_with($v, 'usr'))
						$v = yii::t('aaa', 'User') . ': ' . $userModel->getAttributeLabel($v);
					else if (str_starts_with($v, 'mbrknn'))
						$v = yii::t('mha', 'Kanoon') . ': ' . $memberKanoonModel->getAttributeLabel($v);
					else if (str_starts_with($v, 'knn'))
						$v = yii::t('mha', 'Kanoon') . ': ' . $kanoonModel->getAttributeLabel($v);
					else if (str_starts_with($v, 'mbr'))
						$v = yii::t('mha', 'Member') . ': ' . $memberModel->getAttributeLabel($v);
				}
				sort($rptOutputFields);

				echo DetailView::widget([
					'model' => $model,
					'enableEditMode' => false,
					// 'cols' => 2,
					// 'isVertical' => false,
					'attributes' => [
						'rptID',
						'rptName',
						// 'rptInputFields',
						[
							'attribute' => 'rptOutputFields',
							'format' => 'raw',
							'value' => implode('<br>', $rptOutputFields),
						],
					],
				]);
			?>
		</div>

	</div>
</div>
