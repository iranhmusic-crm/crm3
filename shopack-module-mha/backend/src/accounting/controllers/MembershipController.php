<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\accounting\controllers;

use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii\data\ActiveDataProvider;
use shopack\base\common\helpers\ExceptionHelper;
use shopack\base\backend\helpers\PrivHelper;
use shopack\base\backend\controller\BaseRestController;
use iranhmusic\shopack\mha\backend\accounting\models\MembershipForm;
use shopack\aaa\backend\models\OfflinePaymentModel;

// use iranhmusic\shopack\mha\backend\accounting\models\RenewViaInvoiceForm;

class MembershipController extends BaseRestController
{
	public function behaviors()
	{
		$behaviors = parent::behaviors();

		// $behaviors[static::BEHAVIOR_AUTHENTICATOR]['except'] = [
		// ];

		return $behaviors;
	}

	public function actionOptions()
	{
		return 'options';
	}

	//called by owner
	public function actionRenewalInfo($memberID = null)
	{
		if ($memberID == null)
			$memberID = Yii::$app->user->id;
		else if (($memberID != Yii::$app->user->id)
				&& (PrivHelper::hasPriv('mha/member-membership/crud', '0100') == false)) {
			throw new ForbiddenHttpException('access denied');
		}

		list ($startDate, $endDate, $years, $unitPrice, $totalPrice, $saleableModel, $cardPrintSaleableModel, $printCardAmount) = MembershipForm::getRenewalInfo($memberID);

		return [
			'startDate'		=> $startDate,
			'endDate'			=> $endDate,
			'years'				=> $years,
			'unitPrice'		=> $unitPrice,
			'totalPrice'	=> $totalPrice,
			'saleableID'	=> $saleableModel->slbID,
			'printCardAmount'	=> $printCardAmount,
		];
	}

	public function actionAddToBasket()
	{
		$base64Basketdata = $_POST['basketdata'] ?? [];
		$printCard = $_POST['printCard'] ?? null;
		$discountCode = $_POST['discountCode'] ?? null;

		$result = MembershipForm::addToBasket($base64Basketdata, null, $printCard, $discountCode);

		return [
			'key' => $result[0],
			'basket' => $result[1],
		];
	}

	//called by operator
	public function actionRenewalInfoForInvoice(
		$memberID = null,
		$ofpID = null
	) {
		PrivHelper::checkPriv('mha/member-membership/crud', '0100');

		list (
			$startDate,
			$maxYears,
			$saleableModels,
			$memberModel
		) = MembershipForm::getRenewalInfoForInvoice($memberID, $ofpID);

		return [
			'startDate'				=> $startDate,
			'maxYears'				=> $maxYears,
			'saleableModels'	=> $saleableModels,
			'memberModel'			=> $memberModel,
		];
	}

	public function actionRenewViaInvoice()
	{
		PrivHelper::checkPriv('mha/member-membership/crud', '1000');

		$ownerUserID			= $_POST['ownerUserID'];
		$saleableID				= $_POST['saleableID'];
		$years						= $_POST['years'];
		$printCard				= $_POST['printCard'] ?? true;
		$discountCode			= $_POST['discountCode'] ?? null;
		$offlinePaymentID	= $_POST['offlinePaymentID'] ?? null;
		$invoiceID				= $_POST['invoiceID'] ?? null;

		$result = MembershipForm::addToInvoice(
			$ownerUserID,
			$saleableID,
			$years,
			$printCard,
			$discountCode,
			$offlinePaymentID,
			$invoiceID
		);

		return [
			'key' => $result[0],
			'invoice' => $result[1],
		];
	}

}
