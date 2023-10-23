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
use iranhmusic\shopack\mha\backend\accounting\models\MembershipCardForm;

class MembershipCardController extends BaseRestController
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
				&& (PrivHelper::hasPriv('mha/member-membership-card/crud', '0100') == false)) {
			throw new ForbiddenHttpException('access denied');
		}

		list ($membershipUserAssetID, $price, $saleableModel) = MembershipCardForm::getRenewalInfo($memberID);

		return [
			'membershipUserAssetID' => $membershipUserAssetID,
			'price' => $price,
			'saleableModel' => $saleableModel,
		];
	}

	public function actionAddToBasket()
	{
		$base64Basketdata = $_POST['basketdata'] ?? [];

		return MembershipCardForm::addToBasket($base64Basketdata);
	}

}
