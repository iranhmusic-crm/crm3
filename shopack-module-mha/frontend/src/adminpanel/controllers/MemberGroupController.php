<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\controllers;

use Yii;
use yii\web\Response;
use shopack\base\common\helpers\HttpHelper;
use shopack\aaa\frontend\common\auth\BaseCrudController;
use iranhmusic\shopack\mha\frontend\common\models\MemberGroupModel;
use iranhmusic\shopack\mha\frontend\common\models\MemberGroupSearchModel;

class MemberGroupController extends BaseCrudController
{
	public $modelClass = MemberGroupModel::class;
	public $searchModelClass = MemberGroupSearchModel::class;


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
    $query = MemberGroupModel::find()
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
          'id' => $model->mgpID,
          'title' => $model->mgpName,
        ];
			}
    }

    $out['items'] = $list;

    return $this->renderJson($out);
  }

}
