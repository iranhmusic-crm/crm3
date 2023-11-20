<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\common\accounting\models;

use shopack\base\common\rest\ModelColumnHelper;
use shopack\base\common\rest\enuColumnInfo;
use shopack\base\common\rest\enuColumnSearchType;
use shopack\base\common\validators\JsonValidator;
use shopack\base\common\accounting\models\BaseDiscountModelTrait;

/*
'dscTargetMemberGroupIDs',
'dscTargetKanoonIDs',
'dscTargetProductMhaTypes',
*/

trait DiscountModelTrait
{
	use BaseDiscountModelTrait {
		columnsInfo as trait_columnsInfo;
	}

	public static function columnsInfo()
  {
		$cols = static::trait_columnsInfo();

		$cols = array_merge($cols, [
      'dscTargetMemberGroupIDs' => [
        enuColumnInfo::type       => JsonValidator::class,
        enuColumnInfo::validator  => null,
        enuColumnInfo::default    => null,
        enuColumnInfo::required   => false,
        enuColumnInfo::selectable => true,
      ],
      'dscTargetKanoonIDs' => [
        enuColumnInfo::type       => JsonValidator::class,
        enuColumnInfo::validator  => null,
        enuColumnInfo::default    => null,
        enuColumnInfo::required   => false,
        enuColumnInfo::selectable => true,
      ],
      'dscTargetProductMhaTypes' => [
        enuColumnInfo::type       => JsonValidator::class,
        enuColumnInfo::validator  => null,
        enuColumnInfo::default    => null,
        enuColumnInfo::required   => false,
        enuColumnInfo::selectable => true,
      ],
		]);

		return $cols;
	}

}
