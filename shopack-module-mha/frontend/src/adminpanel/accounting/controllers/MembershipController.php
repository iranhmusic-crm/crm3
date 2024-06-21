<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\accounting\controllers;

use Yii;
use yii\web\BadRequestHttpException;
use shopack\base\common\helpers\Url;
use shopack\base\common\helpers\StringHelper;
use shopack\aaa\frontend\common\auth\BaseController;
use shopack\aaa\frontend\common\models\OfflinePaymentModel;
use iranhmusic\shopack\mha\frontend\common\accounting\models\MembershipUserAssetModel;
use iranhmusic\shopack\mha\frontend\common\accounting\models\MembershipUserAssetSearchModel;
use iranhmusic\shopack\mha\frontend\adminpanel\accounting\models\RenewViaInvoiceForm;
use shopack\base\frontend\common\helpers\Html;

class MembershipController extends BaseController
{
	public function actionRenewViaInvoice(
    $memberID = null,
		$ofpID = null,
		$isPartial = false,
	) {
		$model = new RenewViaInvoiceForm();
    $model->memberID = $memberID;
		$model->ofpID = $ofpID;

		$formPosted = $model->load(Yii::$app->request->post());
		$done = false;
		if ($formPosted)
			$done = $model->process();

    if (Yii::$app->request->isAjax) {
      if ($done) {
        return $this->renderJson([
          'message' => Yii::t('app', 'Success'),
          'id' => $model->invoiceID,
          // 'redirect' => $this->doneLink ? call_user_func($this->doneLink, $model) : null,
          // 'modalDoneFragment' => $this->modalDoneFragment,
        ]);
      }

      if ($formPosted) {
        return $this->renderJson([
          'status' => 'Error',
          'message' => Yii::t('app', 'Error'),
          // 'id' => $id,
          'error' => Html::errorSummary($model),
        ]);
      }

			// return $this->renderJson($this->renderAjax(
      return $this->renderAjaxModal('_renew_form', [
				'model' => $model,
			]);

    } //if (isAjax)

    if ($done)
      return $this->redirect(['/aaa/invoice/view', 'id' => $model->invoiceID]);

    if ($isPartial)
      return $this->renderPartial('_renew_form', [
				'model' => $model,
			]);

		return $this->render('renew', [
			'model' => $model,
		]);
	}

}
