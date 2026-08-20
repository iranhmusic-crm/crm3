<?php

/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\accounting\controllers;

use Yii;
use yii\web\ForbiddenHttpException;
use shopack\base\backend\helpers\PrivHelper;
use shopack\base\backend\controller\BaseRestController;
use iranhmusic\shopack\mha\backend\accounting\models\MembershipCardForm;

class MembershipCardController extends BaseRestController
{
	public function actionOptions()
	{
		return 'options';
	}

	public function actionRenewalInfo($memberID = null)
	{
		if ($memberID == null)
			$memberID = Yii::$app->user->id;
		else if (($memberID != Yii::$app->user->id)
			&& (PrivHelper::hasPriv('mha/member-membership-card/crud', '0100') == false)
		) {
			throw new ForbiddenHttpException('access denied');
		}

		$info = MembershipCardForm::getRenewalInfo($memberID);

		$membershipUserAssetID = $info['membershipUserAssetID'];
		$price                 = $info['price'];
		$saleableModel         = $info['saleableModel'];
		$lastMembership        = $info['lastMembership'];

		return [
			'membershipUserAssetID' => $membershipUserAssetID,
			'price'                 => $price,
			'saleableModel'         => $saleableModel,
		];
	}

	public function actionAddToBasket()
	{
		$base64Basketdata = Yii::$app->request->getBodyParam('basketdata', []);

		return MembershipCardForm::addToBasket($base64Basketdata);
	}
}
