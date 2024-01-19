<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\accounting\models;

use iranhmusic\shopack\mha\common\accounting\enums\enuMhaProductType;
use Yii;
use shopack\base\backend\accounting\models\BaseBasketModel;

class BasketModel extends BaseBasketModel
{
	//override:
	protected function makeDesc($basketItem)
	{
		switch ($basketItem->saleable->product->prdMhaType)
		{
			case enuMhaProductType::Membership:
				return implode(' ', [
					$basketItem->saleable->slbName,
					'-',
					'از',
					Yii::$app->formatter->asJalali($basketItem->orderParams['startDate']),
					'تا',
					Yii::$app->formatter->asJalali($basketItem->orderParams['endDate']),
					'به مدت',
					$basketItem->qty,
					'سال'
				]);
		}

		return $basketItem->saleable->slbName;
	}

}
