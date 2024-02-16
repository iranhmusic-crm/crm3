<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\accounting\controllers;

use iranhmusic\shopack\mha\backend\accounting\models\SaleableModel;
use shopack\base\backend\accounting\controllers\BaseAccountingController;

class DefaultController extends BaseAccountingController
{
	//override
	protected function processVoucherItems($voucher, $items)
	{
		foreach ($items as $item) {
			SaleableModel::ProcessVoucherItem(null, null, $item);
		}
	}

}
