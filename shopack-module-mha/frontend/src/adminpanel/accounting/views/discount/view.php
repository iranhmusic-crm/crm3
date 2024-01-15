<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\common\helpers\ArrayHelper;
use shopack\base\common\accounting\enums\enuAmountType;
use shopack\base\common\accounting\enums\enuDiscountStatus;
use shopack\base\common\accounting\enums\enuDiscountType;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\frontend\common\widgets\PopoverX;
use shopack\base\frontend\common\widgets\DetailView;
use shopack\base\frontend\common\widgets\tabs\Tabs;
use iranhmusic\shopack\mha\frontend\common\accounting\models\ProductModel;
use iranhmusic\shopack\mha\frontend\common\accounting\models\SaleableModel;
use iranhmusic\shopack\mha\frontend\common\models\KanoonModel;
use iranhmusic\shopack\mha\frontend\common\models\MemberGroupModel;
use iranhmusic\shopack\mha\common\accounting\enums\enuMhaProductType;
use iranhmusic\shopack\mha\frontend\common\models\MemberModel;

$modelClass = Yii::$app->controller->modelClass;

$this->params['breadcrumbs'][] = Yii::t('mha', 'Music House');
$this->params['breadcrumbs'][] = Yii::t('mha', 'Services Definition and Fee');
$this->params['breadcrumbs'][] = ['label' => Yii::t('aaa', 'Discounts'), 'url' => ['index']];
$this->title = Yii::t('aaa', 'Discount') . ': ' . $model->dscID . ' - ' . $model->dscName;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="discount-view w-100">
  <div class='card'>
		<div class='card-header'>
			<div class="float-end">
				<?= $modelClass::canCreate() ? Html::createButton(null, null, [
          'data-popup-size' => 'lg',
        ]) : '' ?>
        <?= $model->canUpdate()   ? Html::updateButton(null,   ['id' => $model->dscID], [
          'data-popup-size' => 'lg',
        ]) : '' ?>
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

    <div class='card-tabs'>
  		<?php $tabs = Tabs::begin($this); ?>

      <?php $tabs->beginTabPage('مشخصات'); ?>
        <div>
          <?php
            $attributes = [
              [
                'attribute' => 'dscStatus',
                'value' => enuDiscountStatus::getLabel($model->dscStatus),
              ],
              'dscID',
              'dscName',
              [
                'attribute' => 'dscType',
                'value' => enuDiscountType::getLabel($model->dscType),
              ],
            ];

            if ($model->dscType == enuDiscountType::System) {
              $attributes = array_merge($attributes, [
                [
                  'attribute' => 'dscDiscountGroupID',
                  'value' => $model->discountGroup->dscgrpName ?? null,
                ],
              ]);
            } else if ($model->dscType == enuDiscountType::Coupon) {
              $attributes = array_merge($attributes, [
                'dscCodeString',
                'dscCodeHasSerial:boolean',
              ]);

              if ($model->dscCodeHasSerial) {
                $attributes = array_merge($attributes, [
                  'dscCodeSerialCount',
                  'dscCodeSerialLength',
                ]);
              }
            }

            $attributes = array_merge($attributes, [
              [
                'group' => true,
                'label' => Yii::t('app', 'Conditions'),
                'rowOptions' => ['class' => 'info'],
              ],
              [
                'attribute' => 'dscTargetUserIDs',
                'format' => 'raw',
                'value' => Html::splitAsList(MemberModel::toString($model->dscTargetUserIDs), '|', [
                  'encode' => false
                ]),
              ],
              [
                'attribute' => 'dscTargetMemberGroupIDs',
                'format' => 'raw',
                'value' => Html::splitAsList(MemberGroupModel::toString($model->dscTargetMemberGroupIDs), '|', [
                  'encode' => false
                ]),
              ],
              [
                'attribute' => 'dscTargetKanoonIDs',
                'format' => 'raw',
                'value' => Html::splitAsList(KanoonModel::toString($model->dscTargetKanoonIDs), '|', [
                  'encode' => false
                ]),
              ],
              [
                'attribute' => 'dscTargetProductMhaTypes',
                'format' => 'raw',
                'value' => Html::splitAsList(enuMhaProductType::getLabel($model->dscTargetProductMhaTypes), '|', [
                  'encode' => false
                ]),
              ],
              [
                'attribute' => 'dscTargetProductIDs',
                'format' => 'raw',
                'value' => Html::splitAsList(ProductModel::toString($model->dscTargetProductIDs), '|', [
                  'encode' => false
                ]),
              ],
              [
                'attribute' => 'dscTargetSaleableIDs',
                'format' => 'raw',
                'value' => Html::splitAsList(SaleableModel::toString($model->dscTargetSaleableIDs), '|', [
                  'encode' => false
                ]),
              ],
              [
                'attribute' => 'dscReferrers',
                'format' => 'raw',
                'value' => Html::splitAsList(SaleableModel::toString($model->dscReferrers), '|', [
                  'encode' => false
                ]),
              ],

              // 'dscSaleableBasedMultiplier',
            ]);

            $attributes = array_merge($attributes, [
              [
                'group' => true,
                'label' => Yii::t('app', 'Limitations'),
                'rowOptions' => ['class' => 'info'],
              ],
              'dscValidFrom:jalali',
              'dscValidTo:jalali',
              'dscTotalMaxCount:decimal',
              'dscTotalMaxPrice:toman',
              'dscPerUserMaxCount:decimal',
              'dscPerUserMaxPrice:toman',
            ]);

            $attributes = array_merge($attributes, [
              [
                'group' => true,
                'label' => Yii::t('app', 'Actions'),
                'rowOptions' => ['class' => 'info'],
              ],
              [
                'attribute' => 'dscAmount',
                'value' => Yii::$app->formatter->asDecimal($model->dscAmount)
                  . ' '
                  . ($model->dscAmountType == enuAmountType::Percent ? 'درصد' : 'تومان')
              ],
              [
                'attribute' => 'dscMaxAmount',
                'value' => (empty($model->dscMaxAmount) ? 'نامحدود'
                  : Yii::$app->formatter->asDecimal($model->dscMaxAmount)
                    . ' '
                    . ($model->dscAmountType == enuAmountType::Percent ? 'تومان' : 'درصد')
                ),
              ],
            ]);

            $attributes = array_merge($attributes, [
              [
                'group' => true,
                'label' => Yii::t('app', 'Usage'),
                'rowOptions' => ['class' => 'info'],
              ],
              'dscTotalUsedCount:decimal',
              'dscTotalUsedPrice:toman',
            ]);

            echo DetailView::widget([
              'model' => $model,
              'enableEditMode' => false,
              // 'cols' => 2,
              // 'isVertical' => false,
              'attributes' => $attributes,
            ]);
          ?>
        </div>
      <?php $tabs->endTabPage(); ?>

      <?php
        if (($model->dscType == enuDiscountType::Coupon) && $model->dscCodeHasSerial) {
          $tabs->newAjaxTabPage(Yii::t('aaa', 'Discount Serials'), [
              '/mha/accounting/discount-serial/index',
              'dscsnDiscountID' => $model->dscID,
            ],
            'discount-serials'
          );
        }
      ?>

      <?php $tabs->newAjaxTabPage(Yii::t('aaa', 'Discount Usages'), [
          '/mha/accounting/discount-usage/index',
          'dscusgDiscountID' => $model->dscID,
        ],
        'discount-usages'
      ); ?>

      <?php $tabs->end(); ?>

    </div>
  </div>
</div>
