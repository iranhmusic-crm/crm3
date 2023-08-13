<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use Yii;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuInsurerStatus;

class MasterInsurerTypeModel extends MhaActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\MasterInsurerTypeModelTrait;

  use \shopack\base\common\db\SoftDeleteActiveRecordTrait;
  public function initSoftDelete()
  {
    $this->softdelete_RemovedStatus  = enuInsurerStatus::Removed;
    // $this->softdelete_StatusField    = 'minstypStatus';
    $this->softdelete_RemovedAtField = 'minstypRemovedAt';
    $this->softdelete_RemovedByField = 'minstypRemovedBy';
	}

	public static function tableName()
	{
		return '{{%MHA_MasterInsurerType}}';
	}

	public function behaviors()
	{
		return [
			[
				'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
				'createdAtAttribute' => 'minstypCreatedAt',
				'createdByAttribute' => 'minstypCreatedBy',
				'updatedAtAttribute' => 'minstypUpdatedAt',
				'updatedByAttribute' => 'minstypUpdatedBy',
			],
		];
	}

}
