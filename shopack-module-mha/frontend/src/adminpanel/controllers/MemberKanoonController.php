<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\controllers;

use Yii;
use yii\web\Response;
use shopack\base\common\helpers\Url;
use shopack\base\common\helpers\StringHelper;
use shopack\base\frontend\helpers\Html;
use shopack\aaa\frontend\common\auth\BaseCrudController;
use iranhmusic\shopack\mha\frontend\common\models\MemberKanoonModel;
use iranhmusic\shopack\mha\frontend\common\models\MemberKanoonSearchModel;
use iranhmusic\shopack\mha\common\enums\enuMemberKanoonStatus;

class MemberKanoonController extends BaseCrudController
{
	public $modelClass = MemberKanoonModel::class;
	public $searchModelClass = MemberKanoonSearchModel::class;
	public $modalDoneFragment = 'member-kanoons';

	public function init()
	{
		$this->doneLink = function ($model) {
			return Url::to(['/mha/member/view',
				'id' => $model->mbrknnMemberID,
				'fragment' => $this->modalDoneFragment,
				'anchor' => StringHelper::convertToJsVarName($model->primaryKeyValue()),
			]);
		};

		parent::init();
	}

  public function actionCreate_afterCreateModel(&$model)
  {
		$model->mbrknnMemberID = $_GET['mbrknnMemberID'] ?? null;
		$model->mbrknnStatus = enuMemberKanoonStatus::WaitForSend;
  }

	public function actionChangeStatus(
		$id,
		$status
	) {
		$model = $this->findModel($id);

		$model->mbrknnStatus = $status;
		$done = $model->save();

    if ($done)
      return $this->redirect($this->doneLink ? call_user_func($this->doneLink, $model) : 'index');

    return $this->redirect(
      $this->doneLink ? call_user_func($this->doneLink, $model)
        : ['view', 'id' => $model->primaryKeyValue()]);
	}

	public function actionAccept($id)
	{
		$model = $this->findModel($id);
		$model->mbrknnStatus = enuMemberKanoonStatus::Accepted;

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

      return $this->renderAjaxModal('_accept_form', [
        'model' => $model,
      ]);
    }

    if ($done)
      return $this->redirect(['view', 'id' => $model->primaryKeyValue()]);

    return $this->render('accept', [
      'model' => $model
    ]);
	}

}
