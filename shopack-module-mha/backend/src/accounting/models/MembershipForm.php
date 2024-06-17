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
use shopack\aaa\backend\models\OfflinePaymentModel;
use shopack\base\common\accounting\enums\enuAmountType;

class MembershipForm extends Model
{
	//list ($startDate, $endDate, $years, $price, $saleableModel, $cardPrintSaleableModel, $printCardAmount)
	public static function getRenewalInfo($memberID, $printCard = true)
	{
		if (empty($memberID))
			throw new UnprocessableEntityHttpException('The MemberID not provided');

		$memberModel = MemberModel::find()->where(['mbrUserID' => $memberID])->one();
		if ($memberModel == null)
			throw new NotFoundHttpException('The requested item does not exist.');

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
				if ($remained->days > (31 * 3)) {
					throw new UnprocessableEntityHttpException('There are more than 3 months of current membership left');
				}
			}
		}

		$endDate = clone $startDate;
		$endDate->add(\DateInterval::createFromDateString('1 year'));
		// $endDate->sub(\DateInterval::createFromDateString('250 day'));

		$years = 1;
		$target = clone $now;
		$target->add(\DateInterval::createFromDateString('6 month'));
		while ($endDate < $target) {
			$endDate->add(\DateInterval::createFromDateString('1 year'));
			++$years;
		}

		// $diff = $endDate->diff($now)->days;
		// if ($diff < (365 / 2)) {
		// 	$endDate->add(\DateInterval::createFromDateString('1 year'));
		// 	++$years;
		// }

		$startDate = $startDate->format('Y-m-d');
		$endDate = $endDate->format('Y-m-d');

		$query = SaleableModel::find()
			->select(SaleableModel::selectableColumns())
			->joinWith('product', false, 'INNER JOIN')
			->joinWith('product.unit')
			->andWhere(['prdMhaType' => enuMhaProductType::Membership])
			->andWhere(['<=', 'slbAvailableFromDate', new Expression('NOW()')])
			->andWhere(['slbStatus' => enuSaleableStatus::Active])
			->orderBy('slbAvailableFromDate DESC')
		;
		$actorID = (Yii::$app->user->isGuest ? 0 : Yii::$app->user->id);
		SaleableModel::appendDiscountQuery($query, $actorID);
		$saleableModel = $query->one();

		if (empty($saleableModel->slbID))
			throw new NotFoundHttpException('Definition of membership at this date was not found.');

		$unitPrice = $saleableModel->slbBasePrice;
		$totalPrice = $years * $unitPrice;

		//-----------------
		$cardPrintSaleableModel = null;
		$printCardAmount = 0;

		if ($printCard) {
			$cardPrintSaleableModel = SaleableModel::find()
				->joinWith('product', false, 'INNER JOIN')
				->andWhere(['prdMhaType' => enuMhaProductType::MembershipCard])
				->andWhere(['<=', 'slbAvailableFromDate', new Expression('NOW()')])
				->andWhere(['slbStatus' => enuSaleableStatus::Active])
				->orderBy('slbAvailableFromDate DESC')
				->one();

			if ($cardPrintSaleableModel != null)
				$printCardAmount = $cardPrintSaleableModel->slbBasePrice;
		}

		//-----------------
		return [
			$startDate,
			$endDate,
			$years,
			$unitPrice,
			//$saleableModel->discountedBasePrice
			$totalPrice,
			$saleableModel,
			$cardPrintSaleableModel,
			$printCardAmount
		];
	}

	//called by owner
	public static function addToBasket(
		$basketdata,
		$saleableID = null,
		$printCard = true,
		$discountCode = null
	) {
		if (is_string($basketdata))
			$basketdata = Json::decode(base64_decode($basketdata));

		if ($basketdata === null)
			$basketdata = [];

		//get saleable info
		list (
			$startDate,
			$endDate,
			$years,
			$unitPrice,
			$totalPrice,
			$saleableModel,
			$cardPrintSaleableModel
		) = self::getRenewalInfo(Yii::$app->user->id, $printCard);

		if ($printCard && ($cardPrintSaleableModel == null))
			throw new NotFoundHttpException('Definition of membership card at this date was not found.');

		//1: add membership to basket:
		$membershipBasketModel = new BasketModel;
		$membershipBasketModel->saleableCode   = $saleableModel->slbCode;
		$membershipBasketModel->qty            = $years;
		$membershipBasketModel->maxQty         = $years;
		$membershipBasketModel->qtyStep        = 0; //0: do not allow to change qty in basket
		$membershipBasketModel->orderParams    = [
			'startDate'	=> $startDate,
			'endDate'		=> $endDate,
		];
		// $membershipBasketModel->orderAdditives = ;
		$membershipBasketModel->discountCode   = $discountCode;
		// $membershipBasketModel->referrer       = ;
		// $membershipBasketModel->referrerParams = ;
		// $membershipBasketModel->apiTokenID     = ;
		// $membershipBasketModel->itemKey        = ;
		[$membershipItemKey, $lastPreVoucher] = $membershipBasketModel->addToBasket();

		//2: add membership CARD to basket:
		if ($printCard) {
			$membershipCardBasketModel = new BasketModel;
			$membershipCardBasketModel->saleableCode   = $cardPrintSaleableModel->slbCode;
			$membershipCardBasketModel->qty            = 1;
			$membershipCardBasketModel->maxQty         = 1;
			$membershipCardBasketModel->qtyStep        = 0; //0: do not allow to change qty in basket
			// $membershipCardBasketModel->orderParams    = ;
			// $membershipCardBasketModel->orderAdditives = ;
			$membershipCardBasketModel->discountCode   = $discountCode;
			// $membershipCardBasketModel->referrer       = ;
			// $membershipCardBasketModel->referrerParams = ;
			// $membershipCardBasketModel->apiTokenID     = ;
			// $membershipCardBasketModel->itemKey        = ;
			$membershipCardBasketModel->dependencies		= [$membershipItemKey];
			[$membershipCardItemKey, $lastPreVoucher] = $membershipCardBasketModel->addToBasket();
		}

		return [$membershipItemKey, $lastPreVoucher];
	}

	//called by operator
	public static function getRenewalInfoForInvoice(
		$memberID,
		$ofpID,
		$printCard = true
	) {
		if (empty($memberID) && empty($ofpID))
			throw new UnprocessableEntityHttpException('Both memberid and ofpID are empty');

		if (empty($ofpID) == false) {
			$offlinePaymentModel = OfflinePaymentModel::findOne($ofpID);

			if (empty($offlinePaymentModel))
				throw new NotFoundHttpException('Offline Payment not found');

			if ((empty($memberID) == false)
					&& ($memberID != $offlinePaymentModel->ofpOwnerUserID))
				throw new UnprocessableEntityHttpException('member ids are not matched');

			$memberID = $offlinePaymentModel->ofpOwnerUserID;
		}

		$memberModel = MemberModel::find()->where(['mbrUserID' => $memberID])->one();
		if ($memberModel == null)
			throw new NotFoundHttpException('The requested item does not exist.');

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

			//omit 3 months checking for operators
			// if ($startDate > $now) {
			// 	$remained = date_diff($now, $startDate);
			// 	if ($remained->days > (31 * 3)) {
			// 		throw new UnprocessableEntityHttpException('There are more than 3 months of current membership left');
			// 	}
			// }
		}

		//todo: compute maxYears by remaining years from member-kanoon
		$maxYears = 3;

		$startDate = $startDate->format('Y-m-d');

		//-----------------
		$query = SaleableModel::find()
			->select(SaleableModel::selectableColumns())
			->joinWith('product', false, 'INNER JOIN')
			->joinWith('product.unit')
			->andWhere(['prdMhaType' => enuMhaProductType::Membership])
			->andWhere(['slbStatus' => enuSaleableStatus::Active])
			->orderBy('slbAvailableFromDate ASC')
		;

		if (isset($offlinePaymentModel))
			$query->andWhere(['>=', 'slbAvailableFromDate', $offlinePaymentModel->ofpPayDate->format('Y-m-d')]);
		else
			$query->andWhere(['>=', 'slbAvailableFromDate', new Expression('NOW()')]);

		SaleableModel::appendDiscountQuery($query, $memberID);

		$saleableModels = $query->asArray()->all();

		if (empty($saleableModels))
			throw new NotFoundHttpException('Membership saleables not found');

		// //-----------------
		// $cardPrintSaleableModel = null;
		// $printCardAmount = 0;

		// if ($printCard) {
		// 	$cardPrintSaleableModel = SaleableModel::find()
		// 		->joinWith('product', false, 'INNER JOIN')
		// 		->andWhere(['prdMhaType' => enuMhaProductType::MembershipCard])
		// 		->andWhere(['<=', 'slbAvailableFromDate', new Expression('NOW()')])
		// 		->andWhere(['slbStatus' => enuSaleableStatus::Active])
		// 		->orderBy('slbAvailableFromDate DESC')
		// 		->one();

		// 	if ($cardPrintSaleableModel != null)
		// 		$printCardAmount = $cardPrintSaleableModel->slbBasePrice;
		// }

		//-----------------
		return [
			$startDate,
			$maxYears,
			$saleableModels,
		];
	}

	public static function addToInvoice(
		$ownerUserID,
		$saleableID,
		$years,
		$printCard = true,
		$discountCode = null,
		$offlinePaymentID = null,
		$invoiceID = null
	) {




	}

}
