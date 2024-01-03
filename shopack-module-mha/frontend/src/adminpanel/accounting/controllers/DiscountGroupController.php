<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\accounting\controllers;

use shopack\base\frontend\adminpanel\accounting\controllers\BaseDiscountGroupController;
use iranhmusic\shopack\mha\frontend\common\accounting\models\DiscountGroupModel;
use iranhmusic\shopack\mha\frontend\common\accounting\models\DiscountGroupSearchModel;
// use iranhmusic\shopack\mha\common\enums\enuDiscountGroupStatus;

class DiscountGroupController extends BaseDiscountGroupController
{
	public $modelClass = DiscountGroupModel::class;
	public $searchModelClass = DiscountGroupSearchModel::class;

	// public function init()
  // {
  //   parent::init();
  //   $this->setViewPath(null);
  // }

}
