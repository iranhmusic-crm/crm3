<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\accounting\controllers;

use shopack\base\frontend\adminpanel\accounting\controllers\BaseDiscountUsageController;
use iranhmusic\shopack\mha\frontend\common\accounting\models\DiscountUsageModel;
use iranhmusic\shopack\mha\frontend\common\accounting\models\DiscountUsageSearchModel;
// use iranhmusic\shopack\mha\common\enums\enuDiscountUsageStatus;

class DiscountUsageController extends BaseDiscountUsageController
{
	public $modelClass = DiscountUsageModel::class;
	public $searchModelClass = DiscountUsageSearchModel::class;

	// public function init()
  // {
  //   parent::init();
  //   $this->setViewPath(null);
  // }

}
