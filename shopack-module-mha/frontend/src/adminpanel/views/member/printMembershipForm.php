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
}

body {
  display: table;
  width: 100%;
  /* height: 100%; */
}

.container {
  display: table-cell;
}

.print {
  margin: auto;
  padding-left: 1cm;
  padding-right: 1cm;
  padding-top: 5.3cm;
}

.a4 {
  width: 19cm;
  max-height: 27.7cm;
}

.a5 {
  width: 14.8cm;
  max-height: 19cm;
}

h1 {
  float: right;
  font-size: 12px;
  font-weight: normal;
  margin: 0;
  padding: 0;
  padding-left: 2px;
}

h2 {
  font-size: 20px;
  font-weight: 800;
  margin: 0;
  padding: 0;
}

h3 {
  font-size: 18px;
  font-weight: 700;
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
foreach ($mbrkanoons as $mbrkanoon) {
  $kanoonNames[] = $mbrkanoon->kanoon->knnName;
}
$kanoonNames = implode(' - ' , $kanoonNames);
?>

<div class="print a4" id="dcapture">
  <h2>پرسش نامه عضویت</h2>
  <p>&nbsp;</p>

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

</div>
