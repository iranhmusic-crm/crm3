<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\accounting\controllers;

use iranhmusic\shopack\mha\backend\accounting\models\SaleableModel;
use shopack\base\backend\accounting\controllers\BaseAccountingController;

class DefaultController extends BaseAccountingController
{
	/**
	 * override
	 *
	 * return: status|error of every item
	 */
	protected function processVoucherItems($voucher, $items)
	{
		$result = [];

		foreach ($items as $item) {
			try {
				$ret = SaleableModel::ProcessVoucherItem(null, null, $item);

				if ($ret === true) {
					$result[$item['key']] = [
						'ok' => 1,
					];
				} //else : no new status. already processed

			} catch (\Throwable $th) {
				$result[$item['key']] = [
					'error' => $th->getMessage(),
				];
			}
		}

		return $result;
	}

}
