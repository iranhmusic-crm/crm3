<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\accounting\controllers;

use shopack\aaa\frontend\common\auth\BaseCrudController;
use iranhmusic\shopack\mha\frontend\common\accounting\models\CouponModel;
use iranhmusic\shopack\mha\frontend\common\accounting\models\CouponSearchModel;
// use iranhmusic\shopack\mha\common\enums\enuCouponStatus;

class CouponController extends BaseCrudController
{
	public $modelClass = CouponModel::class;
	public $searchModelClass = CouponSearchModel::class;

	// public function actionCreate_afterCreateModel(&$model)
  // {
	// 	$model->docStatus = enuDocumentStatus::Active;
  // }

}
