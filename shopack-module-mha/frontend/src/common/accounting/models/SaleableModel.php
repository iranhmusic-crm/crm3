<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\accounting\models;

use Yii;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\common\accounting\enums\enuSaleableStatus;
use shopack\base\frontend\common\rest\RestClientActiveRecord;

class SaleableModel extends RestClientActiveRecord
{
	use \iranhmusic\shopack\mha\common\accounting\models\SaleableModelTrait;

	public static $resourceName = 'mha/accounting/saleable';

	public function isSoftDeleted()
  {
    return ($this->slbStatus == enuSaleableStatus::Removed);
  }

  public static function toString($ids)
	{
		if (empty($ids))
			return null;

		$models = self::findAll($ids);
		$desc = [];
		foreach ($models as $item) {
			$desc[] = $item->slbName;
		}

		return implode('|', $desc);
	}

}
