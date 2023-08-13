<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use Yii;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuInsurerStatus;

class MasterInsurerModel extends MhaActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\MasterInsurerModelTrait;

  use \shopack\base\common\db\SoftDeleteActiveRecordTrait;
  public function initSoftDelete()
  {
    $this->softdelete_RemovedStatus  = enuInsurerStatus::Removed;
    // $this->softdelete_StatusField    = 'minsStatus';
    $this->softdelete_RemovedAtField = 'minsRemovedAt';
    $this->softdelete_RemovedByField = 'minsRemovedBy';
	}

	public static function tableName()
	{
		return '{{%MHA_MasterInsurer}}';
	}

	public function behaviors()
	{
		return [
			[
				'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
				'createdAtAttribute' => 'minsCreatedAt',
				'createdByAttribute' => 'minsCreatedBy',
				'updatedAtAttribute' => 'minsUpdatedAt',
				'updatedByAttribute' => 'minsUpdatedBy',
			],
		];
	}

}
