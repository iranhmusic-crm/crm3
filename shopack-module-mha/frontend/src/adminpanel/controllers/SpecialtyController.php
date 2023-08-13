<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\controllers;

use Yii;
use yii\web\Response;
use yii\base\InvalidConfigException;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use shopack\base\common\helpers\Url;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\common\helpers\ArrayHelper;
use shopack\base\frontend\helpers\Html;
use shopack\aaa\frontend\common\auth\BaseController;
use iranhmusic\shopack\mha\frontend\common\models\SpecialtyModel;
use iranhmusic\shopack\mha\frontend\common\models\SpecialtySearchModel;
use iranhmusic\shopack\mha\common\enums\enuBasicDefinitionType;
use iranhmusic\shopack\mha\frontend\common\models\BasicDefinitionModel;


class SpecialtyController extends BaseController
{
	protected function findModel($id)
	{
		if (($model = SpecialtyModel::findOne($id)) === null)
      throw new NotFoundHttpException('The requested item not exist.');

    return $model;
	}

  public function actionIndex()
  {
    $searchModel = new SpecialtySearchModel();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    $dataProvider->pagination = false;

    return $this->render('index', [
			'searchModel' => $searchModel,
      'dataProvider' => $dataProvider,
      // 'resultStatus' => $resultStatus,
      // 'resultData' => $resultData,
      // 'message' => $messageText,
    ]);
  }

  // public function actionView($id)
  // {
	// 	$model = $this->findModel($id);

  //   return $this->render('view', [
  //     'model' => $model,
  //   ]);
  // }

  public function actionCreate($parentid = null)
  {
    $model = new SpecialtyModel;
    $model->parentid = $parentid;

		$formPosted = $model->load(Yii::$app->request->post());
		$done = false;
		if ($formPosted)
			$done = $model->save();

    if (Yii::$app->request->isAjax) {
      if ($done) {
        return $this->renderJson([
          'message' => Yii::t('app', 'Success'),
          // 'id' => $id,
          'redirect' => Url::to(['index', 'selid' => $model->primaryKeyValue()]),
          // 'model' => [
          //   'id' => $model->primaryKeyValue(),
          //   'name' => $model->spcName,
          //   'root' => $model->spcRoot,
          //   'left' => $model->spcLeft,
          //   'right' => $model->spcRight,
          //   'level' => $model->spcLevel,
          // ],
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

      return $this->renderAjaxModal('_form', [
        'model' => $model,
      ]);
    }

    if ($done)
      return $this->redirect(['index', 'selid' => $model->primaryKeyValue()]);

    return $this->render('create', [
      'model' => $model,
    ]);
  }

  public function actionUpdate($id)
  {
		$model = $this->findModel($id);

		$formPosted = $model->load(Yii::$app->request->post());
		$done = false;
		if ($formPosted)
			$done = $model->save();

    if (Yii::$app->request->isAjax) {
      if ($done) {
        return $this->renderJson([
          'message' => Yii::t('app', 'Success'),
          // 'id' => $id,
          'redirect' => Url::to(['index', 'selid' => $model->primaryKeyValue()]),
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

      return $this->renderAjaxModal('_form', [
        'model' => $model,
      ]);
    }

    if ($done)
      return $this->redirect(['index', 'selid' => $model->primaryKeyValue()]);

    return $this->render('update', [
      'model' => $model
    ]);
  }

  public function actionDelete($id)
  {
    if (empty($_POST['confirmed']))
      throw new BadRequestHttpException('دستور حذف باید تایید شده باشد');

		$model = $this->findModel($id);

    if ($model->delete() === false)
      return $this->redirect(['index', 'selid' => $model->primaryKeyValue()]);

    return $this->redirect(['index']);
  }

  public function actionUndelete($id)
  {
    if (empty($_POST['confirmed']))
      throw new BadRequestHttpException('دستور بازگردانی باید تایید شده باشد');

		$model = $this->findModel($id);

    if ($model->undelete() === false)
      return $this->redirect(['view', 'id' => $model->primaryKeyValue()]);

    return $this->redirect(['index']);
  }

  public function actionGetItems($id = null)
  {
    Yii::$app->response->format = Response::FORMAT_JSON;

    $result = HttpHelper::callApi('mha/specialty', HttpHelper::METHOD_GET, [
      'parentid' => $id,
    ]);

    //-----------------
    $list = [];

    if ($result[0] == 200) {
      foreach ($result[1]['data'] as $v) {
        $spcDescFieldTypeName = 'ندارد';
        if (empty($v['spcDescFieldType']) == false ) {
          if (isset($fildTypes[$v['spcDescFieldType']]))
            $spcDescFieldTypeName = $fildTypes[$v['spcDescFieldType']];
          else
            $spcDescFieldTypeName = $v['spcDescFieldType'];
        }

        $left = $v['spcLeft'];
        $right = $v['spcRight'];
        $isLeaf = ($right == $left + 1);

        $list[] = [
          'key' => $v['spcID'],
          'title' => $v['spcName'],
          'nestedset' => [
            'root' => $v['spcRoot'],
            'left' => $left,
            'right' => $right,
            'level' => $v['spcLevel'],
          ],
          'DescFieldType' => $v['spcDescFieldType'],
          'DescFieldLabel' => $v['spcDescFieldLabel'],
          'folder' => !$isLeaf,
          'lazy' => !$isLeaf,
        ];
      }
    }

    return $list;
  }

  public function actionSelect2List(
    $q=null,
    // $id=null,
    $page=0,
    $perPage=20
  ) {
    Yii::$app->response->format = Response::FORMAT_JSON;

    $out['total_count'] = 0;
		$out['items'] = [['id' => '', 'name' => '']];

		if (empty($q))
			return $this->renderJson($out);

    //count
    $query = SpecialtyModel::find()
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
          'id' => $model->spcID,
          'name' => $model->fullName,
        ];
			}
    }

    $out['items'] = $list;

    return $this->renderJson($out);
  }

  public function actionParamsSchema($id)
  {
		$model = $this->findModel($id);
    if (empty($model->spcDescFieldType) == false) {
      if ($model->spcDescFieldType == 'text') {
        return $this->renderJson([
          'count' => 1,
          'list' => [
            [
              'id' => 'desc',
              'label' => $model->spcDescFieldLabel ?? 'متن',
              'mandatory' => 1,
              'type' => 'string',
            ],
          ],
        ]);
      }

      $mhaList = enuBasicDefinitionType::getList();
      foreach($mhaList as $k => $v) {
        if ($model->spcDescFieldType == 'mha:' . $k) {

          $definitionModels = BasicDefinitionModel::find()
            ->andWhere(['bdfType' => $k])
            ->asArray()
            ->all();

          return $this->renderJson([
            'count' => 1,
            'list' => [
              [
                'id' => 'desc',
                'label' => $model->spcDescFieldLabel ?? $v,
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
