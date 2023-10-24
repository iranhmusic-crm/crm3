<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\accounting\controllers;

use shopack\base\common\helpers\Url;
use shopack\base\common\helpers\StringHelper;
use shopack\aaa\frontend\common\auth\BaseCrudController;
use iranhmusic\shopack\mha\frontend\common\accounting\models\MembershipSaleableModel;
use iranhmusic\shopack\mha\frontend\common\accounting\models\MembershipSaleableSearchModel;
// use iranhmusic\shopack\mha\common\enums\enuMembershipStatus;

class MembershipSaleableController extends BaseCrudController
{
	public $modelClass = MembershipSaleableModel::class;
	public $searchModelClass = MembershipSaleableSearchModel::class;
	public $modalDoneFragment = 'saleables';

	public function init()
	{
		$this->doneLink = function ($model) {
			return Url::to(['membership-product/view',
				'id' => $model->slbProductID,
				'fragment' => $this->modalDoneFragment,
				'anchor' => StringHelper::convertToJsVarName($model->primaryKeyValue()),
			]);
		};

		parent::init();
	}

	public function actionCreate_afterCreateModel(&$model)
  {
		$model->slbProductID = $_GET['slbProductID'] ?? null;
  }

}
