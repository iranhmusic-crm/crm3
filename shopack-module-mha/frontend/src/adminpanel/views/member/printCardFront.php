<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\frontend\common\helpers\Html;
use shopack\aaa\common\enums\enuGender;
use iranhmusic\shopack\mha\frontend\common\models\MemberKanoonModel;
use iranhmusic\shopack\mha\common\enums\enuMemberKanoonStatus;
use iranhmusic\shopack\mha\common\enums\enuKanoonMembershipDegree;

$this->title = Yii::t('mha', 'Print Card (Front)');
$this->params['breadcrumbs'][] = Yii::t('mha', 'Music House');
$this->params['breadcrumbs'][] = ['label' => Yii::t('mha', 'Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->displayName(), 'url' => ['view', 'id' => $model->mbrUserID]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$css =<<<CSS
@font-face {
  font-family: 'Nassim';
  src: url('/fonts/MehrazNassim.eot'); /* IE9 Compat Modes */
  src: url('/fonts/MehrazNassim.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
        url('/fonts/MehrazNassim.woff') format('woff'), /* Modern Browsers */
        url('/fonts/MehrazNassim.ttf')  format('truetype'); /* Safari, Android, iOS */
}
body {
  direction: rtl;
  padding: 0px;
  margin: 0px;
}
body, html {
  display:table;
  height:100%;
  width:100%;
}
.container {
  display:table-cell;
  vertical-align:middle;
}
.cardbox {
  width: 8.5cm;
  height: 5cm;
/*            border: 1px solid #000;*/
  position: relative;
  font-family: "Nassim";
  /*padding-right: 0.5cm;
  padding-left: 0.5cm;
  padding-top: 60px;*/
  margin: 0 auto;
/*            transform: scale(2,2);*/
}
.info {
  right: 7px;
  left: 7px;
  bottom: 4px;
/*            height: 110px;*/
  position: absolute;
}
.img img {
width: 180px;
height: 180px;
float: right;
}
label {
  color: #000;
  float: right;
  padding: 2px 0px;
  width: 96px;
  direction: rtl;
}
h1 {
  float: right;
  font-size: 12px;
  font-weight: normal;
  margin: 0;
  padding: 0;
  padding-left: 2px;
}
h3 {
  float: right;
  font-size: 12px;
  font-weight: normal;
  margin: 0;
  padding: 0;
  padding-left: 2px;
}
h1:after {
  content: ":";
  /* padding-right: 2px; */
  padding-left: 2px;
}
h2 {
  font-size: 12px;
  font-weight: normal;
  margin: 0;
  padding: 0;
}
.logo_type {
  position: absolute;
  top: 5px;
  right: 5px;
}
.logo_type img {
  height: 83px;
}
.logo_title {
  position: absolute;
  top: 37px;
  left: 0px;
  right: 0px;
  margin: auto;
  width: 100%;
  text-align: center;
}
.logo_title img {
  height: 42px;
}
.user_img {
  position: absolute;
  top: 8px;
  left: 5px;
  width: 75px;
  height: 95px;
}
.sign {
  width: 60px;
  height: 60px;
  position: absolute;
  left:0px;
  bottom: -10px;
}
.sign img {
  height: 52px;
  width: auto;
}
CSS;

$this->registerCss($css);

$kanoonNames = [];
$kanoonDegrees = [];
foreach ($mbrkanoons as $mbrkanoon) {
  $kanoonNames[] = $mbrkanoon->kanoon->knnName;
  $kanoonDegrees[] = Yii::$app->formatter->asPersianNum(enuKanoonMembershipDegree::getLabel($mbrkanoon->mbrknnMembershipDegree));
}
$kanoonNames = implode(' - ' , $kanoonNames);
$kanoonDegrees = implode(' - ' , $kanoonDegrees);
?>

<div class="cardbox">
  <div class="sign"><img src="/images/sign.png"></div>
  <div class="logo_type"><img src="/images/logo_type.jpg"></div>
  <div class="logo_title"><img src="/images/logo_iran.jpg"></div>
  <div class="user_img" style="background:url(<?= $model->user->imageFile->fullFileUrl ?? null ?>);background-size: cover;background-position: center center;"></div>
  <div class="info">
    <label style="width: 31%;"><h1>نام</h1><h2><?= $model->user->usrFirstName ?></h2></label>
    <label style="width: 69%;"><h1>نام خانوادگی</h1><h2><?= $model->user->usrLastName ?></h2></label>
    <label><h1>تاریخ تولد</h1><h2><?= Yii::$app->formatter->asPersianNum(Yii::$app->formatter->asJalali($model->user->usrBirthDate)) ?></h2></label>
    <label><h1>کد ملی</h1><h2><?= Yii::$app->formatter->asPersianNum($model->user->usrSSID) ?></h2></label>
    <label><h1>نام پدر</h1><h2><?= $model->user->usrFatherName ?></h2></label>
    <label style="width: 46%;"><h1>کد و نوع عضویت</h1><h2><?= Yii::$app->formatter->asPersianNum($model->mbrRegisterCode) ?> - <?= $kanoonDegrees ?></h2></label>
    <label style="width: 143px;"><h1>کانون</h1><h2><?= $kanoonNames ?></h2></label>
    <label><h1>اعتبار</h1><h2><?= Yii::$app->formatter->asPersianNum(Yii::$app->formatter->asJalali($model->mbrExpireDate)) ?></h2></label>
    <label style="text-align: left;width: 88px;float: left;"><h3 style="width:100%;">مدیر عامل </h3></label>
  </div>
</div>
