<?php
/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use shopack\base\frontend\helpers\Html;

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

<body class="h-100 d-flex flex-column">
<?php $this->beginBody() ?>
<?php $this->beginMainFrame() ?>

<main id="layout-login" class="flex-shrink-0 h-min-100" role="main" style="position: relative;">
  <div class="container-fluid">
    <div class="row h-min-100">
      <div class="col-md-4 d-flex login-sidebar min-vh-100">
        <?= Alert::widget() ?>
        <?= $content ?>
      </div>
      <div class="col d-flex login-center min-vh-100">
        <div class='w-100 text-center'>
          <p><?= Html::img('/images/logo_main_bw_h200.png') ?></p>
          <p>کاربر محترم</p>
          <p>این پنل مختص مدیران و اپراتورهای سیستم میباشد.</p>
          <p>در صورتیکه شما قصد دارید به عنوان اپراتور در سیستم فعالیت کنید، ابتدا باید در این پنل ثبت نام کرده و پس از تایید ایمیل خود، افزایش سطح دسترسی را از مدیر سیستم درخواست کنید.</p>
          <p>در غیر اینصورت از طریق پنل اعضا اقدام به ثبت نام و یا ورود به سیستم کنید.</p>
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
