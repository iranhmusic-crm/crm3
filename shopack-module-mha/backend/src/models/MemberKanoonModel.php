<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use Yii;
use yii\db\Expression;
use yii\web\UnprocessableEntityHttpException;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuMemberKanoonStatus;

class MemberKanoonModel extends MhaActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\MemberKanoonModelTrait;

	public static function tableName()
	{
		return '{{%MHA_Member_Kanoon}}';
	}

	public $mbrRegisterCode = null;

	public function behaviors()
	{
		return [
			[
				'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
				'createdAtAttribute' => 'mbrknnCreatedAt',
				'createdByAttribute' => 'mbrknnCreatedBy',
				'updatedAtAttribute' => 'mbrknnUpdatedAt',
				'updatedByAttribute' => 'mbrknnUpdatedBy',
			],
		];
	}

	public function save($runValidation = true, $attributeNames = null)
	{
		//check status changed to Accepted
		$accepted = false;

		$values = $this->getDirtyAttributes(['mbrknnStatus']);
		if (empty($values) == false) {
			$oldStatus = $this->oldAttributes['mbrknnStatus'] ?? null;
			if (($oldStatus != $this->mbrknnStatus)
				&& ($this->mbrknnStatus == enuMemberKanoonStatus::Accepted)
			) {
				$accepted = true;
			}
		}

		// if (empty($this->member->mbrRegisterCode)) {
		// 	$values = $this->getDirtyAttributes(['mbrknnStatus']);
		// 	if (empty($values) == false) {
		// 		if ($this->mbrknnStatus == enuMemberKanoonStatus::Accepted) {
		// 			$oldStatus = $this->oldAttributes['mbrknnStatus'];
		// 			if ($oldStatus !== enuMemberKanoonStatus::Accepted) {
		// 				$accepted = true;
		// 				// throw new UnprocessableEntityHttpException('To confirm the membership, just use the Accept command');
		// 			}
		// 		}
		// 	}
		// }

		if ($accepted) {
			if (empty($this->mbrknnAcceptedAt))
				$this->mbrknnAcceptedAt = (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d');
				// throw new UnprocessableEntityHttpException('تاریخ تایید عضویت تعیین نشده است.');

			$transaction = Yii::$app->db->beginTransaction();
		}

    try {
			//moved to trigger
			// $mbrknnHistory = $this->mbrknnHistory;
			// if (empty($mbrknnHistory))
			// 	$mbrknnHistory = [];
			// $mbrknnHistory[] = [
			// 	'at' => new \yii\web\JsExpression('UNIX_TIMESTAMP(NOW())'),
			// 	'status' => $this->mbrknnStatus,
			// 	'comment' => $this->mbrknnComment,
			// ];
			// $this->mbrknnHistory = $mbrknnHistory;

			//------------------------
			if (parent::save($runValidation, $attributeNames) == false)
				throw new UnprocessableEntityHttpException(implode("\n", $this->getFirstErrors()));

			if ($accepted) {
				if (MemberModel::AssignRegistrationCode(
						$this->mbrknnMemberID, $this->mbrRegisterCode, $this->mbrknnAcceptedAt)
					&& empty($this->mbrRegisterCode)
				) {
					//fetch saved mbrRegisterCode
					$qry =<<<SQL
  SELECT mbrRegisterCode
	  FROM tbl_MHA_Member
   WHERE mbrUserID = {$this->mbrknnMemberID}
SQL;
					$row = Yii::$app->db->createCommand($qry)->queryOne();
					if (empty($row) == false)
						$this->mbrRegisterCode = $row['mbrRegisterCode'];
				}
			}

			if (isset($transaction))
				$transaction->commit();

			return true;

    } catch (\Throwable $e) {
			if (isset($transaction))
	      $transaction->rollBack();

      throw $e;
    }
	}

}
