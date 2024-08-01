<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\userpanel\controllers;

use yii\web\NotFoundHttpException;
use shopack\base\common\helpers\ArrayHelper;
use shopack\base\frontend\common\widgets\JsonTableGrid;
use shopack\aaa\frontend\common\auth\BaseController;
use iranhmusic\shopack\mha\common\enums\enuKanoonStatus;
use iranhmusic\shopack\mha\frontend\common\models\DocumentModel;
use iranhmusic\shopack\mha\frontend\common\models\BasicDefinitionModel;
use iranhmusic\shopack\mha\frontend\common\models\KanoonModel;

class DocumentController extends BaseController
{
  protected function findModel($id)
	{
		if (($model = DocumentModel::findOne($id)) === null)
      throw new NotFoundHttpException('The requested item does not exist.');

    return $model;
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
