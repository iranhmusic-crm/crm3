<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\userpanel\controllers;

use Yii;
use yii\web\UnprocessableEntityHttpException;
use shopack\aaa\frontend\common\auth\BaseController;
use shopack\base\frontend\helpers\Html;
use iranhmusic\shopack\mha\frontend\common\models\MemberModel;
use iranhmusic\shopack\mha\frontend\userpanel\models\MemberSignupForm;

class MemberController extends BaseController
{
	public function actionSignup()
  {
		if (Yii::$app->member->isMember)
			throw new UnprocessableEntityHttpException(Yii::t('mha', 'You are already registered.'));

		//---
		$jwtPayload = Yii::$app->user->identity->jwtPayload;
		$mustApprove = $jwtPayload['mustApprove'] ?? '';
		$mustApprove_email = false;
		$mustApprove_mobile = false;
		if (isset($mustApprove)) {
			$mustApprove = ',' . $mustApprove . ',';
			$mustApprove_email = (strpos($mustApprove, ',email,') !== false);
			$mustApprove_mobile = (strpos($mustApprove, ',mobile,') !== false);
		}
		if ($mustApprove_email || $mustApprove_mobile)
			throw new UnprocessableEntityHttpException(Yii::t('aaa', 'Email and/or Mobile not approved.'));

		//---
		$model = new MemberSignupForm;
		$model->mbrUserID = Yii::$app->user->id;

		$formPosted = $model->load(Yii::$app->request->post());
		$done = false;
		if ($formPosted)
			$done = $model->save();

    if (Yii::$app->request->isAjax) {
      if ($done) {
        return $this->renderJson([
          'message' => Yii::t('app', 'Success'),
          // 'id' => $id,
          // 'redirect' => $this->doneLink ? call_user_func($this->doneLink, $model) : null,
          'modalDoneFragment' => $this->modalDoneFragment,
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

      return $this->renderAjaxModal('_form_signup', [
        'model' => $model,
      ]);
    }

    if ($done)
      return $this->goHome();

    return $this->render('signup', [
      'model' => $model,
    ]);
  }

  public function actionUpdate()
  {
		$model = MemberModel::findOne(Yii::$app->user->id);

		$formPosted = $model->load(Yii::$app->request->post());
		$done = false;
		if ($formPosted)
			$done = $model->save();

    if (Yii::$app->request->isAjax) {
      if ($done) {
        return $this->renderJson([
          'message' => Yii::t('app', 'Success'),
          // 'id' => $id,
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

      return $this->renderAjaxModal('_form', [
        'model' => $model,
      ]);
    }

    if ($done)
      return $this->redirect('/');

    return $this->render('update', [
      'model' => $model
    ]);
  }

}
