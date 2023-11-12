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

$this->title = 'چاپ نامه صندوق هنر';
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

h3 {
  font-size: 18px;
  font-weight: 700;
}

h2 {
  font-size: 20px;
  font-weight: 800;
  margin: 0;
  padding: 0;
}

p {
  font-size: 17px;
  line-height: 32px;
  text-align: justify;
}

strong {
  font-weight: 800px;
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

<div class="print a5" id="dcapture">
  <h2>
    صندوق اعتباری هنر<br>
    موضوع: معرفی نامه عضویت
  </h2>
  <p>&nbsp;</p>
  <p>
    <span style="padding-right: 1cm"></span>احتراما با استناد به آیین نامه عضویت صندوق اعتباری هنر،
    <strong>
      <?php echo (empty($model->user->usrGender) ? 'هنرمند'
        : ($model->user->usrGender == enuGender::Male ? 'آقای' : 'خانم')); ?>
      <?= $model->user->usrFirstName ?>
      <?= $model->user->usrLastName ?>
    </strong>
    فرزند <strong><?= $model->user->usrFatherName ?></strong>
    با کد ملی <strong><?= Yii::$app->formatter->asPersianNum($model->user->usrSSID) ?></strong>
    متولد <strong><?= Yii::$app->formatter->asPersianNum(Yii::$app->formatter->asJalali($model->user->usrBirthDate)) ?></strong>
    و کد عضویت <strong><?= Yii::$app->formatter->asPersianNum($model->mbrRegisterCode) ?></strong>
    از اعضای کانون <strong><?= $kanoonNames ?></strong>
    خانه‌ی موسیقی ایران که شرایط عضویت در صندوق اعتباری هنر را
    به مدت <strong>۳</strong> سال
    (قابل تمدید در صورت استمرار فعالیت و ارائه اثر جدید در مدت عضویت) دارا می‌باشند؛ به حضور معرفی می‌دارد.%
  </p>
  <p>&nbsp;</p>
  <h3 style="text-align: center;padding-right: 8cm;">
    حمیدرضا نوربخش<br>
    مدیرعامل
  </h3>
</div>
