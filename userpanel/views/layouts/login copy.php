<?php
/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;

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
  <title>خانه موسیقی - <?= Html::encode($this->title) ?></title>
  <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>
<?php $this->beginMainFrame() ?>

<header id="header">
  <?php
    NavBar::begin([
      'brandLabel' => Html::img('/images/logo_main.png', [
        'height' => '60px',
      ]),
      'brandUrl' => Yii::$app->homeUrl,
      'brandOptions' => [
        'class' => [
          'py-0',
        ],
      ],
      'innerContainerOptions' => [
        'class' => [
          'container-fluid',
        ],
      ],
      'options' => [
        'class' => [
          'navbar-expand-md',
          'navbar-dark',
          'bg-dark',
          'fixed-top',
          'py-0',
        ],
      ],
    ]);

    echo Nav::widget([
      'options' => ['class' => 'navbar-nav'],
      'items' => [
        // ['label' => 'Home', 'url' => ['/site/index']],
        // ['label' => 'About', 'url' => ['/site/about']],
        // ['label' => 'Contact', 'url' => ['/site/contact']],
        // Yii::$app->user->isGuest
        //   ? ['label' => 'Login', 'url' => ['/site/login']]
        //   : '<li class="nav-item">'
        //     . Html::beginForm(['/site/logout'])
        //     . Html::submitButton(
        //       'Logout (' . Yii::$app->user->identity->username . ')',
        //       ['class' => 'nav-link btn btn-link logout']
        //     )
        //     . Html::endForm()
        //     . '</li>'
      ]
    ]);
    NavBar::end();
  ?>
</header>

<main id="layout-login" class="flex-shrink-0 h-100" role="main">
  <div class="container-fluid h-100">
    <div class="row h-100">
      <div class="col-md-4 d-flex login-sidebar">
        <?= Alert::widget() ?>
        <?= $content ?>
      </div>
      <div class="col d-flex login-center">
        <div class="w-100 text-center">
          <p><?= Html::img('/images/logo_main_bw.png', ['height' => '150px']) ?></p>
          <br>
          <h1>سامانه جامع هنرمندان</h1>
        </div>
      </div>
    </div>
  </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-light">
  <div class="container">
    <div class="row text-muted">
      <div class="col-md-6 text-center text-md-start">&copy; My Company <?= date('Y') ?></div>
      <div class="col-md-6 text-center text-md-end"><?= Yii::powered() ?></div>
    </div>
  </div>
</footer>

<?php $this->endMainFrame() ?>
<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
