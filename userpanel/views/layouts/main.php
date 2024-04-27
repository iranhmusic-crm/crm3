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
  <title>خانه موسیقی من - <?= Html::encode($this->title) ?></title>
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

      $userMenuItems = [
        [
          'label' => Yii::t('aaa', 'Shopping Card'),
          'url' => ['/aaa/basket'],
          'options' => ['class' => 'me-0 ms-auto'],
        ],
      ];

      if (Yii::$app->user->isGuest) {
        $userMenuItems = array_merge($userMenuItems, [
          [
            'label' => Yii::t('aaa', 'Login'),
            'url' => ['/aaa/auth/login'],
          ]
        ]);
      } else {
        $userMenuItems = array_merge($userMenuItems, [
          [
            'label' => Yii::t('app', 'Menu'),
            // 'options' => ['class' => 'me-0 ms-auto'],
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
              ['label' => Yii::t('aaa', 'Financial'), 'url' => ['/aaa/fin']],
              '<hr class="dropdown-divider">',
              ['label' => Yii::t('aaa', 'Logout'), 'url' => ['/aaa/auth/logout']],
            ],
          ],
        ]);
      }

      echo Nav::widget([
        'options' => ['class' => 'navbar-nav w-100'],
        'items' =>
          // ['label' => 'Home', 'url' => ['/site']],
          $userMenuItems,
      ]);
      NavBar::end();
    ?>
  </header>

  <side class="sidebar">
    <?php
      if (Yii::$app->user->isGuest == false) {
        $memberModel = Yii::$app->member->memberModel;

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
        ];

        if ($memberModel != null) {
          $sidebarItems = array_merge($sidebarItems, [
            [
              'label' => Yii::t('mha', 'Sponsorships'),
              // 'icon' => 'badge-dollar',
              'url' => '/mha/member-sponsorship',
            ],
            [
              'label' => Yii::t('mha', 'Specialties'),
              // 'icon' => 'badge-dollar',
              'url' => '/mha/member-specialty',
            ],
            [
              'label' => Yii::t('mha', 'Kanoons'),
              // 'icon' => 'badge-dollar',
              'url' => '/mha/member-kanoon',
            ],
            [
              'label' => Yii::t('mha', 'Insurance'),
              'items' => [
                [
                  'label' => Yii::t('mha', 'Master Insurances'),
                  // 'icon' => 'badge-dollar',
                  'url' => '/mha/member-master-insurance',
                ],
                [
                  'label' => Yii::t('mha', 'Master Insurance Documents'),
                  // 'icon' => 'badge-dollar',
                  'url' => '/mha/member-master-ins-doc',
                ],
                [
                  'label' => Yii::t('mha', 'Supplementary Insurance Documents'),
                  // 'icon' => 'badge-dollar',
                  'url' => '/mha/member-supplementary-ins-doc',
                ],
              ],
            ],
            [
              'label' => Yii::t('mha', 'Documents'),
              // 'icon' => 'badge-dollar',
              'url' => '/mha/member-document',
            ],
          ]);

          if (empty($memberModel->mbrRegisterCode) == false) {
            $sidebarItems = array_merge($sidebarItems, [
              [
                'label' => Yii::t('mha', 'Memberships'),
                // 'icon' => 'badge-dollar',
                // 'url' => '/mha/member-membership',
                'url' => '/mha/accounting/membership',
              ],
            ]);
          }
        }

        $sidebarItems = array_merge($sidebarItems, [
          [
            'label' => Yii::t('aaa', 'Financial'),
            // 'icon' => 'badge-dollar',
            'url' => '/aaa/fin',
          ],
        ]);

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
      }
    ?>
  </side>

  <main id="layout-main" class="content h-min-100" role="main" style="position: relative;">
    <div class="container">
      <?php if (!empty($this->params['breadcrumbs']))
        echo Breadcrumbs::widget([
          'homeLink' => [
            'label' => Yii::t('mha', 'My Music House'),
            'url' => Yii::$app->homeUrl,
          ],
          'links' => $this->params['breadcrumbs'],
        ]);
      ?>
      <?= Alert::widget() ?>
      <?= $content ?>
    </div>
  </main>

  <footer id="footer" class="footer mt-auto py-3 bg-light">
    <div class="container">
      <div class="row text-muted">
        <div class="col text-end">اتوماسیون خانه موسیقی ایران - نسخه 3.1</div>
      </div>
    </div>
  </footer>

</div>

<?php $this->endMainFrame() ?>
<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
