<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\accounting\controllers;

use Yii;
use yii\web\UnprocessableEntityHttpException;
use shopack\base\common\helpers\Url;
use shopack\base\common\helpers\StringHelper;
use iranhmusic\shopack\mha\frontend\common\accounting\models\MembershipUserAssetModel;
use iranhmusic\shopack\mha\frontend\common\accounting\models\MembershipUserAssetSearchModel;
use shopack\aaa\frontend\common\auth\BaseController;

class MembershipController extends BaseController
{
	public function actionRenewByOfflinePayment($ofpid)
	{
		return $this->renderAjaxModal('_renew_form', [
			// 'model' => $model,
		]);
	}

}
