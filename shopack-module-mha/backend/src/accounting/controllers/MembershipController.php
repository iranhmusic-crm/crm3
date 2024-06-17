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
use iranhmusic\shopack\mha\backend\accounting\models\RenewViaInvoiceForm;

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

	public function actionRenewViaInvoice()
	{
		//todo: (vi) check permission

		$model = new RenewViaInvoiceForm();

		// $this->checkPermission($model);

		if ($model->load(Yii::$app->request->getBodyParams(), '') == false)
			throw new NotFoundHttpException("parameters not provided");

		$model->process();

		return [
			// // 'result' => [
			// 	// 'message' => 'updated',
			// 	'docID' => $model->docID,
			// 	'docStatus' => $model->docStatus,
			// 	'docUpdatedAt' => $model->docUpdatedAt,
			// 	'docUpdatedBy' => $model->docUpdatedBy,
			// // ],
		];

	}

}
