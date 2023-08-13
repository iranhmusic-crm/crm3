<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use Yii;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuDocumentStatus;

class DocumentModel extends MhaActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\DocumentModelTrait;

  use \shopack\base\common\db\SoftDeleteActiveRecordTrait;
  public function initSoftDelete()
  {
    $this->softdelete_RemovedStatus  = enuDocumentStatus::Removed;
    // $this->softdelete_StatusField    = 'docType';
    $this->softdelete_RemovedAtField = 'docRemovedAt';
    $this->softdelete_RemovedByField = 'docRemovedBy';
	}

	public static function tableName()
	{
		return '{{%MHA_Document}}';
	}

	public function behaviors()
	{
		return [
			[
				'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
				'createdAtAttribute' => 'docCreatedAt',
				'createdByAttribute' => 'docCreatedBy',
				'updatedAtAttribute' => 'docUpdatedAt',
				'updatedByAttribute' => 'docUpdatedBy',
			],
		];
	}

}
