<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\accounting\controllers;

use shopack\aaa\frontend\common\auth\BaseCrudController;
use iranhmusic\shopack\mha\frontend\common\accounting\models\MembershipProductModel;
use iranhmusic\shopack\mha\frontend\common\accounting\models\MembershipProductSearchModel;
// use iranhmusic\shopack\mha\common\enums\enuMembershipStatus;

class MembershipProductController extends BaseCrudController
{
	public $modelClass = MembershipProductModel::class;
	public $searchModelClass = MembershipProductSearchModel::class;

	public function actionCreate_afterCreateModel(&$model)
  {
		$model->prdUnitID = 1; //unit:Year
  }

}
