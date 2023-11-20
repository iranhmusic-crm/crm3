<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\controllers;

use Yii;
use yii\web\Response;
use shopack\base\common\helpers\ArrayHelper;
use shopack\base\frontend\common\helpers\Html;
use shopack\aaa\frontend\common\auth\BaseCrudController;
use iranhmusic\shopack\mha\common\enums\enuKanoonStatus;
use iranhmusic\shopack\mha\common\enums\enuBasicDefinitionType;
use iranhmusic\shopack\mha\frontend\common\models\KanoonModel;
use iranhmusic\shopack\mha\frontend\common\models\KanoonSearchModel;
use iranhmusic\shopack\mha\frontend\common\models\BasicDefinitionModel;
use iranhmusic\shopack\mha\frontend\adminpanel\models\KanoonSendMessageForm;

class KanoonController extends BaseCrudController
{
	public $modelClass = KanoonModel::class;
	public $searchModelClass = KanoonSearchModel::class;

	public function actionSelect2List(
    $q=null,
    // $id=null,
    $page=0,
    $perPage=20
  ) {
    Yii::$app->response->format = Response::FORMAT_JSON;

    $out['total_count'] = 0;
		$out['items'] = [['id' => '', 'title' => '']];

		if (empty($q))
			return $this->renderJson($out);

    //count
    $query = KanoonModel::find()
      ->addUrlParameter('q', $q);

    $out['total_count'] = $count = $query->count();
    if ($count == 0)
      return $this->renderJson($out);

    //items
    $query->limit($perPage);
    $query->offset($page * $perPage);
    $models = $query->all();

		$list = [];
    if (empty($models) == false) {
			foreach ($models as $model) {
        $list[] = [
          'id' => $model->knnID,
          'title' => $model->knnName,
        ];
			}
    }

    $out['items'] = $list;

    return $this->renderJson($out);
  }

  public function actionSendMessage($id = null)
  {
    $model = new KanoonSendMessageForm;
    $model->kanoonID = $id;

		$formPosted = $model->load(Yii::$app->request->post());
		$done = false;
		if ($formPosted)
			$done = $model->process();

    if (Yii::$app->request->isAjax) {
      if ($done) {
        return $this->renderJson([
          'message' => Yii::t('app', 'Success'),
          // 'id' => $id,
          // 'redirect' => $this->doneLink ? call_user_func($this->doneLink, $model) : null,
          // 'modalDoneFragment' => $this->modalDoneFragment,
        ]);
      }

      if ($formPosted) {
        return $this->renderJson([
          'status' => 'Error',
          'message' => Yii::t('app', 'Error'),
          // 'id' => $id,
          'error' => Html::errorSummary($model),
        ]);
      }

      return $this->renderAjaxModal('_form_sendMessage', [
        'model' => $model,
      ]);
    }

    if ($done) {
      if (empty($id))
        return $this->redirect(['index']);

      return $this->redirect(['view', 'id' => $id]);
    }

    return $this->render('sendMessage', [
      'model' => $model,
    ]);
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
            ->noLimit()
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
