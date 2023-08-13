<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\userpanel\controllers;

use Yii;
use yii\web\Response;
use shopack\base\common\helpers\Url;
use shopack\base\common\helpers\StringHelper;
use shopack\base\common\helpers\HttpHelper;
use shopack\aaa\frontend\common\auth\BaseCrudController;
use iranhmusic\shopack\mha\frontend\common\models\MemberSpecialtyModel;
use iranhmusic\shopack\mha\frontend\common\models\MemberSpecialtySearchModel;

class MemberSpecialtyController extends BaseCrudController
{
	public $modelClass = MemberSpecialtyModel::class;
	public $searchModelClass = MemberSpecialtySearchModel::class;
	// public $modalDoneFragment = 'member-specialty';

	public function init()
	{
		$this->doneLink = function ($model) {
			return Url::to(['index']);
		};

		parent::init();
	}

  public function actionCreate_afterCreateModel(&$model)
  {
		$model->mbrspcMemberID = Yii::$app->user->id;
  }

	public function getSearchParams()
  {
    return array_replace_recursive(Yii::$app->request->queryParams, [
			'mbrspcMemberID' => Yii::$app->user->id,
		]);
  }

}
