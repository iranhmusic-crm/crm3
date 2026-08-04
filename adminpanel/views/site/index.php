<?php
/** @var yii\web\View $this */

use shopack\base\frontend\common\helpers\Html;

$this->title = 'پیشخوان';
?>

<div class="site-index w-100 min-vh-100 d-grid" style="align-content: center;">
  <div class="jumbotron text-center bg-transparent">
    <p><?= Html::img('/images/logo_main_bw_h200.png') ?></p>

    <?php
      $user = Yii::$app->user->identity;
      $mustApprove = $user->jwtPayload['mustApprove'] ?? null;
      if (empty($mustApprove) == false
      // if ((empty($user->usrEmail) == false && empty($user->usrEmailApprovedAt))
      //   || (empty($user->usrMobile) == false && empty($user->usrMobileApprovedAt))
      ) {
        echo "<p>بدلیل عدم تایید ایمیل و یا موبایل وارد شده، دسترسی شما معادل کاربر عادی قرار داده شده است و پس از تایید ایمیل و موبایل، به دسترسی تعیین شده توسط مدیر بازمی‌گردد.</p>";
      }
    ?>
  </div>
</div>
