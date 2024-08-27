<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\controllers;

use Yii;
use shopack\base\common\helpers\Json;
use shopack\base\backend\controller\BaseRestController;
use shopack\base\common\security\RsaPrivate;
use iranhmusic\shopack\mha\backend\accounting\models\SaleableModel;

class ServiceController extends BaseRestController
{
	public function behaviors()
	{
		$behaviors = parent::behaviors();

		$behaviors[static::BEHAVIOR_AUTHENTICATOR]['except'] = [
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
		$bodyParams = Yii::$app->request->getBodyParams();

		$voucherID = $bodyParams['vchid'];
		$data = $bodyParams['data'];

		if (empty(Yii::$app->controller->module->servicePrivateKey))
			$data = base64_decode($data);
		else
			$data = RsaPrivate::model(Yii::$app->controller->module->servicePrivateKey)->decrypt($data);
		$data = Json::decode($data);

		$userid = $bodyParams['userid'];

		SaleableModel::ProcessVoucherItem($voucherID, $userid, $data);
	}

}
