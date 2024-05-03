<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\accounting\controllers;

use Yii;
use yii\web\Response;
use yii\web\UnprocessableEntityHttpException;
use shopack\base\common\helpers\Url;
use shopack\base\common\helpers\StringHelper;
use shopack\base\frontend\common\helpers\Html;
use shopack\aaa\frontend\common\auth\BaseCrudController;
use iranhmusic\shopack\mha\frontend\common\accounting\models\MembershipUserAssetModel;
use iranhmusic\shopack\mha\frontend\common\accounting\models\MembershipUserAssetSearchModel;
use iranhmusic\shopack\mha\frontend\common\models\MemberModel;

// use iranhmusic\shopack\mha\common\enums\enuMemberMembershipStatus;

class MembershipUserAssetController extends BaseCrudController
{
	public $modelClass = MembershipUserAssetModel::class;
	public $searchModelClass = MembershipUserAssetSearchModel::class;
	public $modalDoneFragment = 'member-memberships';

	public function init()
	{
		$this->doneLink = function ($model) {
			return Url::to(['/mha/member/view',
				'id' => $model->uasActorID,
				'fragment' => $this->modalDoneFragment,
				'anchor' => StringHelper::convertToJsVarName($model->primaryKeyValue()),
			]);
		};

		parent::init();
	}

  public function actionCreate_afterCreateModel(&$model)
  {
		$model->uasActorID = $_GET['uasActorID'] ?? null;
		// $model->mbrshpStatus = enuMemberMembershipStatus::WaitForPay;
		// $model->mbrshpStartDate = date('Y-m-d');
	}

	public function actionCreate_afterLoadModel(&$model)
  {
		if (empty($model->actor->mbrAcceptedAt)) {
			throw new UnprocessableEntityHttpException(Yii::t('mha', 'Membership start date is blank'));
		}

  }

}
