<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use yii\db\Expression;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuMembershipStatus;
use shopack\base\common\shop\IAssetEntity;

class MemberMembershipModel extends MhaActiveRecord implements IAssetEntity
{
	use \iranhmusic\shopack\mha\common\models\MemberMembershipModelTrait;

	public static function tableName()
	{
		return '{{%MHA_MemberMembership}}';
	}

	public function behaviors()
	{
		return [
			[
				'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
				'createdAtAttribute' => 'mbrshpCreatedAt',
				'createdByAttribute' => 'mbrshpCreatedBy',
				'updatedAtAttribute' => 'mbrshpUpdatedAt',
				'updatedByAttribute' => 'mbrshpUpdatedBy',
			],
		];
	}

	//list ($startDate, $endDate, $years, $price, $saleableID)
	public static function getRenewalInfo($memberID)
	{
		if (empty($memberID))
			throw new UnprocessableEntityHttpException('The MemberID not provided');

		$memberModel = MemberModel::find()->where(['mbrUserID' => $memberID])->one();
		if ($memberModel == null)
			throw new NotFoundHttpException('The requested item not exist.');

		if (empty($memberModel->mbrRegisterCode))
			throw new UnprocessableEntityHttpException('The member does not have a register code');

		if (empty($memberModel->mbrAcceptedAt))
			throw new UnprocessableEntityHttpException('Membership start date is blank');

		$lastMembership = self::find()
			->andWhere(['mbrshpMemberID' => $memberID])
			->orderBy('mbrshpEndDate DESC')
			->one();

		$now = new \DateTime('now');

		if (empty($lastMembership->mbrshpEndDate)) {
			$startDate = new \DateTime($memberModel->mbrAcceptedAt);
			$startDate->setTime(0, 0);
		} else {
			$startDate = new \DateTime($lastMembership->mbrshpEndDate);
			$startDate->setTime(0, 0);
			// $startDate->add(\DateInterval::createFromDateString('1 day'));

			if ($startDate > $now) {
				$remained = date_diff($now, $startDate);
				if ($remained->days > 30) {
					throw new UnprocessableEntityHttpException('There are more than 30 days of current membership left');
				}
			}
		}

		$endDate = clone $startDate;
		$endDate->add(\DateInterval::createFromDateString('1 year'));
		// $endDate->sub(\DateInterval::createFromDateString('1 day'));

		$years = 1;
		while ($endDate < $now) {
			$endDate->add(\DateInterval::createFromDateString('1 year'));
			++$years;
		}

		$startDate = $startDate->format('Y-m-d');
		$endDate = $endDate->format('Y-m-d');

		$membershipModel = MembershipModel::find()
			->andWhere(['<=', 'mshpStartFrom', new Expression('NOW()')])
			->andWhere(['mshpStatus' => enuMembershipStatus::Active])
			->orderBy('mshpStartFrom DESC')
			->one();

		if ($membershipModel == null)
			throw new NotFoundHttpException('Definition of membership at this date was not found.');

		$unitPrice = $membershipModel->mshpYearlyPrice;
		$totalPrice = $years * $unitPrice;
		$saleableID = $membershipModel->mshpID;

		return [$startDate, $endDate, $years, $unitPrice, $totalPrice, $saleableID];
	}

}
