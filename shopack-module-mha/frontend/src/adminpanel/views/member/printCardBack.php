<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\frontend\helpers\Html;
use shopack\aaa\common\enums\enuGender;
use iranhmusic\shopack\mha\frontend\common\models\MemberKanoonModel;
use iranhmusic\shopack\mha\common\enums\enuMemberKanoonStatus;
use iranhmusic\shopack\mha\common\enums\enuKanoonMembershipDegree;

$this->title = Yii::t('mha', 'Print Card (Back)');
$this->params['breadcrumbs'][] = Yii::t('mha', 'Music House');
$this->params['breadcrumbs'][] = ['label' => Yii::t('mha', 'Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->displayName(), 'url' => ['view', 'id' => $model->mbrUserID]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$css =<<<CSS
@font-face {
  font-family: 'proxima';
  src: url('/fonts/Proxima-Nova-Regular.eot'); /* IE9 Compat Modes */
  src: url('/fonts/Proxima-Nova-Regular.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
        url('/fonts/Proxima-Nova-Regular.woff') format('woff'), /* Modern Browsers */
        url('/fonts/Proxima-Nova-Regular.ttf')  format('truetype'); /* Safari, Android, iOS */
}
@font-face {
  font-family: 'acaslonpro-italic';
  src: url('/fonts/acaslonpro-italic-webfont.eot'); /* IE9 Compat Modes */
  src: url('/fonts/acaslonpro-italic-webfont.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
        url('/fonts/acaslonpro-italic-webfont.woff') format('woff'), /* Modern Browsers */
        url('/fonts/acaslonpro-italic-webfont.ttf')  format('truetype'); /* Safari, Android, iOS */
}
@font-face {
  font-family: 'acaslonpro-regular';
  src: url('/fonts/acaslonpro-regular-webfont.eot'); /* IE9 Compat Modes */
  src: url('/fonts/acaslonpro-regular-webfont.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
        url('/fonts/acaslonpro-regular-webfont.woff') format('woff'), /* Modern Browsers */
        url('/fonts/acaslonpro-regular-webfont.ttf')  format('truetype'); /* Safari, Android, iOS */
}
body {
  direction: ltr;
  padding: 0px;
  margin: 0px;
}
body, html{
  display:table;
  height:100%;
  width:100%;
  font-family: "proxima";
}
.container{
  display:table-cell;
  vertical-align:middle;
}
.cardbox {
  width: 8.5cm;
  height: 5cm;
/*            border: 1px solid #000;*/
  position: relative;
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
float: left;
}
label {
  color: #000;
  float: left;
  padding: 2px 0px;
  width: 50%;
  direction: ltr;
}
.logo_title h4 {
  font-family: "acaslonpro-regular";
  margin: 0px;
  font-size: 17px;
}
.logo_title p {
  font-family: "acaslonpro-regular";
  margin: 0px;
  font-size: 13px;
  position: absolute;
  top: 22px;
  width: 100%;
  font-style: italic;
}
h1 {
  float: left;
  font-size: 11px;
  font-weight: bold;
  margin: 0;
  padding: 0;
  padding-left: 2px;
}
h3 {
  float: left;
  font-size: 11px;
  font-weight: bold;
  margin: 0;
  padding: 0;
  padding-left: 2px;
}
h1:after {
  content: ":";
  padding-right: 2px;
  /* padding-left: 2px; */
}
h2 {
  font-size: 11px;
  font-weight: normal;
  margin: 0;
  padding: 0;
  float: left;
}
.logo_type {
  position: absolute;
  top: 5px;
  left: 5px;
}
.logo_type img {
  height: 83px;
}
.logo_title {
  position: absolute;
  top: 25px;
  left: 24px;
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
  width: 70px;
  height: 80px;
}
CSS;

$this->registerCss($css);
?>

<div class="cardbox">
  <div class="logo_type"><img src="/images/logo_type.jpg"></div>
  <div class="logo_title"><h4>House Of Music</h4><p>Association For Iranian Musician</p></div>
  <div class="info">
    <label style="width:100%;"><h1>Full Name</h1><h2>
      <?php
        $parts = [];
        if ($model->user->usrGender == enuGender::Male)
          $parts[] = 'Mr.';
        else if ($model->user->usrGender == enuGender::Female)
          $parts[] = 'Ms.';

        $parts[] = ucfirst($model->user->usrFirstName_en);
        $parts[] = strtoupper($model->user->usrLastName_en);

        echo implode(' ', $parts);
      ?>
    </h2></label>
    <label><h1>Birthday</h1><h2><?= (new \DateTime($model->user->usrBirthDate))->format('Y/m/d') ?></h2></label>
    <label><h1>National Code</h1><h2><?= $model->user->usrSSID ?></h2></label>
    <label><h1>Father Name</h1><h2><?= ucfirst($model->user->usrFatherName_en) ?></h2></label>
    <label><h1>Club</h1><h2><?= $mbrkanoon->kanoon->knnNameEn ?></h2></label>
    <label style="width: 100%;"><h1>Member Code</h1><h2><?= $model->mbrRegisterCode ?> - <?= enuKanoonMembershipDegree::$list[$mbrkanoon->mbrknnMembershipDegree] ?></h2></label>
    <label><h1>Expire Date</h1><h2><?php
      if (empty($model->mbrExpireDate) == false)
        echo (new \DateTime($model->mbrExpireDate))->format('Y/m/d');
    ?></h2></label>
  </div>
</div>
