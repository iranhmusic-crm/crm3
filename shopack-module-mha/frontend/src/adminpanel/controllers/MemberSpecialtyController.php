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
use iranhmusic\shopack\mha\frontend\common\models\SpecialtyModel;
use iranhmusic\shopack\mha\frontend\common\models\MemberSpecialtyModel;
use iranhmusic\shopack\mha\frontend\common\models\MemberSpecialtySearchModel;

class MemberSpecialtyController extends BaseCrudController
{
	public $modelClass = MemberSpecialtyModel::class;
	public $searchModelClass = MemberSpecialtySearchModel::class;
	public $modalDoneFragment = 'member-specialty';

	public function init()
	{
		$this->doneLink = function ($model) {
			return Url::to(['/mha/member/view',
				'id' => $model->mbrspcMemberID,
				'fragment' => $this->modalDoneFragment,
				'anchor' => StringHelper::convertToJsVarName($model->primaryKeyValue()),
			]);
		};

		parent::init();
	}

  public function actionCreate_afterCreateModel(&$model)
  {
		$model->mbrspcMemberID = $_GET['mbrspcMemberID'] ?? null;
		$model->form_specialties = SpecialtyModel::getAllAsTree();
  }

	public function actionUpdate_afterCreateModel(&$model)
  {
		$model->form_specialties = SpecialtyModel::getAllAsTree();
  }

}
