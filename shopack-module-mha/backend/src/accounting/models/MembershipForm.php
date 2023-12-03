<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\accounting\models;

use Yii;
use yii\base\Model;
use yii\db\Expression;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;
use Ramsey\Uuid\Uuid;
use shopack\base\common\helpers\Json;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\common\security\RsaPrivate;
use shopack\base\common\accounting\enums\enuSaleableStatus;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuMembershipStatus;
use iranhmusic\shopack\mha\backend\models\MemberModel;
use iranhmusic\shopack\mha\backend\accounting\models\UserAssetModel;
use iranhmusic\shopack\mha\backend\accounting\models\SaleableModel;
use iranhmusic\shopack\mha\backend\models\MemberMemberGroupModel;
use iranhmusic\shopack\mha\common\accounting\enums\enuMhaProductType;
use shopack\base\common\accounting\enums\enuAmountType;

class MembershipForm extends Model
{
	//list ($startDate, $endDate, $years, $price, $saleableModel)
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

		$lastMembership = UserAssetModel::find()
			->joinWith('saleable', false, 'INNER JOIN')
			->joinWith('saleable.product', false, 'INNER JOIN')
			->andWhere(['uasActorID' => $memberID])
			->orderBy('uasValidToDate DESC')
			->one();

		$now = new \DateTime('now');

		if (empty($lastMembership->uasValidToDate)) {
			$startDate = new \DateTime($memberModel->mbrAcceptedAt);
			$startDate->setTime(0, 0);
		} else {
			$startDate = new \DateTime($lastMembership->uasValidToDate);
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

		$saleableModel = SaleableModel::find()
			->joinWith('product', false, 'INNER JOIN')
			->andWhere(['prdMhaType' => enuMhaProductType::Membership])
			->andWhere(['<=', 'slbAvailableFromDate', new Expression('NOW()')])
			->andWhere(['slbStatus' => enuSaleableStatus::Active])
			->orderBy('slbAvailableFromDate DESC')
			->one();

		if ($saleableModel == null)
			throw new NotFoundHttpException('Definition of membership at this date was not found.');

		$unitPrice = $saleableModel->slbBasePrice;
		$totalPrice = $years * $unitPrice;

		return [$startDate, $endDate, $years, $unitPrice, $totalPrice, $saleableModel];
	}

	public static function addToBasket($basketdata, $saleableID = null)
	{
		if (is_string($basketdata))
			$basketdata = Json::decode(base64_decode($basketdata));

		if ($basketdata === null)
			$basketdata = [];

		//get saleable info
		list ($startDate, $endDate, $years, $unitPrice, $totalPrice, $saleableModel)
			= self::getRenewalInfo(Yii::$app->user->id);

		//todo: check user langauge from request header
		$desc = implode(' ', [
			$saleableModel->slbName,
			'-',
			'از',
			Yii::$app->formatter->asJalali($startDate),
			'تا',
			Yii::$app->formatter->asJalali($endDate),
			'به مدت',
			$years,
			'سال'
		]);

		//card print
		$cardPrintSaleableModel = SaleableModel::find()
			->joinWith('product', false, 'INNER JOIN')
			->andWhere(['prdMhaType' => enuMhaProductType::MembershipCard])
			->andWhere(['<=', 'slbAvailableFromDate', new Expression('NOW()')])
			->andWhere(['slbStatus' => enuSaleableStatus::Active])
			->orderBy('slbAvailableFromDate DESC')
			->one();

		if ($cardPrintSaleableModel == null)
			throw new NotFoundHttpException('Definition of membership card at this date was not found.');

		$parentModule = Yii::$app->controller->module->module;

		$membershipKey = Uuid::uuid4()->toString();





		//discount
		$membershipSystemDiscounts = DiscountModel::findSystemDiscount($saleableModel->slbID);


		$membershipCardSystemDiscounts = DiscountModel::findSystemDiscount($cardPrintSaleableModel->slbID);







		//member group
		$memberMemberGroupModels = MemberMemberGroupModel::find()
			->innerJoinWith('memberGroup')
			->andWhere(['mbrmgpMemberID' => Yii::$app->user->id])
			->andWhere(['OR',
				'mbrmgpStartAt IS NULL',
				['<=', 'mbrmgpStartAt', new Expression('NOW()')],
			])
			->andWhere(['OR',
				'mbrmgpEndAt IS NULL',
				['>=', 'mbrmgpEndAt', new Expression('NOW()')],
			])
			->all();

		$membershipDiscountAmount = 0;
		$membershipCardDiscountAmount = 0;

		if (empty($memberMemberGroupModels) == false) {
			foreach ($memberMemberGroupModels as $memberMemberGroup) {
				if ((empty($memberMemberGroup->memberGroup->mgpMembershipDiscountAmount) == false)
					&& (empty($memberMemberGroup->memberGroup->mgpMembershipDiscountType) == false)
				) {
					if ($memberMemberGroup->memberGroup->mgpMembershipDiscountType == enuAmountType::Percent) {
						$amount = $memberMemberGroup->memberGroup->mgpMembershipDiscountAmount * $totalPrice / 100.0;
					} else {
						$amount = min($totalPrice, $memberMemberGroup->memberGroup->mgpMembershipDiscountAmount);
					}

					if ($amount > $membershipDiscountAmount)
						$membershipDiscountAmount = $amount;
				}

				if ((empty($memberMemberGroup->memberGroup->mgpMembershipCardDiscountAmount) == false)
					&& (empty($memberMemberGroup->memberGroup->mgpMembershipCardDiscountType) == false)
				) {
					if ($memberMemberGroup->memberGroup->mgpMembershipCardDiscountType == enuAmountType::Percent) {
						$amount = $memberMemberGroup->memberGroup->mgpMembershipCardDiscountAmount * $cardPrintSaleableModel->slbBasePrice / 100.0;
					} else {
						$amount = min($cardPrintSaleableModel->slbBasePrice, $memberMemberGroup->memberGroup->mgpMembershipCardDiscountAmount);
					}

					if ($amount > $membershipCardDiscountAmount)
						$membershipCardDiscountAmount = $amount;
				}
			}

		}

		//
		$data = [
			'userid' => Yii::$app->user->id,
			'items' => [
				[//1: membership
					'key'				=> $membershipKey,
					'service'		=> $parentModule->id,
					// 'slbkey'		=> self::saleableKey(),
					'slbid'			=> $saleableModel->slbID,
					'desc' 			=> $desc,
					'qty'				=> $years,
					'unit'			=> $saleableModel->product->unit->untName,
					'prdtype'		=> $saleableModel->product->prdType,
					'unitprice' => $unitPrice,
					'slbinfo'		=> [
						'startDate' => $startDate,
						'endDate' => $endDate,
					],
					'maxqty'		=> $years,
					'qtystep'		=> 0, //0: do not allow to change qty in basket
					'discount'	=> $membershipDiscountAmount,
				],
				[//2: card print
					'service'		=> $parentModule->id,
					'slbid'			=> $cardPrintSaleableModel->slbID,
					'desc' 			=> $cardPrintSaleableModel->slbName,
					'qty'				=> 1,
					'unit'			=> $cardPrintSaleableModel->product->unit->untName,
					'prdtype'		=> $cardPrintSaleableModel->product->prdType, //always is physical
					'unitprice' => $cardPrintSaleableModel->slbBasePrice,
					'maxqty'		=> 1,
					'qtystep'		=> 0, //0: do not allow to change qty in basket
					'discount'	=> $membershipCardDiscountAmount,
					'dependencies' => [$membershipKey],
				],
			],
		];
		$data = Json::encode($data);

		if (empty($parentModule->servicePrivateKey))
			$data = base64_encode($data);
		else
			$data = RsaPrivate::model($parentModule->servicePrivateKey)->encrypt($data);

		list ($resultStatus, $resultData) = HttpHelper::callApi('aaa/basket/item',
			HttpHelper::METHOD_POST,
			[],
			[
				'data' => $data,
				'service'	=> $parentModule->id,
			]
		);

		if ($resultStatus < 200 || $resultStatus >= 300)
			throw new \yii\web\HttpException($resultStatus, Yii::t('mha', $resultData['message'], $resultData));

		return $resultData;
	}

}
