<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\controllers;

use shopack\base\common\helpers\Url;
use shopack\base\common\helpers\StringHelper;
use shopack\aaa\frontend\common\auth\BaseCrudController;
use iranhmusic\shopack\mha\frontend\common\models\MemberMemberGroupModel;
use iranhmusic\shopack\mha\frontend\common\models\MemberMemberGroupSearchModel;

class MemberMemberGroupController extends BaseCrudController
{
	public $modelClass = MemberMemberGroupModel::class;
	public $searchModelClass = MemberMemberGroupSearchModel::class;
	public $modalDoneFragment = 'member-member-groups';

	public function init()
	{
		$this->doneLink = function ($model) {
			return Url::to(['/mha/member/view',
				'id' => $model->mbrmgpMemberID,
				'fragment' => $this->modalDoneFragment,
				'anchor' => StringHelper::convertToJsVarName($model->primaryKeyValue()),
			]);
		};

		parent::init();
	}

	public function actionCreate_afterCreateModel(&$model)
  {
		$model->mbrmgpMemberID = $_GET['mbrmgpMemberID'] ?? null;
  }

}
