<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\accounting\models;

use Yii;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;
// use shopack\base\common\accounting\enums\enuDiscountSerialStatus;

class DiscountSerialModel extends MhaActiveRecord
{
	use \iranhmusic\shopack\mha\common\accounting\models\DiscountSerialModelTrait;

	// use \shopack\base\common\db\SoftDeleteActiveRecordTrait;
  // public function initSoftDelete()
  // {
  //   $this->softdelete_RemovedStatus  = enuDiscountSerialStatus::Removed;
  //   // $this->softdelete_StatusField    = 'dscsnType';
  //   $this->softdelete_RemovedAtField = 'dscsnRemovedAt';
  //   $this->softdelete_RemovedByField = 'dscsnRemovedBy';
	// }

	public static function tableName()
	{
		return '{{%MHA_Accounting_DiscountSerial}}';
	}

	// public function behaviors()
	// {
	// 	return [
	// 		[
	// 			'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
	// 			'createdAtAttribute' => 'dscsnCreatedAt',
	// 			'createdByAttribute' => 'dscsnCreatedBy',
	// 			'updatedAtAttribute' => 'dscsnUpdatedAt',
	// 			'updatedByAttribute' => 'dscsnUpdatedBy',
	// 		],
	// 	];
	// }

}
