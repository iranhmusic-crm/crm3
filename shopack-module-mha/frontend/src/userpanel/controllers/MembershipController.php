<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\userpanel\controllers;

use Yii;
use yii\web\Response;
use shopack\base\common\helpers\Url;
use shopack\base\common\helpers\StringHelper;
use shopack\base\frontend\helpers\Html;
use shopack\aaa\frontend\common\auth\BaseController;
use iranhmusic\shopack\mha\frontend\common\models\MembershipModel;
use iranhmusic\shopack\mha\frontend\common\models\MemberMembershipModel;
use iranhmusic\shopack\mha\frontend\common\models\MemberMembershipSearchModel;
use iranhmusic\shopack\mha\common\enums\enuMemberMembershipStatus;
use shopack\base\common\shop\ISaleableController;
// use iranhmusic\shopack\mha\frontend\common\models\BasketModel;
use iranhmusic\shopack\mha\frontend\userpanel\models\MembershipBasketForm;

class MembershipController extends BaseController implements ISaleableController
{
	//ISaleableController:
  public function actionAddToBasket()
  {
		$model = new MembershipBasketForm();

		try {
			$formPosted = $model->load($_POST);
		} catch (\Throwable $th) {
			if (Yii::$app->request->isAjax) {
				return $this->renderAjaxModal('_error', [
					'error' => Yii::t('mha', $th->getMessage()),
				]);
			}

			throw $th;
		}

		$done = false;
		if ($formPosted)
			$done = $model->addToBasket($_POST['basketdata'] ?? null);

		if (Yii::$app->request->isAjax) {
			if ($done != false) {
				return $this->renderJson([
					'message' => Yii::t('app', 'Success'),
					'redirect' => Url::to(['/aaa/basket']),
					// 'basketdata' => $done,
				]);
			}

			if ($formPosted) {
				return $this->renderJson([
					'status' => 'Error',
					'message' => Yii::t('app', 'Error'),
					'error' => Html::errorSummary($model),
				]);
			}

			return $this->renderAjaxModal('_form', [
				'model' => $model,
			]);
		}

		// if ($done)
		// 	return $this->redirect(['view', 'id' => $model->primaryKeyValue()]);

		return $this->render('create', [
			'model' => $model,
		]);
  }

}
