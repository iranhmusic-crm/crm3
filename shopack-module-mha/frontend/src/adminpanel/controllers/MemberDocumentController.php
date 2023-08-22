<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\controllers;

use Yii;
use yii\web\Response;
use yii\web\BadRequestHttpException;
use shopack\base\common\helpers\Url;
use shopack\base\common\helpers\StringHelper;
use shopack\base\frontend\helpers\Html;
use shopack\aaa\frontend\common\auth\BaseCrudController;
use iranhmusic\shopack\mha\frontend\common\models\MemberDocumentModel;
use iranhmusic\shopack\mha\frontend\common\models\MemberDocumentSearchModel;
use iranhmusic\shopack\mha\common\enums\enuMemberDocumentStatus;

class MemberDocumentController extends BaseCrudController
{
	public $modelClass = MemberDocumentModel::class;
	public $searchModelClass = MemberDocumentSearchModel::class;
	public $modalDoneFragment = 'member-documents';

	public function init()
	{
		$this->doneLink = function ($model) {
			return Url::to(['/mha/member/view',
				'id' => $model->mbrdocMemberID,
				'fragment' => $this->modalDoneFragment,
				'anchor' => StringHelper::convertToJsVarName($model->primaryKeyValue()),
			]);
		};

		parent::init();
	}

  public function actionCreate_afterCreateModel(&$model)
  {
		$model->mbrdocMemberID = $_GET['mbrdocMemberID'] ?? null;
		$model->mbrdocStatus = enuMemberDocumentStatus::WaitForApprove;
		$model->mbrdocDocumentID = $_GET['docID'] ?? null;
  }

	public function actionApprove($id)
	{
    if (empty($_POST['confirmed']))
      throw new BadRequestHttpException('این عملیات باید تایید شده باشد');

		if (Yii::$app->request->isAjax == false)
			throw new BadRequestHttpException('It is not possible to execute this command in a mode other than Ajax');

    $model = $this->findModel($id);
    $model->mbrdocStatus = enuMemberDocumentStatus::Approved;
    $done = $model->save();

		return $this->renderJson([
			'status' => 'Ok',
			'message' => Yii::t('app', 'Success'),
			'modalDoneFragment' => $this->modalDoneFragment,
		]);
	}

	public function actionReject($id)
	{
		$model = $this->findModel($id);
		$model->mbrdocStatus = enuMemberDocumentStatus::Rejected;

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

      return $this->renderAjaxModal('_reject_form', [
        'model' => $model,
      ]);
    }

    if ($done)
      return $this->redirect(['view', 'id' => $model->primaryKeyValue()]);

    return $this->render('reject', [
      'model' => $model
    ]);
	}

}
