<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use yii\web\JsExpression;
use borales\extensions\phoneInput\PhoneInput;
use shopack\base\common\helpers\Url;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\frontend\widgets\Select2;
use shopack\base\frontend\widgets\DepDrop;
use shopack\base\frontend\helpers\Html;
use shopack\base\frontend\widgets\ActiveForm;
use shopack\base\frontend\widgets\FormBuilder;
use shopack\aaa\common\enums\enuGender;
use shopack\aaa\frontend\common\models\UserModel;
use iranhmusic\shopack\mha\common\enums\enuSpecialtyStatus;
use iranhmusic\shopack\mha\common\enums\enuBasicDefinitionType;
?>

<div class='specialty-form'>
	<?php
		if (Yii::$app->request->isAjax) {
/*			$treeid = 'fancytree-maintree';
			if ($model->isNewRecord) {
				$modalDone = <<<JS
modalDone = function(result) {
	console.log('create', result.model);
	var tree = $.ui.fancytree.getTree('#{$treeid}');
	if (result.model.root == result.model.id) {
		//add root
		obj = {
			'key' : result.model.id,
			'title' : result.model.name,
			'nestedset' : {
				'root' : result.model.root,
				'left' : result.model.left,
				'right' : result.model.right,
				'level' : result.model.level,
			},
			'folder' : true,
			'lazy' : true,
		};
		tree.getRootNode().addChildren(obj);
	} else {
		//find parent
		var selectedNode = tree.getActiveNode();
		if (selectedNode) {
			var rootNode = tree.findFirst(node => node.key == result.model.root);
		} else {
			//Error
			console.log('create non-root Error: node not found');
		}
	}
}
JS;
			} else {
				$modalDone = <<<JS
modalDone = function(result) {
	console.log('update', result.model);
}
JS;
			}

			$this->registerJs($modalDone);
*/
			$form = ActiveForm::begin([
				'model' => $model,
				// 'modalDoneInternalScript_OK' => 'modalDone(result);',
			]);
		} else {
			$form = ActiveForm::begin([
				'model' => $model,
			]);
		}

		$builder = $form->getBuilder();

		$fildTypes = [
			'text' => 'متن',
		];
		$mhaList = enuBasicDefinitionType::getList();
		foreach($mhaList as $k => $v) {
			$fildTypes['mha:' . $k] = $v;
		}

		$builder->fields([
			['spcName'],
			['spcDescFieldType',
				'type' => FormBuilder::FIELD_WIDGET,
				'widget' => Select2::class,
				'widgetOptions' => [
					'data' => $fildTypes,
					'options' => [
						'placeholder' => Yii::t('app', '-- Choose --'),
						'dir' => 'rtl',
					],
					'pluginOptions' => [
						'allowClear' => true,
					],
				],
			],
			['spcDescFieldLabel',
				'visibleConditions' => [
					'spcDescFieldType' => ['!=', ''],
				],
			],
		]);
	?>

	<?php $builder->beginFooter(); ?>
		<div class="card-footer">
			<div>
				<?= Html::formErrorSummary($model); ?>
			</div>
			<div class="ms-auto">
				<?= Html::activeSubmitButton($model) ?>
			</div>
			<!-- <div class="clearfix"></div> -->
		</div>
	<?php $builder->endFooter(); ?>

	<?php
		$builder->render();
		$form->endForm(); //ActiveForm::end();
	?>
</div>
