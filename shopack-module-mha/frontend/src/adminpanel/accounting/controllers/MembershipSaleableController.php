<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\accounting\controllers;

use shopack\aaa\frontend\common\auth\BaseCrudController;
use iranhmusic\shopack\mha\frontend\common\accounting\models\MembershipSaleableModel;
use iranhmusic\shopack\mha\frontend\common\accounting\models\MembershipSaleableSearchModel;
// use iranhmusic\shopack\mha\common\enums\enuMembershipStatus;

class MembershipSaleableController extends BaseCrudController
{
	public $modelClass = MembershipSaleableModel::class;
	public $searchModelClass = MembershipSaleableSearchModel::class;

	// public function getSearchParams()
  // {
  //   return Yii::$app->request->queryParams;
  // }

	public function actionCreate_afterCreateModel(&$model)
  {
		$model->slbProductID = $_GET['slbProductID'] ?? null;
  }

}
