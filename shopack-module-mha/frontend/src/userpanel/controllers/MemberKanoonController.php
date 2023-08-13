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
use iranhmusic\shopack\mha\frontend\common\models\MemberKanoonModel;
use iranhmusic\shopack\mha\frontend\common\models\MemberKanoonSearchModel;
use iranhmusic\shopack\mha\common\enums\enuMemberKanoonStatus;

class MemberKanoonController extends BaseCrudController
{
	public $modelClass = MemberKanoonModel::class;
	public $searchModelClass = MemberKanoonSearchModel::class;
	// public $modalDoneFragment = 'member-kanoons';

	public function init()
	{
		$this->doneLink = function ($model) {
			return Url::to(['index']);
		};

		parent::init();
	}

  public function actionCreate_afterCreateModel(&$model)
  {
		$model->mbrknnMemberID = Yii::$app->user->id;
		$model->mbrknnStatus = enuMemberKanoonStatus::WaitForSend;
  }

  public function getSearchParams()
  {
    return array_replace_recursive(Yii::$app->request->queryParams, [
			'mbrknnMemberID' => Yii::$app->user->id,
		]);
  }

}
