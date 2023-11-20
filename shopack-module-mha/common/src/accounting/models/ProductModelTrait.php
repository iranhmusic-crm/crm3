<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\accounting\models;

use shopack\base\common\rest\ModelColumnHelper;
use shopack\base\common\rest\enuColumnInfo;
use shopack\base\common\rest\enuColumnSearchType;
use shopack\base\common\validators\JsonValidator;
use iranhmusic\shopack\mha\common\accounting\enums\enuMhaProductType;
use shopack\base\common\accounting\models\BaseProductModelTrait;

/*
'prdMhaType',
*/

trait ProductModelTrait
{
	use BaseProductModelTrait {
		columnsInfo as trait_columnsInfo;
	}

	public static function columnsInfo()
  {
		$cols = static::trait_columnsInfo(); //BaseProductModelTrait::columnsInfo();

		$cols = array_merge($cols, [
			'prdMhaType' => [
        enuColumnInfo::type       => ['string', 'max' => 1],
        enuColumnInfo::validator  => null,
        enuColumnInfo::default    => null, //enuMhaProductType
        enuColumnInfo::required   => true,
        enuColumnInfo::selectable => true,
      ],
		]);

		return $cols;
	}

	public static function getUnitModelClass()
	{
		$className = get_called_class();

		if (str_contains($className, '\\backend\\'))
			$className = '\iranhmusic\shopack\mha\backend\accounting\models\UnitModel';
		else
			$className = '\iranhmusic\shopack\mha\frontend\common\accounting\models\UnitModel';

		return $className;
	}

}
