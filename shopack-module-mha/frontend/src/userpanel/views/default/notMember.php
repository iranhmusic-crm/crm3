<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

$this->title = 'عضویت';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-index w-100 min-h-100 d-grid" style="align-content: center;">
  <div class="jumbotron text-center bg-transparent">
    <p>&nbsp;</p>
    <?php
      $jwtPayload = Yii::$app->user->identity->jwtPayload;
      $mustApprove = $jwtPayload['mustApprove'] ?? '';
      $mustApprove_email = false;
      $mustApprove_mobile = false;
      if (isset($mustApprove)) {
        $mustApprove = ',' . $mustApprove . ',';
        $mustApprove_email = (strpos($mustApprove, ',email,') !== false);
        $mustApprove_mobile = (strpos($mustApprove, ',mobile,') !== false);
      }
    ?>
    <p>کاربر محترم: <span class='d-inline-block dir-ltr'><?= Yii::$app->user->identity->usrEmail ?? (Yii::$app->user->identity->usrMobile ?? '') ?></span></p>
    <p>شما در خانه موسیقی عضو نیستید.</p>
    <?php
      if ($mustApprove_email || $mustApprove_mobile) {
        $approves = implode(' و ', array_filter([
          $mustApprove_email ? 'ایمیل' : null,
          $mustApprove_mobile ? 'موبایل' : null,
        ]));
    ?>
      <p>در صورت تمایل به عضویت در خانه موسیقی، ابتدا <?= $approves ?> خود را تایید کرده، سپس به همین صفحه مراجعه کنید.</p>
    <?php } else { ?>
      <p>در صورت تمایل به عضویت در خانه موسیقی، بر روی لینک زیر کلیک کنید:</p>
      <a href='mha/member/signup' class='btn btn-success'><?= Yii::t('mha', 'Signup Member') ?></a>
    <?php } ?>
  </div>
</div>
