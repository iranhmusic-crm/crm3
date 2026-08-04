<?php

/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\common\helpers\Url;
use shopack\base\common\helpers\StringHelper;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\frontend\common\widgets\grid\GridView;
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
            $grid_id = StringHelper::generateRandomId();
            $filter_mode_id = Html::getInputId($searchModel, 'filter_mode');

            echo $this->render('_search', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'grid_id' => $grid_id,
                'filter_mode_id' => $filter_mode_id,
            ]);
            ?>
        </div>

        <div class='card-body'>
            <?php
            echo GridView::widget([
                'id' => $grid_id,
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'filterSelector' => "#{$filter_mode_id} input",

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
                        },
                    ],
                    [
                        'attribute' => 'mbrRegisterCode',
                        'format' => 'raw',
                        'value' => function ($model, $key, $index, $widget) {
                            return Html::a($model->mbrRegisterCode ??
                                Yii::$app->formatter->asBoolean(false), ['view', 'id' => $model->mbrUserID]);
                            // return Html::a($model->mbrRegisterCode ?? '[ندارد]', ['view', 'id' => $model->mbrUserID]);
                        },
                    ],
                    [
                        'attribute' => 'usrFirstName',
                        'format' => 'raw',
                        'value' => function ($model, $key, $index, $widget) {
                            return Html::a($model->user->usrFirstName, ['view', 'id' => $model->mbrUserID]);
                        },
                    ],
                    [
                        'attribute' => 'usrLastName',
                        'format' => 'raw',
                        'value' => function ($model, $key, $index, $widget) {
                            return Html::a($model->user->usrLastName, ['view', 'id' => $model->mbrUserID]);
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