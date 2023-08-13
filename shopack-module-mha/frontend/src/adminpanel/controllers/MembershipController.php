<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\controllers;

use shopack\aaa\frontend\common\auth\BaseCrudController;
use iranhmusic\shopack\mha\frontend\common\models\MembershipModel;
use iranhmusic\shopack\mha\frontend\common\models\MembershipSearchModel;
use iranhmusic\shopack\mha\common\enums\enuMembershipStatus;

class MembershipController extends BaseCrudController
{
	public $modelClass = MembershipModel::class;
	public $searchModelClass = MembershipSearchModel::class;

	public function actionCreate_afterCreateModel(&$model)
  {
		$model->mshpStatus = enuMembershipStatus::Active;
  }

}
