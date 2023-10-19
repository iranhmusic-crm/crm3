<?php
/** @var yii\web\View $this */
/** @var string $content */

use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use app\assets\AppAsset;
use app\widgets\Alert;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\frontend\common\web\SideNav;

// $this->registerJs('var globalBaseUrl = "' . Yii::$app->request->baseUrl . '";', \yii\web\View::POS_BEGIN);
AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => '/favicon.ico']);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>

<html lang="<?= Yii::$app->language ?>" class="h-100" dir="rtl">
<head>
  <title>پنل مدیریت خانه موسیقی - <?= Html::encode($this->title) ?></title>
  <?php $this->head() ?>
</head>

<body class="h-100">
<?php $this->beginBody() ?>
<?php $this->beginMainFrame() ?>

<div class="wrapper h-100">

  <header id="header">
    <?php
      NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top'],
        'innerContainerOptions' => ['class' => ['container-fluid']],
      ]);

      $userMenuItems = [];
      if (Yii::$app->user->isGuest) {
        $userMenuItems = [
          'label' => Yii::t('aaa', 'Login'),
          'url' => ['/aaa/auth/login'],
        ];
      } else {
        $userMenuItems = [
          'label' => Yii::t('app', 'Menu'),
          'options' => ['class' => 'me-0 ms-auto'],
          'dropdownOptions' => ['class' => 'dropdown-items-reverse'],
          'items' => [
            // '<li class="nav-item">' . Html::a(
            //   'Logout (' . Yii::$app->user->identity->email . ')',
            //   ['/aaa/auth/logout'],
            //   [
            //     'method' => 'post',
            //     'form' => [
            //       'csrf' => false,
            //     ],
            //     'button' => [
            //       'class' => 'nav-link btn btn-link logout',
            //     ],
            //   ]
            // ) . '</li>',
            ['label' => Yii::$app->user->identity->usrEmail ?? (Yii::$app->user->identity->usrMobile ?? '')],
            '<hr class="dropdown-divider">',
            ['label' => Yii::t('aaa', 'My Profile'), 'url' => ['/aaa/profile']],
            // ['label' => Yii::t('aaa', 'Financial'), 'url' => ['/aaa/fin']],
            '<hr class="dropdown-divider">',
            ['label' => Yii::t('aaa', 'Logout'), 'url' => ['/aaa/auth/logout']],
          ],
        ];
      }

      echo Nav::widget([
        'options' => ['class' => 'navbar-nav w-100'],
        'items' => [
          // ['label' => 'Home', 'url' => ['/site']],
          $userMenuItems,
        ]
      ]);
      NavBar::end();
    ?>
  </header>

  <side class="sidebar">
    <?php
      $userInfos = [];

      if ((empty(Yii::$app->user->identity->usrFirstName) == false)
          || (empty(Yii::$app->user->identity->usrLastName) == false))
      {
        $s = [
          Yii::$app->user->identity->usrFirstName ?? '',
          Yii::$app->user->identity->usrLastName ?? '',
        ];
        $userInfos[] = implode(' ', $s);
      }

      if (empty(Yii::$app->user->identity->usrEmail) == false)
        $userInfos[] = Yii::$app->user->identity->usrEmail;

      if (empty(Yii::$app->user->identity->usrMobile) == false)
        $userInfos[] = Yii::$app->user->identity->usrMobile;

      $sidebarItems = [
        [
          'label' => "<div>"
            . "<div class='text-center dir-ltr'>"
            . implode('<br>', $userInfos)
            . "</div>"

            . "<div class='text-center'>"
            . Html::a(Yii::t('aaa', 'My Profile'), '/aaa/profile')
            . ' - '
            . Html::confirmButton(Yii::t('aaa', 'Logout'), '/aaa/auth/logout', Yii::t('aaa', 'Are you sure you want to logout?'), [
              'btn' => false,
            ])

            . "</div>"

            . "<hr class='sidemenu-divider'>"
            . "</div>",
          'encode' => false,
          'url' => null,
        ],
        [
          'label' => Yii::t('app', 'Desktop'),
          'icon' => 'home',
          'url' => '/',
        ],
        [
          'label' => Yii::t('aaa', 'System'),
          // 'icon' => 'question-sign',
          'items' => [
            [
              'label' => Yii::t('app', 'Settings'),
              'items' => [
                ['label' => Yii::t('aaa', 'Roles'), 'icon' => 'info-sign', 'url' => ['/aaa/role']],
                ['label' => Yii::t('aaa', 'Geo Divisions'), 'icon' => 'info-sign', 'url' => ['/aaa/geo-country']],
              ],
            ],
            ['label' => Yii::t('aaa', 'Users'), 'icon' => 'info-sign', 'url' => ['/aaa/user']],
            ['label' => Yii::t('aaa', 'Gateways'), 'icon' => 'info-sign', 'url' => ['/aaa/gateway']],

            [
              'label' => Yii::t('aaa', 'Files'),
              'items' => [
                ['label' => Yii::t('aaa', 'Files'), 'icon' => 'info-sign', 'url' => ['/aaa/upload-file']],
                ['label' => Yii::t('aaa', 'Upload Queue'), 'icon' => 'info-sign', 'url' => ['/aaa/upload-queue']],
              ],
            ],

            [
              'label' => Yii::t('aaa', 'Messages'),
              'items' => [
                ['label' => Yii::t('aaa', 'Messages'), 'icon' => 'info-sign', 'url' => ['/aaa/message']],
                ['label' => Yii::t('aaa', 'Message Templates'), 'icon' => 'info-sign', 'url' => ['/aaa/message-template']],
              ],
            ],
          ],
        ],
        [
          'label' => Yii::t('mha', 'Music House'),
          // 'icon' => 'music',
          'items' => [
            ['label' => Yii::t('mha', 'Members'), 'icon' => 'info-sign', 'url' => ['/mha/member']],
            ['label' => Yii::t('mha', 'Documents'), 'icon' => 'info-sign', 'url' => ['/mha/member-document']],
            [
              'label' => Yii::t('mha', 'Members Memberships'),
              'icon' => 'info-sign',
              // 'url' => ['/mha/member-membership'],
              'url' => ['/mha/accounting/membership-user-asset'],
            ],
            [
              'label' => Yii::t('mha', 'Kanoons'),
              'icon' => 'info-sign',
              'items' => [
                ['label' => Yii::t('mha', 'Kanoons'), 'icon' => 'info-sign', 'url' => ['/mha/kanoon']],
                ['label' => Yii::t('mha', 'Kanoon Members'), 'icon' => 'info-sign', 'url' => ['/mha/member-kanoon']],
              ],
            ],
            [
              'label' => Yii::t('mha', 'Insurance'),
              // 'icon' => 'music',
              'items' => [
                ['label' => Yii::t('mha', 'Master Insurance Documents List'), 'icon' => 'info-sign', 'url' => ['/mha/member-master-ins-doc']],
                ['label' => Yii::t('mha', 'Supplementary Insurance Documents List'), 'icon' => 'info-sign', 'url' => ['/mha/member-supplementary-ins-doc']],
              ],
            ],
            [
              'label' => Yii::t('mha', 'Services Definition and Fee'),
              'icon' => 'info-sign',
              'items' => [
                [
                  'label' => Yii::t('mha', 'Memberships'),
                  'icon' => 'info-sign',
                  'url' => ['/mha/accounting/membership-product'],
                ],
                [
                  'label' => Yii::t('mha', 'Membership Card Products'), //'Card Issuance'),
                  'icon' => 'info-sign',
                  'url' => ['/mha/accounting/membership-card-product'],
                ],
                [
                  'label' => Yii::t('mha', 'Postage'),
                  'icon' => 'info-sign',
                  'url' => ['/mha/accounting/postage-product'],
                ],
              ],
            ],
            [
              'label' => Yii::t('app', 'Settings'),
              'items' => [
                ['label' => Yii::t('mha', 'Master Insurers'), 'icon' => 'info-sign', 'url' => ['/mha/master-insurer']],
                ['label' => Yii::t('mha', 'Supplementary Insurers'), 'icon' => 'info-sign', 'url' => ['/mha/supplementary-insurer']],
                ['label' => Yii::t('mha', 'Specialties'), 'icon' => 'info-sign', 'url' => ['/mha/specialty']],
                ['label' => Yii::t('mha', 'Documents'), 'icon' => 'info-sign', 'url' => ['/mha/document']],
                ['label' => Yii::t('mha', 'Basic Definitions'), 'icon' => 'info-sign', 'url' => ['/mha/basic-definition']],
              ],
            ],
          ],
        ],
        [
          'label' => Yii::t('aaa', 'Financial'),
          // 'icon' => 'badge-dollar',
          'items' => [
            // ['label' => Yii::t('aaa', 'Wallets'), 'icon' => 'info-sign', 'url' => ['/aaa/wallet']],
            // ['label' => Yii::t('aaa', 'Wallet Transactions'), 'icon' => 'info-sign', 'url' => ['/aaa/wallet-transaction']],
            // ['label' => Yii::t('aaa', 'Payment gateways'), 'icon' => 'info-sign', 'url' => ['/aaa/payment-gateway']],
            // ['label' => Yii::t('aaa', 'Vouchers'), 'icon' => 'info-sign', 'url' => ['/aaa/voucher']],
            ['label' => Yii::t('aaa', 'Baskets'), 'icon' => 'info-sign', 'url' => ['/aaa/basket']],
            ['label' => Yii::t('aaa', 'Orders'), 'icon' => 'info-sign', 'url' => ['/aaa/order']],
            ['label' => Yii::t('aaa', 'Online Payments'), 'icon' => 'info-sign', 'url' => ['/aaa/online-payment']],
            ['label' => Yii::t('aaa', 'Offline Payments'), 'icon' => 'info-sign', 'url' => ['/aaa/offline-payment']],
          ],
        ],
        [
          'label' => Yii::t('aaa', 'Reports'),
          // 'icon' => 'badge-dollar',
          'items' => [
            ['label' => Yii::t('mha', 'Reports'), 'icon' => 'info-sign', 'url' => ['/mha/report']],
          ],
        ],
      ];

      echo SideNav::widget([
        'type' => SideNav::TYPE_SECONDARY,
        // 'heading' => 'Options',
        'containerOptions' => [
          'class' => ['h-min-100', 'border-0'],
        ],
        // 'addlCssClass' => 'text-secondary',

        'indMenuOpen' => '<i class="indicator fas fa-angle-down"></i>',
        'indMenuClose' => '<i class="indicator fas fa-angle-left"></i>',
        'iconPrefix' => 'fas fa-',

        'activateParents' => true,
        'hideEmptyItems' => false,

        'items' => $sidebarItems,
      ]);
    ?>
  </side>

  <main id="layout-main" class="content h-min-100" role="main" style="position: relative;">
    <div class="container">
      <?php if (!empty($this->params['breadcrumbs'])): ?>
        <?= Breadcrumbs::widget([
          'links' => $this->params['breadcrumbs'],
          'encodeLabels' => false,
        ]) ?>
      <?php endif ?>
      <?= Alert::widget() ?>
      <?= $content ?>
    </div>
  </main>

  <footer id="footer" class="footer mt-auto py-3 bg-light">
    <div class="container">
      <div class="row text-muted">
        <div class="col text-end">اتوماسیون خانه موسیقی ایران - نسخه 3.0</div>
      </div>
    </div>
  </footer>

</div>

<?php $this->endMainFrame() ?>
<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
