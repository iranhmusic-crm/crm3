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
use shopack\aaa\frontend\common\auth\BaseCrudController;
use iranhmusic\shopack\mha\frontend\common\models\MemberDocumentModel;
use iranhmusic\shopack\mha\frontend\common\models\MemberDocumentSearchModel;
use iranhmusic\shopack\mha\common\enums\enuMemberDocumentStatus;

class MemberDocumentController extends BaseCrudController
{
	public $modelClass = MemberDocumentModel::class;
	public $searchModelClass = MemberDocumentSearchModel::class;
	// public $modalDoneFragment = 'member-documents';

	public function init()
	{
		$this->doneLink = function ($model) {
			return Url::to(['index']);
		};

		parent::init();
	}

  public function actionCreate_afterCreateModel(&$model)
  {
		$model->mbrdocMemberID = Yii::$app->user->id;
		$model->mbrdocStatus = enuMemberDocumentStatus::WaitForApprove;
		$model->mbrdocDocumentID = $_GET['docID'] ?? null;
  }

	public function getSearchParams()
  {
    return array_replace_recursive(Yii::$app->request->queryParams, [
			'mbrdocMemberID' => Yii::$app->user->id,
		]);
  }

}
