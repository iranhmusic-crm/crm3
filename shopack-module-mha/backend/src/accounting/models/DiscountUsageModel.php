<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\accounting\models;

use Yii;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;
// use shopack\base\common\accounting\enums\enuDiscountUsageStatus;

class DiscountUsageModel extends MhaActiveRecord
{
	use \iranhmusic\shopack\mha\common\accounting\models\DiscountUsageModelTrait;

	// use \shopack\base\common\db\SoftDeleteActiveRecordTrait;
  // public function initSoftDelete()
  // {
  //   $this->softdelete_RemovedStatus  = enuDiscountUsageStatus::Removed;
  //   // $this->softdelete_StatusField    = 'dscusgType';
  //   $this->softdelete_RemovedAtField = 'dscusgRemovedAt';
  //   $this->softdelete_RemovedByField = 'dscusgRemovedBy';
	// }

	public static function tableName()
	{
		return '{{%MHA_Accounting_DiscountUsage}}';
	}

	// public function behaviors()
	// {
	// 	return [
	// 		[
	// 			'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
	// 			'createdAtAttribute' => 'dscusgCreatedAt',
	// 			'createdByAttribute' => 'dscusgCreatedBy',
	// 			'updatedAtAttribute' => 'dscusgUpdatedAt',
	// 			'updatedByAttribute' => 'dscusgUpdatedBy',
	// 		],
	// 	];
	// }

}
