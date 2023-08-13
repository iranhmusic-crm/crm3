<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\frontend\widgets\grid\GridView;
use shopack\base\common\helpers\Url;
use shopack\base\frontend\helpers\Html;
use shopack\base\common\helpers\StringHelper;
use iranhmusic\shopack\mha\common\enums\enuMemberStatus;
use iranhmusic\shopack\mha\frontend\common\models\MemberModel;
use iranhmusic\shopack\mha\common\enums\enuBasicDefinitionType;
use iranhmusic\shopack\mha\frontend\common\models\BasicDefinitionModel;
?>

<?php
  $mbrspcMemberID = Yii::$app->request->queryParams['mbrspcMemberID'] ?? null;
?>

<?php
	// echo Alert::widget(['key' => 'shoppingcart']);

	// if (isset($statusReport))
	// 	echo (is_array($statusReport) ? Html::icon($statusReport[0], ['plugin' => 'glyph']) . ' ' . $statusReport[1] : $statusReport);

  $columns = [
    [
      'class' => 'kartik\grid\SerialColumn',
    ],
  ];

  if (empty($mbrspcMemberID)) {
    $columns = array_merge($columns, [
      [
        'class' => \iranhmusic\shopack\mha\frontend\common\widgets\grid\MemberDataColumn::class,
        'attribute' => 'mbrspcMemberID',
        'format' => 'raw',
        'value' => function ($model, $key, $index, $widget) {
          return Html::a($model->member->displayName(), ['/mha/member/view', 'id' => $model->mbrspcMemberID]); //, ['class' => ['btn', 'btn-sm', 'btn-outline-secondary']]);
        },
      ],
    ]);
  }

  $columns = array_merge($columns, [
    [
      'attribute' => 'mbrspcSpecialtyID',
      'format' => 'raw',
      'value' => function ($model, $key, $index, $widget) {
        return $model->specialty->fullName;
      },
    ],
    [
      'attribute' => 'mbrspcDesc',
      'value' => function ($model, $key, $index, $widget) {
        if (empty($model->mbrspcSpecialtyID)
          || empty($model->mbrspcDesc)
          || empty($model->specialty->spcDescFieldType)
        )
          return null;

        $desc = $model->mbrspcDesc['desc'];
        $fieldType = $model->specialty->spcDescFieldType;
        if ($fieldType == 'text')
          return $desc;

        if (str_starts_with($fieldType, 'mha:')) {
          $bdf = substr($fieldType, 4);

          $basicDefinitionModel = BasicDefinitionModel::find()
            ->andWhere(['bdfID' => $desc])
            // ->andWhere(['bdfType' => $bdf])
            ->one()
          ;

          if ($basicDefinitionModel)
            return enuBasicDefinitionType::getLabel($bdf) . ': ' . $basicDefinitionModel->bdfName;

          return enuBasicDefinitionType::getLabel($bdf) . ': ' . $desc;
        }

        // $mhaList = enuBasicDefinitionType::getList();
        // foreach($mhaList as $k => $v) {
        //   if ($fieldType == 'mha:' . $k) {
        //     return $v . ': ' . $desc;
        //   }
        // }

        return $desc;
      },
    ],
    [
      'class' => \shopack\base\frontend\widgets\ActionColumn::class,
      'header' => MemberModel::canCreate() ? Html::createButton(null, [
        'create',
        'mbrspcMemberID' => $mbrspcMemberID ?? $_GET['mbrspcMemberID'] ?? null,
      ]) : Yii::t('app', 'Actions'),
      'template' => '{update} {delete}{undelete}',
      'visibleButtons' => [
        'update' => function ($model, $key, $index) {
          return $model->canUpdate();
        },
        'delete' => function ($model, $key, $index) {
          return $model->canDelete();
        },
        'undelete' => function ($model, $key, $index) {
          return $model->canUndelete();
        },
      ],
    ]
  ]);

  echo GridView::widget([
    'id' => StringHelper::generateRandomId(),
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $columns,
  ]);
?>
