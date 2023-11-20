<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\accounting\controllers;

use shopack\base\frontend\adminpanel\accounting\controllers\BaseSaleableController;
use iranhmusic\shopack\mha\frontend\common\accounting\models\SaleableModel;
use iranhmusic\shopack\mha\frontend\common\accounting\models\SaleableSearchModel;
// use iranhmusic\shopack\mha\common\enums\enuSaleableStatus;

class SaleableController extends BaseSaleableController
{
	public $modelClass = SaleableModel::class;
	public $searchModelClass = SaleableSearchModel::class;

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
