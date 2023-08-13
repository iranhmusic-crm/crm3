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
  <title>خانه موسیقی من - <?= Html::encode($this->title) ?></title>
  <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>
<?php $this->beginMainFrame() ?>

<main id="layout-login" class="flex-shrink-0 h-100" role="main">
  <div class="container-fluid h-100">
    <div class="row h-100">
      <div class="col-md-4 d-flex login-sidebar">
        <?= Alert::widget() ?>
        <?= $content ?>
      </div>
      <div class="col d-flex login-center">
        <div class="w-100 text-center">
          <p><?= Html::img('/images/logo_main_bw_h200.png') ?></p>
        </div>
      </div>
    </div>
  </div>
</main>

<?php $this->endMainFrame() ?>
<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
