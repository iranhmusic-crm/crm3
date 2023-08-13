<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use Yii;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuKanoonStatus;

class KanoonModel extends MhaActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\KanoonModelTrait;

  use \shopack\base\common\db\SoftDeleteActiveRecordTrait;
  public function initSoftDelete()
  {
    $this->softdelete_RemovedStatus  = enuKanoonStatus::Removed;
    // $this->softdelete_StatusField    = 'knnStatus';
    $this->softdelete_RemovedAtField = 'knnRemovedAt';
    $this->softdelete_RemovedByField = 'knnRemovedBy';
	}

	public static function tableName()
	{
		return '{{%MHA_Kanoon}}';
	}

	public function behaviors()
	{
		return [
			[
				'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
				'createdAtAttribute' => 'knnCreatedAt',
				'createdByAttribute' => 'knnCreatedBy',
				'updatedAtAttribute' => 'knnUpdatedAt',
				'updatedByAttribute' => 'knnUpdatedBy',
			],
		];
	}

	public function save($runValidation = true, $attributeNames = null)
  {
		if (empty($this->knnDescFieldType) && (empty($this->knnDescFieldLabel) == false))
			$this->knnDescFieldLabel = null;

    return parent::save($runValidation, $attributeNames);
  }

}
