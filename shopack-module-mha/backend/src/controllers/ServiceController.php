<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\controllers;

use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii\data\ActiveDataProvider;
use shopack\base\common\helpers\Json;
use shopack\base\backend\controller\BaseRestController;
use shopack\base\backend\helpers\PrivHelper;
use shopack\base\common\security\RsaPrivate;
use iranhmusic\shopack\mha\backend\models\MembershipModel;

class ServiceController extends BaseRestController
{
	public function behaviors()
	{
		$behaviors = parent::behaviors();

		$behaviors[BaseRestController::BEHAVIOR_AUTHENTICATOR]['except'] = [
			'process-voucher-item',
		];


		return $behaviors;
	}

	public function actionOptions()
	{
		return 'options';
	}

	public function actionProcessVoucherItem()
	{
		$voucherID = $_POST['vchid'];
		$data = $_POST['data'];

		if (empty(Yii::$app->controller->module->servicePrivateKey))
			$data = base64_decode($data);
		else
			$data = RsaPrivate::model(Yii::$app->controller->module->servicePrivateKey)->decrypt($data);

		$data = Json::decode($data);

		$slbkey = $data['slbkey'];

		if ($slbkey == MembershipModel::saleableKey()) {
			return MembershipModel::ProcessVoucherItem($voucherID, $data);
		}

		throw new UnprocessableEntityHttpException("Invalid saleable key ({$slbkey})");
	}

}
