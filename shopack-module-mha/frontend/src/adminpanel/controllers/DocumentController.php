<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\controllers;

use shopack\base\common\helpers\ArrayHelper;
use shopack\base\frontend\common\widgets\JsonTableGrid;
use shopack\aaa\frontend\common\auth\BaseCrudController;
use iranhmusic\shopack\mha\common\enums\enuBasicDefinitionType;
use iranhmusic\shopack\mha\common\enums\enuDocumentStatus;
use iranhmusic\shopack\mha\common\enums\enuKanoonStatus;
use iranhmusic\shopack\mha\frontend\common\models\BasicDefinitionModel;
use iranhmusic\shopack\mha\frontend\common\models\DocumentModel;
use iranhmusic\shopack\mha\frontend\common\models\DocumentSearchModel;
use iranhmusic\shopack\mha\frontend\common\models\KanoonModel;

class DocumentController extends BaseCrudController
{
	public $modelClass = DocumentModel::class;
	public $searchModelClass = DocumentSearchModel::class;

	public function actionCreate_afterCreateModel(&$model)
  {
		$model->docStatus = enuDocumentStatus::Active;
  }

	public function actionUpdate_afterLoadModel(&$model, $formPosted)
  {
		if ($formPosted) {
			$dirtyValues = $model->getDirtyAttributes(['docExtraParamsSchema']);

			if (empty($dirtyValues) == false) {
				$docExtraParamsSchema = [];

				foreach ($model->docExtraParamsSchema as $k => $v) {
					if (empty($v[DocumentModel::$EXPARAM_name]) == false) {
						$docExtraParamsSchema[] = $v;
					}
				}

				$model->docExtraParamsSchema = $docExtraParamsSchema;
			}
		}
  }

	public function actionParamsSchema($id, $field)
  {
		$model = $this->findModel($id);
		return $this->renderJson(JsonTableGrid::generateDynamicParamsForm($model, $field, function($orgType) {

			if (str_starts_with($orgType, 'mha:bdef:')) {
				$bdefType = substr($orgType, 9);
				return ['select', ArrayHelper::map(BasicDefinitionModel::find()
					->where(['bdfType' => $bdefType])
					->asArray()->noLimit()->all(), 'bdfID', 'bdfName')];

			} else if ($orgType == 'mha:kanoon') {
				return ['select', ArrayHelper::map(KanoonModel::find()
					->where(['knnStatus' => enuKanoonStatus::Active])
					->asArray()->noLimit()->all(), 'knnID', 'knnName')];

			} else
				return [$orgType, null];
		}));
  }

}
