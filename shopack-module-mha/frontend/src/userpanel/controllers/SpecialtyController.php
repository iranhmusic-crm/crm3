<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\userpanel\controllers;

use Yii;
use yii\web\Response;
use shopack\base\common\helpers\Url;
use yii\base\InvalidConfigException;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use shopack\base\frontend\helpers\Html;
use shopack\base\common\helpers\HttpHelper;
use shopack\aaa\frontend\common\auth\BaseController;
use iranhmusic\shopack\mha\frontend\common\models\SpecialtyModel;
use iranhmusic\shopack\mha\frontend\common\models\SpecialtySearchModel;

class SpecialtyController extends BaseController
{
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

}
