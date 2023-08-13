<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use Yii;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuInsurerStatus;

class SupplementaryInsurerModel extends MhaActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\SupplementaryInsurerModelTrait;

  use \shopack\base\common\db\SoftDeleteActiveRecordTrait;
  public function initSoftDelete()
  {
    $this->softdelete_RemovedStatus  = enuInsurerStatus::Removed;
    // $this->softdelete_StatusField    = 'sinsStatus';
    $this->softdelete_RemovedAtField = 'sinsRemovedAt';
    $this->softdelete_RemovedByField = 'sinsRemovedBy';
	}

	public static function tableName()
	{
		return '{{%MHA_SupplementaryInsurer}}';
	}

	public function behaviors()
	{
		return [
			[
				'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
				'createdAtAttribute' => 'sinsCreatedAt',
				'createdByAttribute' => 'sinsCreatedBy',
				'updatedAtAttribute' => 'sinsUpdatedAt',
				'updatedByAttribute' => 'sinsUpdatedBy',
			],
		];
	}

}
