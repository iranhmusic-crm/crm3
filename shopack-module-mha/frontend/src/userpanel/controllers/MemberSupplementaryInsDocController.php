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
use iranhmusic\shopack\mha\frontend\common\models\MemberSupplementaryInsDocModel;
use iranhmusic\shopack\mha\frontend\common\models\MemberSupplementaryInsDocSearchModel;
use iranhmusic\shopack\mha\common\enums\enuInsurerDocStatus;
use shopack\base\frontend\helpers\Html;

class MemberSupplementaryInsDocController extends BaseCrudController
{
	public $modelClass = MemberSupplementaryInsDocModel::class;
	public $searchModelClass = MemberSupplementaryInsDocSearchModel::class;
	// public $modalDoneFragment = 'member-supplementary-ins-docs';

	public function init()
	{
		$this->doneLink = function ($model) {
			return Url::to(['index']);
		};

		parent::init();
	}

  public function actionCreate_afterCreateModel(&$model)
  {
		$model->mbrsinsdocMemberID = Yii::$app->user->id;
		$model->mbrsinsdocStatus = enuInsurerDocStatus::WaitForSurvey;
  }

	public function getSearchParams()
  {
    return array_replace_recursive(Yii::$app->request->queryParams, [
			'mbrsinsdocMemberID' => Yii::$app->user->id,
		]);
  }

}
