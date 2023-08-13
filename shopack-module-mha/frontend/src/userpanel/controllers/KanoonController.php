<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\userpanel\controllers;

use yii\web\NotFoundHttpException;
use shopack\base\common\helpers\ArrayHelper;
use shopack\aaa\frontend\common\auth\BaseController;
use iranhmusic\shopack\mha\frontend\common\models\KanoonModel;
use iranhmusic\shopack\mha\frontend\common\models\KanoonSearchModel;
use iranhmusic\shopack\mha\common\enums\enuKanoonStatus;
use iranhmusic\shopack\mha\common\enums\enuBasicDefinitionType;
use iranhmusic\shopack\mha\frontend\common\models\BasicDefinitionModel;

class KanoonController extends BaseController
{
  protected function findModel($id)
	{
		if (($model = KanoonModel::findOne($id)) === null)
      throw new NotFoundHttpException('The requested item not exist.');

    return $model;
	}

	public function actionParamsSchema($id)
  {
		$model = $this->findModel($id);
    if (empty($model->knnDescFieldType) == false) {
      if ($model->knnDescFieldType == 'text') {
        return $this->renderJson([
          'count' => 1,
          'list' => [
            [
              'id' => 'desc',
              'label' => $model->knnDescFieldLabel ?? 'متن',
              'mandatory' => 1,
              'type' => 'string',
            ],
          ],
        ]);
      }

      $mhaList = enuBasicDefinitionType::getList();
      foreach($mhaList as $k => $v) {
        if ($model->knnDescFieldType == 'mha:' . $k) {

          $definitionModels = BasicDefinitionModel::find()
            ->andWhere(['bdfType' => $k])
            ->asArray()
            ->all();

          return $this->renderJson([
            'count' => 1,
            'list' => [
              [
                'id' => 'desc',
                'label' => $model->knnDescFieldLabel ?? $v,
                'mandatory' => 1,
                'type' => 'combo',
                'data' => ArrayHelper::map($definitionModels, 'bdfID', 'bdfName'),
                // "default":"%"
              ],
            ],
          ]);
        }
      }
    }

    return $this->renderJson(['count' => 0, 'list' => []]);
  }

}
