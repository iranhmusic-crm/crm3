<?php

use shopack\base\frontend\common\helpers\Html;
use shopack\base\frontend\common\widgets\ActiveForm;
use iranhmusic\shopack\mha\frontend\common\models\MemberSearchModel;
?>

<div class='member-model-search'>
    <?php
    $form = ActiveForm::begin([
        'model' => $searchModel,
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1,
        ],
        'formConfig' => [
            'labelSpan' => 12, //\kartik\form\ActiveField::NotSet,
        ],
        'type' => ActiveForm::TYPE_VERTICAL,
        'template' => "{beginLabel}{labelTitle}:{endLabel}\n<br>{beginWrapper}\n{input}\n{error}\n{hint}\n{endWrapper}",
    ]);

    // echo Html::hiddenInput('maincmd', $maincmd ?? null);
    ?>

    <div>
        <div class='pull-left'>
            <div>
                <div style='display:inline-block; margin-left: 20px;'>
                    <?php
                    echo $form->field($searchModel, 'filter_mode', [
                        'inputOptions' => [
                            'class' => [],
                        ],
                    ])
                        ->widget(\yii\bootstrap5\ToggleButtonGroup::class, [
                            'type' => \yii\bootstrap5\ToggleButtonGroup::TYPE_RADIO,
                            'labelOptions' => ['class' => ['btn-outline-secondary', 'btn-sm']],
                            'items' => [
                                MemberSearchModel::FILTER_MODE_HAS_REG_CODE => 'دارای کد عضویت',
                                MemberSearchModel::FILTER_MODE_WAIT_FOR_SEND_TO_KANOON => 'منتظر تایید برای ارسال به کمیسیون',
                                MemberSearchModel::FILTER_MODE_WAIT_FOR_KANOON_APPROVAL => 'منتظر بررسی کمیسیون',
                                MemberSearchModel::FILTER_MODE_ONLINE_REG_REQ => 'درخواست عضویت آنلاین',
                                // MemberSearchModel::FILTER_MODE_WAIT_FOR_BASE_APPROVAL => 'منتظر تایید مدارک',
                                MemberSearchModel::FILTER_MODE_DEAD => 'فوت شده',
                                MemberSearchModel::FILTER_MODE_ALL => 'همه',
                            ],
                        ]);

                    $JS = <<<JS
$("#{$filter_mode_id}").change(function () {
    $('#{$grid_id}').yiiGridView('applyFilter');
});
JS;
                    $this->registerJs($JS, \yii\web\View::POS_READY);
                    ?>
                </div>
            </div>
        </div>

        <div class='clearfix'></div>
    </div>

    <?php
    $form->endForm(); //ActiveForm::end();
    ?>
</div>