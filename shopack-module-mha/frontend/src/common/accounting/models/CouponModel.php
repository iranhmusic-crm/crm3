<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\accounting\models;

use Yii;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\frontend\rest\RestClientActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuDocumentStatus;

class CouponModel extends RestClientActiveRecord
{
	use \iranhmusic\shopack\mha\common\accounting\models\CouponModelTrait;

	public static $resourceName = 'mha/accounting/coupon';

	public function attributeLabels()
	{
		return [
		];
	}

	public function isSoftDeleted()
  {
    return ($this->cpnStatus == enuDocumentStatus::Removed);
  }

	public static function canCreate() {
		return true;
	}

	public function canUpdate() {
		return ($this->cpnType != enuDocumentStatus::Removed);
	}

	public function canDelete() {
		return ($this->cpnType != enuDocumentStatus::Removed);
	}

	public function canUndelete() {
		return ($this->cpnType == enuDocumentStatus::Removed);
	}

}
