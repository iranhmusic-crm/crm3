<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\accounting\controllers;

use shopack\base\frontend\adminpanel\accounting\controllers\BaseCouponController;
use iranhmusic\shopack\mha\frontend\common\accounting\models\CouponModel;
use iranhmusic\shopack\mha\frontend\common\accounting\models\CouponSearchModel;
// use iranhmusic\shopack\mha\common\enums\enuCouponStatus;

class CouponController extends BaseCouponController
{
	public $modelClass = CouponModel::class;
	public $searchModelClass = CouponSearchModel::class;

	// public function actionCreate_afterCreateModel(&$model)
  // {
	// 	$model->docStatus = enuDocumentStatus::Active;
  // }

}
