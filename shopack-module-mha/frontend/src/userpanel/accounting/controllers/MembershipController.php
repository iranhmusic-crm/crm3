<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\userpanel\accounting\controllers;

use Yii;
use shopack\base\common\helpers\Url;
use shopack\base\frontend\common\helpers\Html;
use shopack\aaa\frontend\common\auth\BaseController;
use iranhmusic\shopack\mha\frontend\userpanel\accounting\models\MembershipForm;
use iranhmusic\shopack\mha\frontend\common\accounting\models\MembershipUserAssetSearchModel;
use shopack\base\common\accounting\enums\enuUserAssetStatus;

class MembershipController extends BaseController
{
	public function actionIndex()
  {
    $searchModel = new MembershipUserAssetSearchModel();
		$dataProvider = $searchModel->search(array_replace_recursive(
			Yii::$app->request->queryParams, [
				'uasActorID' => Yii::$app->user->id,
				'uasStatus' => enuUserAssetStatus::Active,
			]));

    $viewParams = [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		];

		if (Yii::$app->request->isAjax)
			return $this->renderJson($this->renderAjax('_index', $viewParams));

    // if ($isPartial) {
    //   return $this->renderPartial('_index', $viewParams);
    // }

    return $this->render('index', $viewParams);
  }

	public function actionAddToBasket()
  {
		$model = new MembershipForm();

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
			if ($done !== false) {
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
