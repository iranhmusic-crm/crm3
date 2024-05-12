<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\common\helpers\Url;
use shopack\base\frontend\common\widgets\grid\GridView;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\common\helpers\StringHelper;
use iranhmusic\shopack\mha\common\enums\enuMemberStatus;
use iranhmusic\shopack\mha\frontend\common\models\MemberModel;

$this->title = Yii::t('mha', 'Members');
$this->params['breadcrumbs'][] = Yii::t('mha', 'Music House');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="member-index w-100">
  <div class='card'>
		<div class='card-header'>
			<div class="float-end">
        <?= MemberModel::canCreate() ? Html::createButton() : '' ?>
			</div>
      <div class='card-title'><?= Html::encode($this->title) ?></div>
			<div class="clearfix"></div>
		</div>

		<div class='card-body'>
			<?php
				echo $this->render('_search', [
					'searchModel' => $searchModel,
					'dataProvider' => $dataProvider,
				]);
			?>
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
          [
            'attribute' => 'image',
            'label' => false,
            'format' => 'raw',
            'value' => function ($model, $key, $index, $widget) {
              return Html::asUploadedImage($model->user->imageFile);
              // if ($model->user->usrImageFileID == null)
              //   return null;
              // elseif (empty($model->user->imageFile->fullFileUrl))
              //   return Yii::t('aaa', '...');
              // elseif ($model->user->imageFile->isImage())
              //   return Html::img($model->user->imageFile->fullFileUrl, ['style' => ['width' => '50px']]);
              // else
              //   return Html::a(Yii::t('app', 'Download'), $model->user->imageFile->fullFileUrl);
            },
          ],
          [
            'attribute' => 'mbrRegisterCode',
            'format' => 'raw',
            'value' => function ($model, $key, $index, $widget) {
              return Html::a($model->mbrRegisterCode ?? '[ندارد]', ['view', 'id' => $model->mbrUserID]);
            },
          ],
          [
            'attribute' => 'usrFirstName',
            'format' => 'raw',
            'value' => function ($model, $key, $index, $widget) {
              return $model->user->usrFirstName;
            },
          ],
          [
            'attribute' => 'usrLastName',
            'format' => 'raw',
            'value' => function ($model, $key, $index, $widget) {
              return $model->user->usrLastName;
            },
          ],
          [
            'attribute' => 'usrSSID',
            'format' => 'raw',
            'value' => function ($model, $key, $index, $widget) {
              if (empty($model->user->usrSSID))
                return null;
              return $model->user->usrSSID;
            },
          ],
          [
            'attribute' => 'usrMobile',
            'format' => 'raw',
            'contentOptions' => [
              'class' => 'dir-ltr text-start tabular-nums',
            ],
            'value' => function ($model, $key, $index, $widget) {
              if (empty($model->user->usrMobile))
                return null;
              return Yii::$app->formatter->asPhone($model->user->usrMobile);
            },
          ],
          // [
          //   'attribute' => 'usrEmail',
          //   'format' => 'raw',
          //   'value' => function ($model, $key, $index, $widget) {
          //     if (empty($model->user->usrEmail))
          //       return null;
          //     return $model->user->usrEmail;
          //   },
          // ],
          'mbrAcceptedAt:jalaliWithTime',
          'mbrExpireDate:jalali',
          [
            'class' => \shopack\base\frontend\common\widgets\grid\EnumDataColumn::class,
            'enumClass' => enuMemberStatus::class,
            'attribute' => 'mbrStatus',
          ],
          [
            'class' => \shopack\base\frontend\common\widgets\ActionColumn::class,
            'header' => MemberModel::canCreate() ? Html::createButton() : Yii::t('app', 'Actions'),
            'template' => '{update-user} {update} {delete}{undelete}',
            'buttons' => [
              'update-user' => function ($url, $model, $key) {
                return Html::a(Yii::t('mha', 'Update User'), [
                  '/aaa/user/update',
                  'id' => $model->mbrUserID,
                  'ref' => Url::toRoute(['view', 'id' => $model->mbrUserID], true),
                ], [
                  'class' => 'btn btn-sm btn-primary',
                ]);
              },
            ],
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
          'mbrCreatedAt:jalaliWithTime',
          'mbrUpdatedAt:jalaliWithTime',
          // [
          //   'attribute' => 'rowDate',
          //   'noWrap' => true,
          //   'format' => 'raw',
          //   'label' => 'ایجاد / ویرایش',
          //   'value' => function($model) {
          //     return Html::formatRowDates(
          //       $model->mbrCreatedAt,
          //       $model->createdByUser,
          //       $model->mbrUpdatedAt,
          //       $model->updatedByUser,
          //       $model->mbrRemovedAt,
          //       $model->removedByUser,
          //     );
          //   },
          // ],
        ],
      ]);
      ?>
    </div>
  </div>
</div>
