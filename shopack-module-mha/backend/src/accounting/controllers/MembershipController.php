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

		list ($startDate, $endDate, $years, $unitPrice, $totalPrice, $saleableModel, $printCardAmount) = MembershipForm::getRenewalInfo($memberID);

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

		return MembershipForm::addToBasket($base64Basketdata, null, $printCard, $discountCode);
	}

}
