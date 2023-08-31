<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use Yii;
use yii\web\NotFoundHttpException;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuReportType;
use iranhmusic\shopack\mha\common\enums\enuReportStatus;
use shopack\aaa\backend\models\UserModel;
use iranhmusic\shopack\mha\backend\models\MemberModel;
use iranhmusic\shopack\mha\backend\models\MemberKanoonModel;

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

	/**
	 * return query
	 */
	public function run()
	{
		switch ($this->rptType) {
			case enuReportType::Members:
				return $this->runMembers();
			case enuReportType::Members:
				return $this->runFinancial();
		}

		throw new NotFoundHttpException('Report type not supported.');
	}

	/**
	 * return query
	 */
	private function runMembers()
	{
		$jointoUser = false;
		$jointoUserImage = false;
		$jointoKanoon = false;

		$rptOutputFields = array_keys($this->rptOutputFields);
		foreach ($rptOutputFields as $k => &$v) {
			if (str_starts_with($v, 'usr')) {
				$jointoUser = true;

				if ($v == 'usrImage')
					$jointoUserImage = true;

			} else if (str_starts_with($v, 'mbrknn')) {
				$jointoKanoon = true;
			} else if (str_starts_with($v, 'knn')) {
				$jointoKanoon = true;
			} else if (str_starts_with($v, 'mbr')) {
			} else  {
				// unknown field
			}
		}

		$query = MemberModel::find();

		//columns
		$query->select($rptOutputFields);

		//join
		if ($jointoUser) {
			$query->innerJoinWith('user');

			if ($jointoUserImage)
				$query->joinWith('user.imageFile');
		}


		return $query;
	}


	/**
	 * return query
	 */
	private function runFinancial()
	{
		//*******************************

		throw new \Exception('not implemented yet!');

		//*******************************
	}

}
