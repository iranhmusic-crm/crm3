<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\accounting\controllers;

use shopack\base\frontend\adminpanel\accounting\controllers\BaseDiscountController;
use iranhmusic\shopack\mha\frontend\common\accounting\models\DiscountModel;
use iranhmusic\shopack\mha\frontend\common\accounting\models\DiscountSearchModel;
// use iranhmusic\shopack\mha\common\enums\enuDiscountStatus;

class DiscountController extends BaseDiscountController
{
	public $modelClass = DiscountModel::class;
	public $searchModelClass = DiscountSearchModel::class;

	public function init()
  {
    parent::init();
    $this->setViewPath(null);
  }

	// public function actionCreate_afterCreateModel(&$model)
  // {
	// 	$model->docStatus = enuDocumentStatus::Active;
  // }

}
