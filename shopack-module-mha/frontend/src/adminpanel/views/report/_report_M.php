<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\common\helpers\ArrayHelper;
use shopack\base\common\helpers\Json;
use shopack\base\common\helpers\StringHelper;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\frontend\common\widgets\grid\GridView;
use shopack\aaa\common\enums\enuGender;
use shopack\aaa\common\enums\enuUserStatus;
use iranhmusic\shopack\mha\frontend\common\models\MemberModel;
use iranhmusic\shopack\mha\frontend\common\models\MemberKanoonModel;
use iranhmusic\shopack\mha\frontend\common\models\KanoonModel;
use iranhmusic\shopack\mha\common\enums\enuKanoonMembershipDegree;
use iranhmusic\shopack\mha\common\enums\enuBasicDefinitionType;
use iranhmusic\shopack\mha\frontend\common\models\BasicDefinitionModel;
?>

<div>
<?php
  if ($dataProvider->getCount() <= 0) {
    echo Html::div('این گزارش خالی است.', ['class' => ['text-center']]);
  } else {
    $columns = [
      [
        'class' => 'kartik\grid\SerialColumn',
      ],
    ];

    $outputFieldsSchema = $model->outputFields();

    if (ArrayHelper::isIndexed($model->rptOutputFields))
      $rptOutputFields = $model->rptOutputFields;
    else
      $rptOutputFields = array_keys($model->rptOutputFields);

    foreach ($rptOutputFields as $field) {
      if (array_key_exists($field, $outputFieldsSchema) == false)
        continue;

      $outputSchema = $outputFieldsSchema[$field];

      $column = [
        'attribute' => $field,
      ];

      if (is_array($outputSchema)) {
        if (isset($outputSchema['export']))
          unset($outputSchema['export']);

        // $template = ArrayHelper::remove($outputSchema, 'template', null);
        $column = array_merge($column, $outputSchema);
        // if (empty($template) == false) {
        //   $column['value'] = function($model) use ($template) {
        //     return strtr($template, '{value}', formatted value)
        //   };
        // }
      } else {
        $column['label'] = $outputSchema;
      }

      $columns[] = $column;
    }

    // foreach ($model->outputFields() as $k => $v) {
    //   // if (array_key_exists($k, $dataProvider->allModels[0]) == false)
    //   if (array_key_exists($k, $model->rptOutputFields) == false)
    //     continue;

    //   $column = [
    //     'attribute' => $k,
    //   ];

    //   if (is_array($v)) {
    //     if (isset($v['export']))
    //       unset($v['export']);

    //     // $template = ArrayHelper::remove($v, 'template', null);
    //     $column = array_merge($column, $v);
    //     // if (empty($template) == false) {
    //     //   $column['value'] = function($model) use ($template) {
    //     //     return strtr($template, '{value}', formatted value)
    //     //   };
    //     // }
    //   } else {
    //     $column['label'] = $v;
    //   }

    //   $columns[] = $column;
    // }

    echo GridView::widget([
      'id' => StringHelper::generateRandomId(),
      'dataProvider' => $dataProvider,
      'columns' => $columns,
    ]);
  }
?>
</div>
