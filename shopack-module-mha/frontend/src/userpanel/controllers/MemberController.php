<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\userpanel\controllers;

use Yii;
use yii\web\UnprocessableEntityHttpException;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use shopack\aaa\frontend\common\auth\BaseController;
use shopack\aaa\frontend\common\models\UserModel;
use shopack\base\frontend\common\helpers\Html;
use shopack\base\common\helpers\Url;
use iranhmusic\shopack\mha\frontend\common\models\MemberModel;
use iranhmusic\shopack\mha\frontend\userpanel\models\MemberSignupForm;

class MemberController extends BaseController
{
  public function behaviors()
	{
		$behaviors = parent::behaviors();

		$behaviors[BaseController::BEHAVIOR_AUTHENTICATOR]['except'] = [
			'signup',
		];

    // $behaviors[BaseController::BEHAVIOR_VERBS]['actions']['signup'] = ['POST'];

		return $behaviors;
	}

  protected function findUserModel()
	{
		if (($model = UserModel::findOne(Yii::$app->user->id)) === null)
      throw new NotFoundHttpException('The requested item not exist.');

    return $model;
	}

  public function actionSignup()
  {
    if (Yii::$app->user->isGuest) {
      return $this->redirect(['/aaa/auth/login-by-mobile',
        'donelink' => Url::to(['/mha/member/signup']),
        'realm' => 'mha-signup',
        'signupIfNotExists' => 1,
      ]);
    }

    // Yii::$app->controller->layout = '/mha-signup';

		$userModel = $this->findUserModel();
    if ($userModel->isSoftDeleted())
      throw new BadRequestHttpException('این آیتم حذف شده است و قابل ویرایش نمی‌باشد.');

    // if (empty($userModel->usrEmail)) {
    // }

		if (Yii::$app->member->isMember)
      return $this->goHome();
    // throw new UnprocessableEntityHttpException(Yii::t('mha', 'You are already registered.'));

		//---------------------------
		$model = new MemberSignupForm;
		$model->mbrUserID = $userModel->usrID;

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

	public function actionOLDSignup()
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

		//---------------------------
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
