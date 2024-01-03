<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\accounting\models;

use Yii;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;
// use shopack\base\common\accounting\enums\enuDiscountGroupStatus;

class DiscountGroupModel extends MhaActiveRecord
{
	use \iranhmusic\shopack\mha\common\accounting\models\DiscountGroupModelTrait;

	// use \shopack\base\common\db\SoftDeleteActiveRecordTrait;
  // public function initSoftDelete()
  // {
  //   $this->softdelete_RemovedStatus  = enuDiscountGroupStatus::Removed;
  //   // $this->softdelete_StatusField    = 'dscgrpType';
  //   $this->softdelete_RemovedAtField = 'dscgrpRemovedAt';
  //   $this->softdelete_RemovedByField = 'dscgrpRemovedBy';
	// }

	public static function tableName()
	{
		return '{{%MHA_Accounting_DiscountGroup}}';
	}

	public function behaviors()
	{
		return [
			[
				'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
				'createdAtAttribute' => 'dscgrpCreatedAt',
				'createdByAttribute' => 'dscgrpCreatedBy',
				'updatedAtAttribute' => 'dscgrpUpdatedAt',
				'updatedByAttribute' => 'dscgrpUpdatedBy',
			],
		];
	}

}
