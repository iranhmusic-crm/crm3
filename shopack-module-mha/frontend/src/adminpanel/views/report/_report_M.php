<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\frontend\widgets\grid\GridView;
use shopack\base\frontend\helpers\Html;
use shopack\base\common\helpers\StringHelper;
use iranhmusic\shopack\mha\common\enums\enuInsurerDocStatus;
use iranhmusic\shopack\mha\frontend\common\models\MemberModel;
?>

<?php
  $columns = [
    [
      'class' => 'kartik\grid\SerialColumn',
    ],
    'rptID',

  ];

  echo GridView::widget([
    'id' => StringHelper::generateRandomId(),
    'dataProvider' => $dataProvider,
    'columns' => $columns,
  ]);
?>
