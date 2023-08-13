<?php
/** @var yii\web\View $this */
$this->title = 'پیشخوان';
?>

<div class="site-index w-100 min-vh-100 d-grid" style="align-content: center;">
  <div class="jumbotron text-center bg-transparent">
    <h1 class="w-50 mx-auto pb-3" style="border-bottom: 2px dashed #318fa9">خانه موسیقی ایران</h1>
    <h2>پنل مدیریت</h2>

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
