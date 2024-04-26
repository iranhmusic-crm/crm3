<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\accounting\models;

use Yii;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\frontend\common\rest\RestClientActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuDocumentStatus;
use shopack\base\common\accounting\enums\enuAmountType;
use shopack\base\frontend\common\helpers\Html;

class DiscountSerialModel extends RestClientActiveRecord
{
	use \iranhmusic\shopack\mha\common\accounting\models\DiscountSerialModelTrait;

	public static $resourceName = 'mha/accounting/discount-serial';

	public function attributeLabels()
	{
		return [
			'dscsnID'         => Yii::t('app', 'ID'),
			'dscsnDiscountID' => Yii::t('aaa', 'Discount'),
			'dscsnSN'         => Yii::t('aaa', 'Serial'),

			//-- mha --
		];
	}

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
