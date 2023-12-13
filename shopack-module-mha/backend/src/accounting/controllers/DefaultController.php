<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\accounting\controllers;

use shopack\base\backend\accounting\controllers\BaseAccountingController;

class DefaultController extends BaseAccountingController
{
	public $basketModelClass = \iranhmusic\shopack\mha\backend\accounting\models\BasketModel::class;
}
