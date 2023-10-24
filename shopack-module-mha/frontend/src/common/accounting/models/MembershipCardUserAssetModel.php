<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\accounting\models;

use Yii;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\frontend\common\rest\RestClientActiveRecord;
use shopack\base\common\accounting\enums\enuUserAssetStatus;
use iranhmusic\shopack\mha\common\accounting\enums\enuUserAssetType;
use shopack\base\common\helpers\ArrayHelper;
use iranhmusic\shopack\mha\frontend\common\accounting\models\UserAssetModel;
use iranhmusic\shopack\mha\common\accounting\enums\enuMhaProductType;

class MembershipCardUserAssetModel extends UserAssetModel
{
	public static $resourceName = 'mha/accounting/user-asset';
	public static $resourceParams = [
		'prdMhaType' => enuMhaProductType::MembershipCard,
	];

	public function __construct()
	{
		// $this->prdMhaType = enuMhaProductType::Membership;
	}

	// public function attributeLabels()
	// {
	// 	return ArrayHelper::merge(parent::attributeLabels(), [
	// 	]);
	// }

	public function isSoftDeleted()
  {
    return false;
  }

	public static function canCreate() {
		return false;
	}

	public function canUpdate() {
		return false;
	}

	public function canDelete() {
		return false;
	}

	public function canUndelete() {
		return false;
	}

}
