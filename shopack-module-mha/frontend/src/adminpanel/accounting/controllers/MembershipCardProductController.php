<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\accounting\controllers;

use shopack\aaa\frontend\common\auth\BaseCrudController;
use shopack\base\common\accounting\enums\enuProductType;
use iranhmusic\shopack\mha\frontend\common\accounting\models\MembershipCardProductModel;
use iranhmusic\shopack\mha\frontend\common\accounting\models\MembershipCardProductSearchModel;
// use iranhmusic\shopack\mha\common\enums\enuMembershipCardStatus;

class MembershipCardProductController extends BaseCrudController
{
	public $modelClass = MembershipCardProductModel::class;
	public $searchModelClass = MembershipCardProductSearchModel::class;

	public function actionCreate_afterCreateModel(&$model)
  {
		$model->prdType = enuProductType::Physical;
		$model->prdUnitID = 2; //unit:Times
  }

}
