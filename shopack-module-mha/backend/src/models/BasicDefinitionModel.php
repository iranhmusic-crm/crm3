<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use Yii;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuBasicDefinitionStatus;

class BasicDefinitionModel extends MhaActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\BasicDefinitionModelTrait;

  use \shopack\base\common\db\SoftDeleteActiveRecordTrait;
  public function initSoftDelete()
  {
    $this->softdelete_RemovedStatus  = enuBasicDefinitionStatus::Removed;
    // $this->softdelete_StatusField    = 'bdfType';
    $this->softdelete_RemovedAtField = 'bdfRemovedAt';
    $this->softdelete_RemovedByField = 'bdfRemovedBy';
	}

	public static function tableName()
	{
		return '{{%MHA_BasicDefinition}}';
	}

	public function behaviors()
	{
		return [
			[
				'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
				'createdAtAttribute' => 'bdfCreatedAt',
				'createdByAttribute' => 'bdfCreatedBy',
				'updatedAtAttribute' => 'bdfUpdatedAt',
				'updatedByAttribute' => 'bdfUpdatedBy',
			],
		];
	}

}
