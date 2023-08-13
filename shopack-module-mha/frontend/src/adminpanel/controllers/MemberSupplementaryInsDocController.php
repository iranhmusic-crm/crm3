<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\controllers;

use Yii;
use yii\web\Response;
use shopack\base\common\helpers\Url;
use shopack\base\common\helpers\StringHelper;
use shopack\aaa\frontend\common\auth\BaseCrudController;
use iranhmusic\shopack\mha\frontend\common\models\MemberSupplementaryInsDocModel;
use iranhmusic\shopack\mha\frontend\common\models\MemberSupplementaryInsDocSearchModel;
use iranhmusic\shopack\mha\common\enums\enuInsurerDocStatus;
use shopack\base\frontend\helpers\Html;

class MemberSupplementaryInsDocController extends BaseCrudController
{
	public $modelClass = MemberSupplementaryInsDocModel::class;
	public $searchModelClass = MemberSupplementaryInsDocSearchModel::class;
	public $modalDoneFragment = 'member-supplementary-ins-docs';

	public function init()
	{
		$this->doneLink = function ($model) {
			return Url::to(['/mha/member/view',
				'id' => $model->mbrsinsdocMemberID,
				'fragment' => $this->modalDoneFragment,
				'anchor' => StringHelper::convertToJsVarName($model->primaryKeyValue()),
			]);
		};

		parent::init();
	}

  public function actionCreate_afterCreateModel(&$model)
  {
		$model->mbrsinsdocMemberID = $_GET['mbrsinsdocMemberID'] ?? null;
		$model->mbrsinsdocStatus = enuInsurerDocStatus::WaitForSurvey;
  }

	public function actionChangeStatus(
		$id,
		$status
	) {
		$model = $this->findModel($id);

		$model->mbrsinsdocStatus = $status;
		$done = $model->save();

    if ($done)
      return $this->redirect($this->doneLink ? call_user_func($this->doneLink, $model) : 'index');

    return $this->redirect(
      $this->doneLink ? call_user_func($this->doneLink, $model)
        : ['view', 'id' => $model->primaryKeyValue()]);
	}

}
