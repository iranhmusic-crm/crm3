<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\userpanel\controllers;

use Yii;
use yii\web\Response;
use shopack\base\common\helpers\Url;
use shopack\base\common\helpers\StringHelper;
use shopack\aaa\frontend\common\auth\BaseCrudController;
use iranhmusic\shopack\mha\frontend\common\models\MemberMasterInsDocModel;
use iranhmusic\shopack\mha\frontend\common\models\MemberMasterInsDocSearchModel;
use iranhmusic\shopack\mha\common\enums\enuInsurerDocStatus;
use shopack\base\frontend\helpers\Html;

class MemberMasterInsDocController extends BaseCrudController
{
	public $modelClass = MemberMasterInsDocModel::class;
	public $searchModelClass = MemberMasterInsDocSearchModel::class;
	// public $modalDoneFragment = 'member-master-ins-docs';

	public function init()
	{
		$this->doneLink = function ($model) {
			return Url::to(['index']);
		};

		parent::init();
	}

  public function actionCreate_afterCreateModel(&$model)
  {
		$model->mbrminsdocMemberID = Yii::$app->user->id;
		$model->mbrminsdocStatus = enuInsurerDocStatus::WaitForSurvey;
  }

	public function getSearchParams()
  {
    return array_replace_recursive(Yii::$app->request->queryParams, [
			'mbrminsdocMemberID' => Yii::$app->user->id,
		]);
  }

}
