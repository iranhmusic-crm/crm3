<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\accounting\models;

use shopack\base\common\rest\ModelColumnHelper;
use shopack\base\common\rest\enuColumnInfo;
use shopack\base\common\rest\enuColumnSearchType;
use shopack\base\common\validators\JsonValidator;
use shopack\base\common\accounting\models\BaseUserAssetModelTrait;

/*
*/

trait UserAssetModelTrait
{
	use BaseUserAssetModelTrait;

  public static function getActorModelClassInfo()
	{
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\iranhmusic\shopack\mha\backend\models\MemberModel';
		else
			$className = '\iranhmusic\shopack\mha\frontend\common\models\MemberModel';

		return [$className, 'mbrUserID'];
	}

	public static function getSaleableModelClass()
	{
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\iranhmusic\shopack\mha\backend\accounting\models\SaleableModel';
		else
			$className = '\iranhmusic\shopack\mha\frontend\common\accounting\models\SaleableModel';

		return $className;
	}

	public static function getCouponModelClass()
	{
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\iranhmusic\shopack\mha\backend\accounting\models\CouponModel';
		else
			$className = '\iranhmusic\shopack\mha\frontend\common\accounting\models\CouponModel';

		return $className;
	}

}
