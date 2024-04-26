<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\accounting\controllers;

use shopack\base\frontend\adminpanel\accounting\controllers\BaseDiscountSerialController;
use iranhmusic\shopack\mha\frontend\common\accounting\models\DiscountSerialModel;
use iranhmusic\shopack\mha\frontend\common\accounting\models\DiscountSerialSearchModel;
// use iranhmusic\shopack\mha\common\enums\enuDiscountSerialStatus;

class DiscountSerialController extends BaseDiscountSerialController
{
	public $modelClass = DiscountSerialModel::class;
	public $searchModelClass = DiscountSerialSearchModel::class;

	// public function init()
  // {
  //   parent::init();
  //   $this->setViewPath(null);
  // }

}
