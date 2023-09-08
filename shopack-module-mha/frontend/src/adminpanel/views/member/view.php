<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\frontend\widgets\PopoverX;
use shopack\base\common\helpers\Url;
use shopack\base\common\helpers\ArrayHelper;
use shopack\base\frontend\widgets\tabs\Tabs;
use shopack\base\frontend\widgets\DetailView;
use shopack\base\frontend\helpers\Html;
use shopack\aaa\common\enums\enuUserStatus;
use shopack\aaa\common\enums\enuGender;
use iranhmusic\shopack\mha\common\enums\enuMemberStatus;
use iranhmusic\shopack\mha\frontend\common\models\MemberModel;

$this->title = Yii::t('mha', 'Member') . ': ' . $model->displayName();
$this->params['breadcrumbs'][] = Yii::t('mha', 'Music House');
$this->params['breadcrumbs'][] = ['label' => Yii::t('mha', 'Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="member-view w-100">
  <div class='card border-default'>
		<div class='card-header bg-default'>
			<div class="float-end">
				<?= MemberModel::canCreate() ? Html::createButton() : '' ?>
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
              'mbrCreatedAt:jalaliWithTime',
              [
                'attribute' => 'mbrCreatedBy_User',
                'format' => 'raw',
                'value' => $model->createdByUser->actorName ?? '-',
              ],
              'mbrUpdatedAt:jalaliWithTime',
              [
                'attribute' => 'mbrUpdatedBy_User',
                'format' => 'raw',
                'value' => $model->updatedByUser->actorName ?? '-',
              ],
              'mbrRemovedAt:jalaliWithTime',
              [
                'attribute' => 'mbrRemovedBy_User',
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

    <div class='card-tabs'>
  		<?php $tabs = Tabs::begin($this); ?>

      <?php $tabs->beginTabPage('مشخصات'); ?>
        <div>
          <div class='row mb-3'>
            <div class='col-9'>
              <?php
                echo DetailView::widget([
                  'model' => $model,
                  'enableEditMode' => false,
                  'cols' => 2,
                  'isVertical' => false,
                  'attributes' => [
                    [
                      'attribute' => 'mbrRegisterCode',
                      'value' => '[' . ($model->mbrRegisterCode ?? 'ندارد') . ']',
                    ],
                    [
                      'attribute' => 'mbrUserID',
                      'format' => 'raw',
                      'value' => Html::a($model->user->displayName(), ['/aaa/user/view', 'id' => $model->mbrUserID]), //, ['class' => ['btn', 'btn-sm', 'btn-outline-secondary']]),
                    ],
                    [
                      'attribute' => 'mbrStatus',
                      'value' => enuMemberStatus::getLabel($model->mbrStatus),
                    ],
                    [
                      'attribute' => 'usrStatus',
                      'value' => enuUserStatus::getLabel($model->user->usrStatus),
                    ],
                    'mbrAcceptedAt:jalaliWithTime',
                    'mbrExpireDate:jalali',
                    [
                      'group' => true,
                      // 'cols' => 1,
                      'label' => 'اطلاعات پایه',
                      'groupOptions' => ['class' => 'info-row'],
                    ],
                    [
                      'attribute' => 'usrGender',
                      'value' => enuGender::getLabel($model->user->usrGender),
                    ],
                    [
                      'attribute' => 'usrSSID',
                      'value' => $model->user->usrSSID,
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
                    [
                      'attribute' => 'usrFatherName',
                      'value' => $model->user->usrFatherName,
                    ],
                    [
                      'attribute' => 'usrFatherName_en',
                      'value' => $model->user->usrFatherName_en,
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
                      'group' => true,
                      'cols' => 1,
                      'label' => 'اطلاعات تکمیلی',
                      'groupOptions' => ['class' => 'info-row'],
                    ],
                    'mbrMusicExperiences:paragraphs',
                    'mbrMusicExperienceStartAt:jalali',

                    [
                      'attribute' => 'mbrInstrumentID',
                      'value' => $model->instrument->bdfName ?? null,
                    ],
                    [
                      'attribute' => 'mbrSingID',
                      'value' => $model->sing->bdfName ?? null,
                    ],
                    [
                      'attribute' => 'mbrResearchID',
                      'value' => $model->research->bdfName ?? null,
                    ],
                    [
                      'attribute' => 'mbrArtDegree',
                      'value' => empty($model->mbrArtDegree) ? null
                                  : 'درجه ' . $model->mbrArtDegree,
                    ],
                    'mbrHonarCreditCode',
                    'mbrJob',
                    'mbrOwnOrgName',

                    'mbrArtHistory:paragraphs',
                    'mbrMusicEducationHistory:paragraphs',

                  ],
                ]);
              ?>
            </div>
            <div class='col-3'>
              <div class='card border-default'>
                <div class='card-body'>
                  <?php
                    $buttons = [];

                    if ($model->canUpdate()) {
                      $buttons[] = Html::a(Yii::t('mha', 'Update User'), [
                        '/aaa/user/update',
                        'id' => $model->mbrUserID,
                        'ref' => Url::to(['view', 'id' => $model->mbrUserID], true)
                      ], [
                        'modal' => true,
                        'class' => 'btn btn-sm btn-primary',
                        'data-popup-size' => 'lg',
                      ]);
                    }

                    if ($model->canUpdate()) {
                      $buttons[] = Html::updateButton(null, ['id' => $model->mbrUserID], [
                        'modal' => true,
                        'data-popup-size' => 'lg',
                      ]);
                      $buttons[] = Html::updateButton('تعیین رمز', ['/aaa/user/password-reset', 'id' => $model->mbrUserID], [
                        'btn' => 'warning',
                      ]);
                    }

                    if ($model->canDelete())
                      $buttons[] = Html::deleteButton(null, ['id' => $model->mbrUserID]);

                    if ($model->canUndelete())
                      $buttons[] = Html::undeleteButton(null, ['id' => $model->mbrUserID]);

                    $buttons[] = Html::a(Yii::t('mha', 'Print Card (Front)'), [
                      'print-card-front',
                      'id' => $model->mbrUserID,
                    ], [
                      'class' => 'btn btn-sm btn-primary',
                      // 'modal' => true,
                      'target' => '_blank',
                    ]);

                    $buttons[] = Html::a(Yii::t('mha', 'Print Card (Back)'), [
                      'print-card-back',
                      'id' => $model->mbrUserID,
                    ], [
                      'class' => 'btn btn-sm btn-primary',
                      // 'modal' => true,
                      'target' => '_blank',
                    ]);

                    $buttons[] = Html::a('چاپ نامه صندوق هنر', [
                      'print-art-fund-letter',
                      'id' => $model->mbrUserID,
                    ], [
                      'class' => 'btn btn-sm btn-primary',
                      // 'modal' => true,
                      'target' => '_blank',
                    ]);

                    if (empty($buttons) == false)
                      echo implode(' ', $buttons);
                  ?>
                </div>
              </div>
              <?php
                $defects = $model->getDefects();
                if (empty($defects) == false) {
              ?>
              <div class='card border-default mt-3'>
                <div class='card-header bg-default'>
                  <div class="float-end">
                    <div class='badge bg-danger'><?= count($defects) ?></div>
                  </div>
                  <div class='card-title'><?= Yii::t('app', 'نواقص پرونده') ?></div>
                  <div class="clearfix"></div>
                </div>
                <div class='card-body'>
                  <?php
                    echo '<ol>';
                    foreach ($defects as $lbl => $val) {
                      echo '<li>';
                      echo '<b>' . $val['label'] . '</b>';
                      echo ': ';
                      if (is_array($val['desc'])) {
                        echo '<ul><li>' . implode('</li><li>', $val['desc']) . '</li></ul>';
                      } else {
                        echo $val['desc'];
                      }
                      echo '</li>';
                    }
                    echo '</ol>';
                  ?>
                </div>
              </div>
              <?php
                }
              ?>

              <div class='card border-default mt-3'>
                <div class='card-header bg-default'>
                  <div class="float-end">
                  </div>
                  <div class='card-title'><?= Yii::t('aaa', 'Image') ?></div>
                  <div class="clearfix"></div>
                </div>
                <div class='card-body text-center'>
                  <?php
                    if ($model->user->usrImageFileID == null)
                      echo Yii::t('app', 'not set');
                    else if (empty($model->user->imageFile->fullFileUrl))
                      echo Yii::t('aaa', 'Uploading...');
                    else if ($model->user->imageFile->isImage())
                      echo Html::img($model->user->imageFile->fullFileUrl, ['style' => ['width' => '100%']]);
                    else
                      echo Html::a(Yii::t('app', 'Download'), $model->user->imageFile->fullFileUrl);
                  ?>
                </div>
              </div>

            </div>
          </div>

        </div>
      <?php $tabs->endTabPage(); ?>

      <?php $tabs->newAjaxTabPage(Yii::t('mha', 'Documents'), [
          '/mha/member-document/index',
          'mbrdocMemberID' => $model->mbrUserID,
        ],
        'member-documents'
      ); ?>

      <?php $tabs->newAjaxTabPage(Yii::t('mha', 'Specialties'), [
          '/mha/member-specialty/index',
          'mbrspcMemberID' => $model->mbrUserID,
        ],
        'member-specialty'
      ); ?>

      <?php $tabs->newAjaxTabPage(Yii::t('mha', 'Kanoons'), [
          '/mha/member-kanoon/index',
          'mbrknnMemberID' => $model->mbrUserID,
        ],
        'member-kanoons'
      ); ?>

      <?php
        $tabs->beginTabPage(Yii::t('mha', 'Insurance'), [
          'member-master-insurances',
          'member-master-ins-docs',
          'member-supplementary-ins-docs',
        ]);

        $tabs2 = Tabs::begin($this, [
          'pluginOptions' => [
            'id' => 'tabs_insurances',
            // 'position' => \kartik\tabs\TabsX::POS_LEFT,
            // 'bordered' => true,
          ],
        ]);

        $tabs2->newAjaxTabPage(Yii::t('mha', 'Master Insurances'), [
            '/mha/member-master-insurance/index',
            'mbrminshstMemberID' => $model->mbrUserID,
          ],
          'member-master-insurances'
        );

        //use runaction for proper loading grid expand column
        $tabs2->beginTabPage(Yii::t('mha', 'Master Insurance Documents'), 'member-master-ins-docs');
        echo Yii::$app->runAction('/mha/member-master-ins-doc/index', ArrayHelper::merge($_GET, [
          'isPartial' => true,
          'params' => [
            'mbrminsdocMemberID' => $model->mbrUserID,
          ],
        ]));
        $tabs2->endTabPage();

        // $tabs2->newAjaxTabPage(Yii::t('mha', 'Master Insurance Documents'), [
        //     '/mha/member-master-ins-doc/index',
        //     'mbrminsdocMemberID' => $model->mbrUserID,
        //   ],
        //   'member-master-ins-docs'
        // );

        //use runaction for proper loading grid expand column
        $tabs2->beginTabPage(Yii::t('mha', 'Supplementary Insurance Documents'), 'member-supplementary-ins-docs');
        echo Yii::$app->runAction('/mha/member-supplementary-ins-doc/index', ArrayHelper::merge($_GET, [
          'isPartial' => true,
          'params' => [
            'mbrsinsdocMemberID' => $model->mbrUserID,
          ],
        ]));
        $tabs2->endTabPage();

        // $tabs2->newAjaxTabPage(Yii::t('mha', 'Supplementary Insurance Documents'), [
        //     '/mha/member-supplementary-ins-doc/index',
        //     'mbrsinsdocMemberID' => $model->mbrUserID,
        //   ],
        //   'member-supplementary-ins-docs'
        // );

        $tabs2->end();

        $tabs->endTabPage();
      ?>

      <?php $tabs->newAjaxTabPage(Yii::t('mha', 'Sponsorships'), [
          '/mha/member-sponsorship/index',
          'mbrspsMemberID' => $model->mbrUserID,
        ],
        'member-sponsorships'
      ); ?>

      <?php $tabs->newAjaxTabPage(Yii::t('mha', 'Memberships'), [
          '/mha/member-membership/index',
          'mbrshpMemberID' => $model->mbrUserID,
        ],
        'member-memberships'
      ); ?>

      <?php
        $tabs->beginTabPage(Yii::t('aaa', 'Financial'), [
          'wallets',
          'wallet-transactions',
          'orders',
          'online-payments',
          'offline-payments',
        ]);

        $tabs2 = Tabs::begin($this, [
          'pluginOptions' => [
            'id' => 'tabs_fin',
            // 'position' => \kartik\tabs\TabsX::POS_LEFT,
            // 'bordered' => true,
          ],
        ]);

        $tabs2->newAjaxTabPage(Yii::t('aaa', 'Wallets'), [
            '/aaa/wallet/index',
            'walOwnerUserID' => $model->mbrUserID,
          ],
          'wallets'
        );

        $tabs2->newAjaxTabPage(Yii::t('aaa', 'Wallet Transactions'), [
            '/aaa/wallet-transaction/index',
            'walOwnerUserID' => $model->mbrUserID,
          ],
          'wallet-transactions'
        );

        $tabs2->newAjaxTabPage(Yii::t('aaa', 'Orders'), [
            '/aaa/order/index',
            'vchOwnerUserID' => $model->mbrUserID,
          ],
          'orders'
        );

        $tabs2->newAjaxTabPage(Yii::t('aaa', 'Online Payments'), [
            '/aaa/online-payment/index',
            'vchOwnerUserID' => $model->mbrUserID,
          ],
          'online-payments'
        );

        $tabs2->newAjaxTabPage(Yii::t('aaa', 'Offline Payments'), [
            '/aaa/offline-payment/index',
            'ofpOwnerUserID' => $model->mbrUserID,
          ],
          'offline-payments'
        );

        $tabs2->end();

        $tabs->endTabPage();
      ?>

      <?php $tabs->end(); ?>
    </div>
  </div>
</div>
