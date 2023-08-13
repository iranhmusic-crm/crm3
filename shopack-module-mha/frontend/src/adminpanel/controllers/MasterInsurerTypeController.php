<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\controllers;

use Yii;
use yii\web\Response;
use shopack\base\common\helpers\Url;
use shopack\base\common\helpers\StringHelper;
use shopack\aaa\frontend\common\auth\BaseCrudController;
use iranhmusic\shopack\mha\frontend\common\models\MasterInsurerTypeModel;
use iranhmusic\shopack\mha\frontend\common\models\MasterInsurerTypeSearchModel;
use iranhmusic\shopack\mha\common\enums\enuInsurerStatus;

class MasterInsurerTypeController extends BaseCrudController
{
	public $modelClass = MasterInsurerTypeModel::class;
	public $searchModelClass = MasterInsurerTypeSearchModel::class;
	public $modalDoneFragment = 'master-insurer-type';

	public function init()
	{
		$this->doneLink = function ($model) {
			return Url::to(['/mha/master-insurer/view',
				'id' => $model->minstypMasterInsurerID,
				'fragment' => $this->modalDoneFragment,
				'anchor' => StringHelper::convertToJsVarName($model->primaryKeyValue()),
			]);
		};

		parent::init();
	}

  public function actionCreate_afterCreateModel(&$model)
  {
		$model->minstypMasterInsurerID = $_GET['minstypMasterInsurerID'] ?? null;
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
    $query = MasterInsurerTypeModel::find()
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
          'id' => $model->minstypID,
          'name' => $model->masterInsurer->minsName . ' - ' . $model->minstypName,
        ];
			}
    }

    $out['items'] = $list;

    return $this->renderJson($out);
  }

}
