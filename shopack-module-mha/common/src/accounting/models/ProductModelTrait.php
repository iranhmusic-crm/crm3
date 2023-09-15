<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\accounting\models;

use shopack\base\common\rest\ModelColumnHelper;
use shopack\base\common\rest\enuColumnInfo;
use shopack\base\common\rest\enuColumnSearchType;
use shopack\base\common\validators\JsonValidator;
use iranhmusic\shopack\mha\common\accounting\enums\enuProductType;
use shopack\base\common\accounting\models\BaseProductModelTrait;

/*
`prdType`
*/

trait ProductModelTrait
{
	use BaseProductModelTrait;

	public static function columnsInfo()
  {
		$cols = BaseProductModelTrait::columnsInfo();

		$cols = array_merge($cols, [
			'prdType' => [
        enuColumnInfo::type       => ['string', 'max' => 1],
        enuColumnInfo::validator  => null,
        enuColumnInfo::default    => null, //enuProductType
        enuColumnInfo::required   => true,
        enuColumnInfo::selectable => true,
      ],
		]);

		return $cols;
	}

}
