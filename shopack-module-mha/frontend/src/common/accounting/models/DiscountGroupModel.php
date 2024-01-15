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

class DiscountGroupModel extends RestClientActiveRecord
{
	use \iranhmusic\shopack\mha\common\accounting\models\DiscountGroupModelTrait;

	public static $resourceName = 'mha/accounting/discount-group';

	public function attributeLabels()
	{
		return [
			'dscgrpID'                      => Yii::t('app', 'ID'),
			// 'dscgrpUUID'
			'dscgrpName'                    => Yii::t('app', 'Name'),
			'dscgrpComputeType'             => Yii::t('app', 'Compute Type'),
			'dscgrpMaxAmount'               => Yii::t('app', 'Max Amount'),
			'dscgrpMaxType'                 => Yii::t('app', 'Max Type'),

			'dscgrpCreatedAt'               => Yii::t('app', 'Created At'),
			'dscgrpCreatedBy'               => Yii::t('app', 'Created By'),
			'dscgrpCreatedBy_User'          => Yii::t('app', 'Created By'),
			'dscgrpUpdatedAt'               => Yii::t('app', 'Updated At'),
			'dscgrpUpdatedBy'               => Yii::t('app', 'Updated By'),
			'dscgrpUpdatedBy_User'          => Yii::t('app', 'Updated By'),
			'dscgrpRemovedAt'               => Yii::t('app', 'Removed At'),
			'dscgrpRemovedBy'               => Yii::t('app', 'Removed By'),
			'dscgrpRemovedBy_User'          => Yii::t('app', 'Removed By'),

			//-- mha --
		];
	}

	public function isSoftDeleted()
  {
    return false;
  }

	public static function canCreate() {
		return true;
	}

	public function canUpdate() {
		return true;
	}

	public function canDelete() {
		return true;
	}

	public function canUndelete() {
		return false;
	}

}
