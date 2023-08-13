<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\controllers;

use Yii;
use yii\web\Response;
use shopack\base\common\helpers\Url;
use shopack\base\common\helpers\StringHelper;
use shopack\base\common\helpers\HttpHelper;
use shopack\aaa\frontend\common\auth\BaseCrudController;
use iranhmusic\shopack\mha\frontend\common\models\MemberMasterInsuranceModel;
use iranhmusic\shopack\mha\frontend\common\models\MemberMasterInsuranceSearchModel;

class MemberMasterInsuranceController extends BaseCrudController
{
	public $modelClass = MemberMasterInsuranceModel::class;
	public $searchModelClass = MemberMasterInsuranceSearchModel::class;
	public $modalDoneFragment = 'member-master-insurances';

	public function init()
	{
		$this->doneLink = function ($model) {
			return Url::to(['/mha/member/view',
				'id' => $model->mbrminshstMemberID,
				'fragment' => $this->modalDoneFragment,
				'anchor' => StringHelper::convertToJsVarName($model->primaryKeyValue()),
			]);
		};

		parent::init();
	}

  public function actionCreate_afterCreateModel(&$model)
  {
		$model->mbrminshstMemberID = $_GET['mbrminshstMemberID'] ?? null;
  }

}
