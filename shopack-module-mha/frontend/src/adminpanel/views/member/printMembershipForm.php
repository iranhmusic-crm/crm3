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
use shopack\aaa\common\enums\enuUserEducationLevel;
use shopack\aaa\common\enums\enuUserMaritalStatus;
use shopack\aaa\common\enums\enuUserMilitaryStatus;

$this->title = 'چاپ فرم عضویت';
$this->params['breadcrumbs'][] = Yii::t('mha', 'Music House');
$this->params['breadcrumbs'][] = ['label' => Yii::t('mha', 'Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->displayName(), 'url' => ['view', 'id' => $model->mbrUserID]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$css =<<<CSS
@font-face {
  font-family: 'Nassim';
  src: url('/fonts/MehrazNassim.eot');
  /* IE9 Compat Modes */
  src: url('/fonts/MehrazNassim.eot?#iefix') format('embedded-opentype'),
    /* IE6-IE8 */
    url('/fonts/MehrazNassim.woff') format('woff'),
    /* Modern Browsers */
    url('/fonts/MehrazNassim.ttf') format('truetype');
    /* Safari, Android, iOS */
}

body {
  direction: rtl;
  padding: 0px;
  margin: 0px;
  font-family: "Nassim";
  display: table;
  width: 100%;
  /* height: 100%; */
}

.container {
  display: table-cell;
}

.a4 {
  width: 19cm;
  height: 27.7cm;
  max-height: 27.7cm;
  margin: auto;
  padding: 5mm;
  border-width: 2mm;
  border-color: black;
  border-style: double;
}

.title {
  font-size: 20px;
  font-weight: 800;
  margin: 0;
  padding: 0;
  text-align: center;
  padding-bottom: 5mm;
}

p {
  font-size: 17px;
  line-height: 32px;
  text-align: justify;
}

strong {
  font-weight: 800;
}

.fieldLabel {
  font-weight: 800;
}

.fieldValue {
  display: inline-block;
  padding-right: 1mm;
}

.dir-ltr {
  direction: ltr;
}

.center {
  text-align: center;
}

.left {
  text-align: left;
}

.box1 {
  width: 4.5cm;
  height: 3cm;
  border: .4mm solid black;
  border-radius: 5mm;
  padding: 3mm;
}
.box1 div {
  height: 8mm;
}

.logo_type img {
  height: 3cm;
}
.logo_title img {
  height: 7mm;
}

.photobox {
  width: 3cm;
  height: 4cm;
  border: .4mm solid black;
  border-radius: 5mm;
}

CSS;

$this->registerCss($css);

//clubs
$searchModel = new MemberKanoonModel();
$mbrkanoons = $searchModel->find()
  ->andWhere(['mbrknnMemberID' => $model->mbrUserID])
  ->andWhere(['mbrknnStatus' => enuMemberKanoonStatus::Accepted])
  ->orderBy('mbrknnIsMaster DESC')
  ->all();

$kanoonNames = [];
$kanoonDates = [];
foreach ($mbrkanoons as $mbrkanoon) {
  $kanoonNames[] = $mbrkanoon->kanoon->knnName;
  $kanoonDates[] = Yii::$app->formatter->asPersianNum(Yii::$app->formatter->asJalali($mbrkanoon->mbrknnAcceptedAt));
}
$kanoonNames = implode(' - ' , $kanoonNames);
$kanoonDates = implode(' - ' , $kanoonDates);
?>

<div class="a4">

  <div class='row'>
    <div class='col'>
      <div class='box1'>
        <div><span class='fieldLabel'>کد عضویت:</span><span class='fieldValue'><?= Yii::$app->formatter->asPersianNum($model->mbrRegisterCode) ?></span></div>
        <div><span class='fieldLabel'>نام کانون:</span><span class='fieldValue'><?= $kanoonNames ?></span></div>
        <div><span class='fieldLabel'>تاریخ:</span><span class='fieldValue'></span><?= $kanoonDates ?></div>
      </div>
    </div>
    <div class='col center'>
      <div class="logo_type"><img src="/images/logo_type.jpg"></div>
      <div class="logo_title"><img src="/images/logo_iran.jpg"></div>
    </div>
    <div class='col left'>
      <div class='photobox' style='display: inline-block;'>
      </div>
    </div>
  </div>

  <div class='title'>پرسش نامه عضویت</div>

  <div class='row'>
    <div class='col'>
      <span class='fieldLabel'>جنسیت:</span><span class='fieldValue'><?= ($model->user->usrGender == enuGender::Male ? 'آقا' : ($model->user->usrGender == enuGender::Female ? 'خانم' : '')) ?></span>
    </div>
    <div class='col'>
      <span class='fieldLabel'>کد ملی:</span><span class='fieldValue'><?= Yii::$app->formatter->asPersianNum($model->user->usrSSID) ?></span>
    </div>
    <div class='col'>
      <span class='fieldLabel'>شماره شناسنامه:</span><span class='fieldValue'><?= Yii::$app->formatter->asPersianNum($model->user->usrBirthCertID) ?></span>
    </div>
    <div class='col'>
      <span class='fieldLabel'>تاریخ تولد:</span><span class='fieldValue'><?= Yii::$app->formatter->asPersianNum(Yii::$app->formatter->asJalali($model->user->usrBirthDate)) ?></span>
    </div>
  </div>

  <div class='row'>
    <div class='col'>
      <span class='fieldLabel'>نام:</span><span class='fieldValue'><?= $model->user->usrFirstName ?></span>
    </div>
    <div class='col'>
      <span class='fieldLabel'>نام خانوادگی:</span><span class='fieldValue'><?= $model->user->usrLastName ?></span>
    </div>
    <div class='col'>
      <span class='fieldLabel'>نام پدر:</span><span class='fieldValue'><?= $model->user->usrFatherName ?></span>
    </div>
    <div class='col'>
      <span class='fieldLabel'>محل تولد:</span><span class='fieldValue'></span>
    </div>
  </div>

  <div class='row'>
    <div class='col'>
      <span class='fieldLabel'>نام لاتین:</span><span class='fieldValue dir-ltr'><?= $model->user->usrFirstName_en ?></span>
    </div>
    <div class='col'>
      <span class='fieldLabel'>نام خانوادگی لاتین:</span><span class='fieldValue dir-ltr'><?= $model->user->usrLastName_en ?></span>
    </div>
    <div class='col'>
      <span class='fieldLabel'>نام پدر لاتین:</span><span class='fieldValue dir-ltr'><?= $model->user->usrFatherName_en ?></span>
    </div>
    <div class='col'>
      <span class='fieldLabel'>محل تولد لاتین:</span><span class='fieldValue dir-ltr'></span>
    </div>
  </div>

  <div class='row'>
    <div class='col'>
      <span class='fieldLabel'>آخرین مدرک تحصیلی:</span><span class='fieldValue'><?= enuUserEducationLevel::getLabel($model->user->usrEducationLevel) ?></span>
    </div>
    <div class='col'>
      <span class='fieldLabel'>رشته تحصیلی:</span><span class='fieldValue'><?= $model->user->usrFieldOfStudy ?></span>
    </div>
    <div class='col'>
      <span class='fieldLabel'>دانشگاه:</span><span class='fieldValue'><?= $model->user->usrEducationPlace ?></span>
    </div>
    <div class='col'>
      <span class='fieldLabel'>شغل اصلی:</span><span class='fieldValue'></span>
    </div>
  </div>

  <div class='row'>
    <div class='col'>
      <span class='fieldLabel'>درجه هنری:</span><span class='fieldValue'></span>
    </div>
    <div class='col'>
      <span class='fieldLabel'>عضویت صندوق هنر:</span><span class='fieldValue'></span>
    </div>
    <div class='col'>
      <span class='fieldLabel'>وضعیت نظام وظیفه:</span><span class='fieldValue'><?= enuUserMilitaryStatus::getLabel($model->user->usrMilitaryStatus) ?></span>
    </div>
    <div class='col'>
      <span class='fieldLabel'>وضعیت تاهل:</span><span class='fieldValue'><?= enuUserMaritalStatus::getLabel($model->user->usrMaritalStatus) ?></span>
    </div>
  </div>

</div>
