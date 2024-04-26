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

class DiscountUsageModel extends RestClientActiveRecord
{
	use \iranhmusic\shopack\mha\common\accounting\models\DiscountUsageModelTrait;

	public static $resourceName = 'mha/accounting/discount-usage';

	public function attributeLabels()
	{
		return [
			'dscusgID'								=> Yii::t('app', 'ID'),
			'dscusgUserID'						=> Yii::t('aaa', 'User'),
			'dscusgUserAssetID'				=> Yii::t('aaa', 'User Asset'),
			'dscusgDiscountID'				=> Yii::t('aaa', 'Discount'),
			'dscusgDiscountSerialID'	=> Yii::t('aaa', 'Discount Serial'),
			'dscusgAmount'						=> Yii::t('aaa', 'Amount'),
			'dscusgCreatedAt'					=> Yii::t('aaa', 'Created At'),

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
