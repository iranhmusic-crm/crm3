<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\accounting\controllers;

use shopack\base\frontend\adminpanel\accounting\controllers\BaseProductController;
use iranhmusic\shopack\mha\frontend\common\accounting\models\ProductModel;
use iranhmusic\shopack\mha\frontend\common\accounting\models\ProductSearchModel;
// use iranhmusic\shopack\mha\common\enums\enuProductStatus;

class ProductController extends BaseProductController
{
	public $modelClass = ProductModel::class;
	public $searchModelClass = ProductSearchModel::class;

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
