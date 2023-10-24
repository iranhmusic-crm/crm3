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

  <div class="container">
    <?= $content ?>
  </div>

  <?php $this->endMainFrame() ?>
  <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
