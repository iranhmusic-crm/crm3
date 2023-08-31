<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use Yii;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuReportType;
use iranhmusic\shopack\mha\common\enums\enuReportStatus;

class ReportModel extends MhaActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\ReportModelTrait;

  use \shopack\base\common\db\SoftDeleteActiveRecordTrait;
  public function initSoftDelete()
  {
    $this->softdelete_RemovedStatus  = enuReportStatus::Removed;
    // $this->softdelete_StatusField    = 'rptStatus';
    $this->softdelete_RemovedAtField = 'rptRemovedAt';
    $this->softdelete_RemovedByField = 'rptRemovedBy';
	}

	public static function tableName()
	{
		return '{{%MHA_Report}}';
	}

	public function behaviors()
	{
		return [
			[
				'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
				'createdAtAttribute' => 'rptCreatedAt',
				'createdByAttribute' => 'rptCreatedBy',
				'updatedAtAttribute' => 'rptUpdatedAt',
				'updatedByAttribute' => 'rptUpdatedBy',
			],
		];
	}

}
