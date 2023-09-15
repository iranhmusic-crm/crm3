<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\common\helpers\Json;
use shopack\base\common\helpers\Url;
use shopack\base\frontend\common\widgets\grid\GridView;
use shopack\base\frontend\common\helpers\Html;
use iranhmusic\shopack\mha\common\enums\enuSpecialtyStatus;
use iranhmusic\shopack\mha\frontend\common\models\SpecialtyModel;
use iranhmusic\shopack\mha\common\enums\enuBasicDefinitionType;

$this->title = Yii::t('mha', 'Specialties');
$this->params['breadcrumbs'][] = Yii::t('mha', 'Music House');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="specialty-index w-100 h-min-100">
  <div class='card border-default h-min-100'>
    <div class='card-body h-min-100'>
      <div class='row h-min-100'>
        <div class='col h-min-100'>
          <div class='card card-default h-min-100'>
            <div class='card-header'>
              <?= Yii::t('mha', 'Specialties') ?>
            </div>
            <div class='card-body p-0 scrollable'>
              <?php
                $createTitle = Yii::t('app', 'Create');
                $createUrl = Url::to(['create']);

                $updateTitle = Yii::t('app', 'Update');
                $updateUrl = Url::to(['update']);

                $deleteUrl = Url::to(['delete']);

                $treeid = 'fancytree-maintree';
                $js =<<<JS
async function selectFancyTreeNode(key, root, left, right, level) {
  // console.log('findFancyTreeNode', {key, root, left, right, level});

  var tree = $.ui.fancytree.getTree('#{$treeid}');
  if (!tree)
    return null;

  var parentNode = tree.rootNode;
  var lastLeft = 0;
  for (lv = 0; lv <= level; lv++) {
    var foundNode = null;

    for (i = 0, ln = parentNode.children.length; i < ln; i++) {
      var node = parentNode.children[i];
      // console.log('node.data.nestedset', node);//, node.data, node.data.nestedset);
      if ( (node.data.nestedset.root == root)
        && (node.data.nestedset.left <= left)
        && (node.data.nestedset.right >= left)
        && (node.data.nestedset.left > lastLeft)
        && (node.data.nestedset.level == lv)
      ) {
        foundNode = node;
        break;
      }
    }

    if (!foundNode)
      return;

    if (foundNode.key == key) {
      foundNode.setActive();
      return;
    }

    await foundNode.setExpanded();
    // if (foundNode.lazy && (foundNode.isLoaded() == false)) {
    //   await foundNode.load();
    // }

    lastLeft = foundNode.data.nestedset.left;
    parentNode = foundNode;
  }
}

function createItem() {
  var tree = $.ui.fancytree.getTree('#{$treeid}');
  if (!tree)
    return null;

  var node = tree.getActiveNode();
  if (!node) {
    krajeeDialog.alert('برای ایجاد زیر مجموعه، ابتدا یک آیتم را انتخاب کنید');
    return;
  }

  doShowModal('{$createTitle}', '{$createUrl}?' + 'parentid=' + node.key);
}

function updateItem() {
  var tree = $.ui.fancytree.getTree('#{$treeid}');
  if (!tree)
    return null;

  var node = tree.getActiveNode();
  if (!node) {
    krajeeDialog.alert('برای ویرایش یک آیتم، ابتدا باید آن را انتخاب کنید');
    return;
  }

  doShowModal('{$updateTitle}', '{$updateUrl}?' + 'id=' + node.key);
}
JS;
                $this->registerJs($js, \yii\web\View::POS_END);

                $models = $dataProvider->getModels();
                $list = [];
                $selectedNode = null;
                $selectedNodeID = $_GET['selid'] ?? null;
                if (empty($models) == false) {
                  foreach ($models as $model) {
                    $nodeData = [
                      'key' => $model->spcID,
                      'title' => $model->spcName,
                      'nestedset' => [
                        'root' => $model->spcRoot,
                        'left' => $model->spcLeft,
                        'right' => $model->spcRight,
                        'level' => $model->spcLevel,
                      ],
                    ];

                    $list[] = array_merge($nodeData, [
                      'folder' => true,
                      'lazy' => true,
                    ]);

                    if ($selectedNodeID && ($selectedNodeID == $model->spcID)) {
                      $selectedNode = $nodeData;
                    }
                  }
                }

                if ($selectedNodeID && ($selectedNode == null)) {
                  try {
                    $selNodeModel = SpecialtyModel::findOne($selectedNodeID);
                    if ($selNodeModel) {
                      $selectedNode = [
                        'key' => $selNodeModel->spcID,
                        'title' => $selNodeModel->spcName,
                        'nestedset' => [
                          'root' => $selNodeModel->spcRoot,
                          'left' => $selNodeModel->spcLeft,
                          'right' => $selNodeModel->spcRight,
                          'level' => $selNodeModel->spcLevel,
                        ],
                      ];
                    }
                  } catch(\Throwable $exp) { }
                }

                $fildTypes = [
                  'text' => 'متن',
                ];
                $mhaList = enuBasicDefinitionType::getList();
                foreach($mhaList as $k => $v) {
                  $fildTypes['mha:' . $k] = $v;
                }
                $fildTypes = Json::encode($fildTypes);

                echo \shopack\base\frontend\common\widgets\fancytree\Fancytree::widget([
                  'id' => 'maintree', //$treeid
                  'data' => $list,
                  // 'activeNode' => $_GET['selid'] ?? null,
                  'url' => Url::to(['get-items']),
                  'options' => [
                    // 'autoScroll' => true,
                    'rtl' => true,
                    'activate' => new \yii\web\JsExpression(<<<JS
function(event, data) {
	// console.log('activate');
	// console.log(event, data);
  $('#info-area-key').html(data.node.key);
  $('#info-area-title').html(data.node.title);

  fildTypes = {$fildTypes};

  if (data.node.data.DescFieldType) {
    $('#info-area-descfieldtype').html(fildTypes[data.node.data.DescFieldType]);
    $('#info-area-descfieldlabel').html(data.node.data.DescFieldLabel);
  } else {
    $('#info-area-descfieldtype').html('');
    $('#info-area-descfieldlabel').html('');
  }
  $('#info-area').show();

  $('#create-button').attr('disabled', null);

  // $('#update-button').attr('href', '{$updateUrl}?id=' + data.node.key);
  $('#update-button').attr('disabled', null);

  $('#delete-button').attr('href', '{$deleteUrl}?id=' + data.node.key);
  $('#delete-button').removeClass('disabled');
}
JS
                    )
                  ],
                  'classes' => ['h-min-100'],
                ]);

                if ($selectedNode) {
/*
var selectedNode = findFancyTreeNode(
  {$selectedNode['key']},
  {$selectedNode['nestedset']['root']},
  {$selectedNode['nestedset']['left']},
  {$selectedNode['nestedset']['right']},
  {$selectedNode['nestedset']['level']}
);
if (selectedNode)
  selectedNode.setActive();
*/
                  $js =<<<JS
selectFancyTreeNode(
  {$selectedNode['key']},
  {$selectedNode['nestedset']['root']},
  {$selectedNode['nestedset']['left']},
  {$selectedNode['nestedset']['right']},
  {$selectedNode['nestedset']['level']}
);
JS;
                  $this->registerJs($js, \yii\web\View::POS_READY);
                }
              ?>
            </div>
            <div class='card-footer'>
              <div class='btn-toolbar' role='toolbar'>
                <div class='btn-group btn-group-sm me-auto' role='group'>

                  <?= Html::createButton("<i class='indicator fas fa-tree'></i>", null, [
                    'class' => ['btn', 'btn-sm', 'btn-outline-primary'],
                    'id' => 'create-root-button',
                    'title' => 'ایجاد سرشاخه',
                  ]) ?>

                  <button id='create-button' type='button' class='btn btn-outline-success' title='ایجاد' onclick="createItem()" disabled='disabled'><i class="indicator fas fa-plus"></i></button>

                  <button id='update-button' type='button' class='btn btn-outline-success' title='ویرایش' onclick="updateItem()" disabled='disabled'><i class="indicator fas fa-pen"></i></button>

                  <?= Html::deleteButton('<i class="indicator fas fa-trash"></i>', null, [
                    'class' => ['btn', 'btn-sm', 'btn-outline-danger'],
                    'id' => 'delete-button',
                    'disabled' => true,
                  ]) ?>
                </div>
                &nbsp;
                <div class='btn-group btn-group-sm' role='group'>
                  <button type='button' class='btn btn-outline-primary' title='بالاتر'><i class="indicator fas fa-angle-double-up"></i></button>
                  <button type='button' class='btn btn-outline-primary' title='پایین تر'><i class="indicator fas fa-angle-double-down"></i></button>
                  <button type='button' class='btn btn-outline-primary' title='راست تر'><i class="indicator fas fa-angle-double-right"></i></button>
                  <button type='button' class='btn btn-outline-primary' title='چپ تر'><i class="indicator fas fa-angle-double-left"></i></button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class='col-9'>
          <div id='info-area' style='display: none'>
            <table class='table table-bordered table-striped'>
              <tr>
                <td width='30%'>کد:</td>
                <td id='info-area-key'></td>
              </tr>
              <tr>
                <td>عنوان:</td>
                <td id='info-area-title'></td>
              </tr>
              <tr>
                <td>نوع فیلد توضیحات:</td>
                <td id='info-area-descfieldtype'></td>
              </tr>
              <tr>
                <td>عنوان فیلد توضیحات:</td>
                <td id='info-area-descfieldlabel'></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
