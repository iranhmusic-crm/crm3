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
use iranhmusic\shopack\mha\frontend\common\models\MemberMembershipModel;
use iranhmusic\shopack\mha\frontend\common\models\MemberMembershipSearchModel;
use iranhmusic\shopack\mha\common\enums\enuMemberMembershipStatus;

class MemberMembershipController extends BaseCrudController
{
	public $modelClass = MemberMembershipModel::class;
	public $searchModelClass = MemberMembershipSearchModel::class;
	// public $modalDoneFragment = 'member-memberships';

	public function init()
	{
		$this->doneLink = function ($model) {
			return Url::to(['index']);
		};

		parent::init();
	}

  public function actionCreate_afterCreateModel(&$model)
  {
		$model->mbrshpMemberID = Yii::$app->user->id;
		$model->mbrshpStatus = enuMemberMembershipStatus::WaitForPay;
		// $model->mbrshpStartDate = date('Y-m-d');

		$model->prepareForCreation();
  }

	public function getSearchParams()
  {
    return array_replace_recursive(Yii::$app->request->queryParams, [
			'mbrshpMemberID' => Yii::$app->user->id,
		]);
  }

}
