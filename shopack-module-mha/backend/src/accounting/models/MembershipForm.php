<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\accounting\models;

use Yii;
use yii\base\Model;
use yii\db\Expression;
use yii\db\Query;
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
use shopack\aaa\backend\models\UserModel;
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
			throw new UnprocessableEntityHttpException('Both memberID and ofpID are empty');

		$offlinePaymentModel = null;
		if (empty($ofpID) == false) {
			$offlinePaymentModel = OfflinePaymentModel::findOne($ofpID);

			if (empty($offlinePaymentModel))
				throw new NotFoundHttpException('Offline Payment not found');

			if ((empty($memberID) == false)
					&& ($memberID != $offlinePaymentModel->ofpOwnerUserID))
				throw new UnprocessableEntityHttpException('member ids are not matched');

			$memberID = $offlinePaymentModel->ofpOwnerUserID;
		}

		$memberModel = MemberModel::find()
			->select(MemberModel::selectableColumns())
			->addSelect(UserModel::selectableColumns())
			->innerJoinWith('user')
			->where(['mbrUserID' => $memberID])
			->asArray()
			->one();

		if ($memberModel == null)
			throw new NotFoundHttpException('The requested item does not exist.');

		if (empty($memberModel['mbrRegisterCode']))
			throw new UnprocessableEntityHttpException('The member does not have a register code');

		if (empty($memberModel['mbrAcceptedAt']))
			throw new UnprocessableEntityHttpException('Membership start date is blank');

		$lastMembership = UserAssetModel::find()
			->joinWith('saleable', false, 'INNER JOIN')
			->joinWith('saleable.product', false, 'INNER JOIN')
			->andWhere(['uasActorID' => $memberID])
			->orderBy('uasValidToDate DESC')
			->one();

		$now = new \DateTime('now');

		if (empty($lastMembership->uasValidToDate)) {
			$startDate = new \DateTime($memberModel['mbrAcceptedAt']);
			$startDate->setTime(0, 0);
		} else {
			$startDate = new \DateTime($lastMembership->uasValidToDate);
			$startDate->setTime(0, 0);

			//preventing 3 month checking for operators
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

		$fnGetSaleables = function($saleableType) use ($memberID, $offlinePaymentModel) {
			//-----------------
			$query = SaleableModel::find()
				->select(SaleableModel::selectableColumns())
				->joinWith('product', false, 'INNER JOIN')
				->joinWith('product.unit')
				->andWhere(['prdMhaType' => $saleableType])
				->andWhere(['slbStatus' => enuSaleableStatus::Active])
				->andWhere(['<=', 'slbAvailableFromDate', new Expression('NOW()')])
				->orderBy('slbAvailableFromDate DESC')
			;
			SaleableModel::appendDiscountQuery($query, $memberID);
			$saleableModel = $query->asArray()->one();

			if (empty($saleableModel['slbID']))
				$saleableModels = [];
			else
				$saleableModels = [$saleableModel];

			if ($offlinePaymentModel != null) {
				$query2 = SaleableModel::find()
					->select(SaleableModel::selectableColumns())
					->joinWith('product', false, 'INNER JOIN')
					->joinWith('product.unit')
					->andWhere(['prdMhaType' => $saleableType])
					->andWhere(['slbStatus' => enuSaleableStatus::Active])
					->andWhere(['<=', 'slbAvailableFromDate', $offlinePaymentModel->ofpPayDate])
					->orderBy('slbAvailableFromDate DESC')
				;
				SaleableModel::appendDiscountQuery($query2, $memberID);
				$saleableModel2 = $query2->asArray()->one();

				if (empty($saleableModel2['slbID']) == false) {
					if (empty($saleableModels))
						$saleableModels = [$saleableModel2];
					else
						$saleableModels = [$saleableModel2, $saleableModel];
				}
			}

			if (empty($saleableModels))
				throw new NotFoundHttpException('Membership saleables not found');

			return $saleableModels;
		};

		$fnGetOneSaleable = function($saleableType) use ($memberID, $offlinePaymentModel) {
			//-----------------
			$query = SaleableModel::find()
				->select(SaleableModel::selectableColumns())
				->joinWith('product', false, 'INNER JOIN')
				->joinWith('product.unit')
				->andWhere(['prdMhaType' => $saleableType])
				->andWhere(['slbStatus' => enuSaleableStatus::Active])
				->andWhere(['<=', 'slbAvailableFromDate', ($offlinePaymentModel == null)
					? (new Expression('NOW()'))
					: $offlinePaymentModel->ofpPayDate
				])
				->orderBy('slbAvailableFromDate DESC')
			;
			SaleableModel::appendDiscountQuery($query, $memberID);
			$saleableModel = $query->asArray()->one();

			if ((empty($offlinePaymentModel) == false) && empty($saleableModel['slbID'])) {
				$query = SaleableModel::find()
					->select(SaleableModel::selectableColumns())
					->joinWith('product', false, 'INNER JOIN')
					->joinWith('product.unit')
					->andWhere(['prdMhaType' => $saleableType])
					->andWhere(['slbStatus' => enuSaleableStatus::Active])
					->andWhere(['<=', 'slbAvailableFromDate', new Expression('NOW()')])
					->orderBy('slbAvailableFromDate DESC')
				;
				SaleableModel::appendDiscountQuery($query, $memberID);
				$saleableModel = $query->asArray()->one();
			}

			if (empty($saleableModel['slbID']))
				throw new NotFoundHttpException('Membership saleable not found');

			return [$saleableModel];
		};

		$membershipSaleableModels			= $fnGetOneSaleable(enuMhaProductType::Membership);
		$membershipCardSaleableModels	= $fnGetOneSaleable(enuMhaProductType::MembershipCard);

		//-----------------
		return [
			$startDate,
			$maxYears,
			$memberModel,
			$offlinePaymentModel,
			$membershipSaleableModels,
			$membershipCardSaleableModels
		];
	}

	public static function addToInvoice(
		$memberID,
		// $ofpID,
		$years,
		$membershipSaleableID,
		$membershipCardSaleableID,
		$invoiceID = null
	) {
		//find startDate and endDate
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

		if (empty($lastMembership->uasValidToDate)) {
			$startDate = new \DateTime($memberModel->mbrAcceptedAt);
		} else {
			$startDate = new \DateTime($lastMembership->uasValidToDate);
		}
		$startDate->setTime(0, 0);

		$endDate = clone $startDate;
		$endDate->add(\DateInterval::createFromDateString("{$years} year"));

		//add membership to basket:
		if (empty($membershipSaleableID) == false) {
			$membershipBasketModel = new BasketModel;
			$membershipBasketModel->saleableCode   = $membershipSaleableID;
			$membershipBasketModel->qty            = $years;
			$membershipBasketModel->maxQty         = $years;
			$membershipBasketModel->qtyStep        = 0; //0: do not allow to change qty in basket
			$membershipBasketModel->orderParams    = [
				'startDate'	=> $startDate,
				'endDate'		=> $endDate,
			];
			// $membershipBasketModel->orderAdditives = ;
			// $membershipBasketModel->discountCode   = $discountCode;
			// $membershipBasketModel->referrer       = ;
			// $membershipBasketModel->referrerParams = ;
			// $membershipBasketModel->apiTokenID     = ;
			// $membershipBasketModel->itemKey        = ;
			[$membershipItemKey, $invoiceID] = $membershipBasketModel->addToInvoice($memberID, $invoiceID);
		}

		//add membership CARD to basket:
		if (empty($membershipCardSaleableID) == false) {
			$membershipCardBasketModel = new BasketModel;
			$membershipCardBasketModel->saleableCode   = $membershipCardSaleableID;
			$membershipCardBasketModel->qty            = 1;
			$membershipCardBasketModel->maxQty         = 1;
			$membershipCardBasketModel->qtyStep        = 0; //0: do not allow to change qty in basket
			// $membershipCardBasketModel->orderParams    = ;
			// $membershipCardBasketModel->orderAdditives = ;
			// $membershipCardBasketModel->discountCode   = $discountCode;
			// $membershipCardBasketModel->referrer       = ;
			// $membershipCardBasketModel->referrerParams = ;
			// $membershipCardBasketModel->apiTokenID     = ;
			// $membershipCardBasketModel->itemKey        = ;
			if (empty($membershipItemKey) == false)
				$membershipCardBasketModel->dependencies		= [$membershipItemKey];
			[$membershipCardItemKey, $invoiceID] = $membershipCardBasketModel->addToInvoice($memberID, $invoiceID);
		}

		return [$membershipItemKey ?? null, $membershipCardItemKey ?? null, $invoiceID];
	}

}
