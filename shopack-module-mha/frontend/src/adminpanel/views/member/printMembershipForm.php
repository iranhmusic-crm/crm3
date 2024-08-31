<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\frontend\common\helpers\Html;
use shopack\base\frontend\common\web\View;
use shopack\aaa\common\enums\enuGender;
use shopack\aaa\common\enums\enuUserEducationLevel;
use shopack\aaa\common\enums\enuUserMaritalStatus;
use shopack\aaa\common\enums\enuUserMilitaryStatus;
use iranhmusic\shopack\mha\common\enums\enuMemberKanoonStatus;
use iranhmusic\shopack\mha\frontend\common\models\MemberKanoonModel;

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
  font-size: 11pt;
  display: table;
  width: 100%;
  /* height: 100%; */
}

.print-container {
  display: table-cell;
  width: 100%;
  height: 100%;
  padding: 0;
  margin: 0;
}

.a4 {
  position: relative;
  width: 19cm;
  height: 27.7cm;
  max-width: 19cm;
  max-height: 27.7cm;
  margin: auto;
  padding: .5mm;
  border-width: .5mm;
  border-color: black;
  border-style: solid;
  page-break-after: always;
}
.a4-middle {
  position: relative;
  width: 100%;
  height: 100%;
  margin: 0;
  padding: .5mm;
  border-width: .7mm;
  border-color: black;
  border-style: solid;
}
.a4-inner {
  position: relative;
  width: 100%;
  height: 100%;
  margin: 0;
  padding: 5mm;
  border-width: .5mm;
  border-color: black;
  border-style: solid;
}

.title {
  font-size: 14pt;
  font-weight: 800;
  margin: 0;
  padding: 0;
  text-align: center;
  padding-bottom: 2mm;
}

p {
  /* font-size: 17px;
  line-height: 32px;
  text-align: justify; */
}

strong {
  font-weight: 800;
}

.fieldLabel {
  font-size: 10pt;
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

.bordered {
  border-width: .4mm .4mm 0 .4mm;
  border-style: solid;
  border-color: black;
}
.bordered:last-child {
  border-bottom-width: .4mm;
}

.table-bordered {
  border-width: .4mm;
  border-style: solid;
  border-color: black;
}

.table > :not(caption) > * > * {
  padding: 1mm !important;
}

.box1 {
  width: 4.5cm;
  height: 3cm;
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
  display: inline-block;
  width: 3cm;
  height: 4cm;
  border: .4mm solid black;
  border-radius: 5mm;
  overflow: hidden;
}

.user_img {
  position: relative;
  top: 0;
  left: 0;
  width: 3cm;
  height: 4cm;
}

.memberinfobox {
  height: 6cm;
}

hr.dotted {
  border: 0;
  border-top-width: .4mm;
  border-top-style: dashed;
  border-top-color: black !important;
  opacity: 1;
  padding: 0;
  margin: 2mm 0;
}

.checked-item {
  display: inline-block;
  margin-left: 3mm;
}

.checkbox {
  display: inline-block;
  width: 4mm;
  height: 4mm;
  border: .4mm solid black;
  margin-left: 2mm;
}

.me-4cm {
  margin-left: 4cm;
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

if (empty($model->user->usrBirthCityID) == false) {
  $cityModel = $model->user->birthCityOrVillage;
}
?>

<div class="a4">
  <div class="a4-middle">
    <div class="a4-inner">

      <div class='row'>
        <div class='col'>
          <div class='box1 bordered'>
            <div><span class='fieldLabel'>کد عضویت:</span><span class='fieldValue'><?= Yii::$app->formatter->asPersianNum($model->mbrRegisterCode) ?></span></div>
            <div><span class='fieldLabel'>نام کانون:</span><span class='fieldValue'><?= Html::encode($kanoonNames) ?></span></div>
            <div><span class='fieldLabel'>تاریخ:</span><span class='fieldValue'></span><?= Html::encode($kanoonDates) ?></div>
          </div>
        </div>
        <div class='col center'>
          <div class="logo_type"><img src="/images/logo_type.jpg"></div>
          <div class="logo_title"><img src="/images/logo_iran.jpg"></div>
          <div class='title'>پرسش نامه عضویت</div>
        </div>
        <div class='col left'>
          <div class='photobox'><?php
            if ((empty($model->user->usrImageFileID) == false)
                && (empty($model->user->imageFile->fullFileUrl) == false)
            ) {
              echo '<div class="user_img" style="background:url(\''
                . $model->user->imageFile->fullFileUrl
                . '\');background-size: cover;background-position: center center;"></div>';
            }
          ?></div>
        </div>
      </div>

      <table class='table table-borderless'>
        <tr>
          <td><span class='fieldLabel'>نام:</span><span class='fieldValue'><?= Html::encode($model->user->usrFirstName) ?></span></td>
          <td><span class='fieldLabel'>نام خانوادگی:</span><span class='fieldValue'><?= Html::encode($model->user->usrLastName) ?></span></td>
          <td><span class='fieldLabel'>نام پدر:</span><span class='fieldValue'><?= Html::encode($model->user->usrFatherName) ?></span></td>
          <td><span class='fieldLabel'>محل تولد:</span><span class='fieldValue'><?= isset($cityModel) ? $cityModel->ctvName : '' ?></span></td>
        </tr>
        <tr>
          <td><span class='fieldLabel'>جنسیت:</span><span class='fieldValue'><?= ($model->user->usrGender == enuGender::Male ? 'آقا' : ($model->user->usrGender == enuGender::Female ? 'خانم' : '')) ?></span></td>
          <td><span class='fieldLabel'>کد ملی:</span><span class='fieldValue'><?= Yii::$app->formatter->asPersianNum($model->user->usrSSID) ?></span></td>
          <td><span class='fieldLabel'>شماره شناسنامه:</span><span class='fieldValue'><?= Yii::$app->formatter->asPersianNum($model->user->usrBirthCertID) ?></span></td>
          <td><span class='fieldLabel'>تاریخ تولد:</span><span class='fieldValue'><?= Yii::$app->formatter->asPersianNum(Yii::$app->formatter->asJalali($model->user->usrBirthDate)) ?></span></td>
        </tr>
        <!-- <tr>
          <td><span class='fieldLabel'>نام لاتین:</span><span class='fieldValue dir-ltr'><?= Html::encode($model->user->usrFirstName_en) ?></span></td>
          <td><span class='fieldLabel'>نام خانوادگی لاتین:</span><span class='fieldValue dir-ltr'><?= Html::encode($model->user->usrLastName_en) ?></span></td>
          <td><span class='fieldLabel'>نام پدر لاتین:</span><span class='fieldValue dir-ltr'><?= Html::encode($model->user->usrFatherName_en) ?></span></td>
          <td></td>
        </tr> -->
        <tr>
          <td><span class='fieldLabel'>آخرین مدرک تحصیلی:</span><span class='fieldValue'><?= enuUserEducationLevel::getLabel($model->user->usrEducationLevel) ?></span></td>
          <td><span class='fieldLabel'>رشته تحصیلی:</span><span class='fieldValue'><?= Html::encode($model->user->usrFieldOfStudy) ?></span></td>
          <td><span class='fieldLabel'>دانشگاه:</span><span class='fieldValue'><?= Html::encode($model->user->usrEducationPlace) ?></span></td>
          <td><span class='fieldLabel'>شغل اصلی:</span><span class='fieldValue'><?= Html::encode($model->mbrJob ?? '') ?></span></td>
        </tr>

        <tr>
          <td><span class='fieldLabel'>درجه هنری:</span><span class='fieldValue'><?= Yii::$app->formatter->asPersianNum($model->mbrArtDegree, 'ندارم') ?></span></td>
          <td><span class='fieldLabel'>عضویت صندوق هنر:</span><span class='fieldValue'><?= Yii::$app->formatter->asPersianNum($model->mbrHonarCreditCode, 'ندارم') ?></span></td>
          <td><span class='fieldLabel'>وضعیت نظام وظیفه:</span><span class='fieldValue'><?= enuUserMilitaryStatus::getLabel($model->user->usrMilitaryStatus) ?></span></td>
          <td><span class='fieldLabel'>وضعیت تاهل:</span><span class='fieldValue'><?= enuUserMaritalStatus::getLabel($model->user->usrMaritalStatus) ?></span></td>
        </tr>
      </table>

      <div class='row'>
        <div class='col-12 bordered'>
          <div><span class='fieldLabel'>سوابق آموزشی</span><span class='fieldValue'>(با ذکر نام اساتید و مدت دوره آموزش):</span></div>
        <div class='resize memberinfobox'><?= Yii::$app->formatter->asSoftParagraphs(Yii::$app->formatter->asPersianNum(Html::encode($model->mbrMusicEducationHistory))) ?></div>
        </div>
        <div class='col-12 bordered'>
          <div><span class='fieldLabel'>سوابق فعالیت‌های هنری</span><span class='fieldValue'>(با ذکر نام آثار مکتوب، صوتی و تصویری):</span></div>
        <div class='resize memberinfobox'><?= Yii::$app->formatter->asSoftParagraphs(Yii::$app->formatter->asPersianNum(Html::encode($model->mbrArtHistory))) ?></div>
        </div>
      </div>

      <div class='fieldLabel'>نشانی محل سکونت:</div>

      <table class='table table-borderless'>
        <tr>
          <td><span class='fieldLabel'>کشور:</span><span class='fieldValue'><?= Yii::$app->formatter->asPersianNum($model->user->country->cntrName ?? null) ?></span></td>
          <td><span class='fieldLabel'>استان:</span><span class='fieldValue'><?= Yii::$app->formatter->asPersianNum($model->user->state->sttName ?? null) ?></span></td>
          <td><span class='fieldLabel'>شهر:</span><span class='fieldValue'><?= Yii::$app->formatter->asPersianNum($model->user->cityOrVillage->ctvName ?? null) ?></span></td>
          <td><span class='fieldLabel'>منطقه / محله:</span><span class='fieldValue'><?= Yii::$app->formatter->asPersianNum($model->user->town->twnName ?? null) ?></span></td>
        </tr>
        <tr>
          <td colspan=4><span class='fieldValue'><?= Yii::$app->formatter->asPersianNum($model->user->usrHomeAddress) ?> - کد پستی: <?= Yii::$app->formatter->asPersianNum($model->user->usrZipCode) ?></span></td>
        </tr>
        <tr>
          <td><span class='fieldLabel'>تلفن همراه:</span><span class='fieldValue'><?= Yii::$app->formatter->asPhone($model->user->usrMobile) ?></span></td>
          <td><span class='fieldLabel'>ایمیل:</span><span class='fieldValue'><?= Html::encode($model->user->usrEmail) ?></span></td>
          <td colspan=2><span class='fieldLabel'>تلفن ثابت:</span><span class='fieldValue'><?= Yii::$app->formatter->asPersianNum($model->user->usrPhones) ?></span></td>
        </tr>
        <tr>
          <td colspan=4><span class='fieldLabel'>آدرس محل کار:</span><span class='fieldValue'><?= Yii::$app->formatter->asPersianNum($model->user->usrWorkAddress) ?></span></td>
        </tr>
        <tr>
          <td><span class='fieldLabel'>تلفن محل کار:</span><span class='fieldValue'><?= Yii::$app->formatter->asPersianNum($model->user->usrWorkPhones) ?></span></td>
        </tr>
      </table>

    </div>
  </div>
</div>

<div class="a4">
  <div class="a4-middle">
    <div class="a4-inner">

      <div class='fieldLabel' style='height:7.5cm'>نظر و توضیحات هیات مدیره کانون تخصصی:</div>

      <hr class='dotted'>

      <div class='row' style='height:6.5cm'>
        <div class='col'><span class='fieldLabel'>محل امضای اعضای هیات مدیره (با ذکر نام):</span></div>
        <div class='col'><span class='fieldLabel'>تاریخ بررسی:</span></div>
      </div>

      <table class='table table-bordered'>
        <tr>
          <td width='50%'>
            <span class='checked-item text-nowrap'><span class='checkbox'></span><span class='fieldLabel'>تایید</span></span>
            <span class='checked-item text-nowrap'><span class='checkbox'></span><span class='fieldLabel'>رد</span></span>
            <span class='checked-item text-nowrap'><span class='checkbox'></span><span class='fieldLabel'>آزمون</span></span>
            <span class='checked-item text-nowrap'><span class='checkbox'></span><span class='fieldLabel'>ارائه مدارک</span></span>
          </td>
          <td width='50%'>
            <span class='checked-item text-nowrap'><span class='checkbox'></span><span class='fieldLabel'>وابسته ۲</span></span>
            <span class='checked-item text-nowrap'><span class='checkbox'></span><span class='fieldLabel'>وابسته ۱</span></span>
            <span class='checked-item text-nowrap'><span class='checkbox'></span><span class='fieldLabel'>پیوسته</span></span>
          </td>
        </tr>
      </table>

      <hr class='dotted'>

      <div class='fieldLabel' style='height:7.5cm'>نظریه هیات مدیره کانون تخصصی پس از بررسی مجدد پرونده (تجدید نظر - آزمون):</div>
      <div class='left me-4cm'><span class='fieldLabel'>تاریخ بررسی:</span></div>

      <hr class='dotted'>

      <div class='row'>
        <div class='col-12'>
          <span class='fieldLabel'>اقدامات انجام شده:</span>
        </div>
        <div class='col'>
          <span class='fieldLabel'>شماره لیست بیمه:</span>
        </div>
        <div class='col'>
          <span class='fieldLabel'>تاریخ اقدام:</span>
        </div>
      </div>

    </div>
  </div>
</div>

<?php
  $js =<<<JS
var autoSizeText = function() {
  var el, elements, _i, _len, _results;
  elements = $('.resize');

  if (elements.length < 0)
    return;

  _results = [];
  for (_i = 0, _len = elements.length; _i < _len; _i++) {
    el = elements[_i];
    _results.push((function(el) {
      var resizeText, _results1;
      resizeText = function() {
        var elNewFontSize;
        elNewFontSize = (parseInt($(el).css('font-size').slice(0, -2)) - 1) + 'px';
        return $(el).css('font-size', elNewFontSize);
      };
      _results1 = [];
      while (el.scrollHeight > el.offsetHeight) {
        _results1.push(resizeText());
      }
       return _results1;
    })(el));
  }
  return _results;
};
JS;

  $this->registerJs($js);
  $this->registerJs("return autoSizeText();", View::POS_READY);
?>
