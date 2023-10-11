<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\accounting\controllers;

use shopack\base\common\helpers\Url;
use shopack\base\common\helpers\StringHelper;
use shopack\aaa\frontend\common\auth\BaseCrudController;
use iranhmusic\shopack\mha\frontend\common\accounting\models\MembershipCardSaleableModel;
use iranhmusic\shopack\mha\frontend\common\accounting\models\MembershipCardSaleableSearchModel;
// use iranhmusic\shopack\mha\common\enums\enuCardStatus;

class MembershipCardSaleableController extends BaseCrudController
{
	public $modelClass = MembershipCardSaleableModel::class;
	public $searchModelClass = MembershipCardSaleableSearchModel::class;
	public $modalDoneFragment = 'saleables';

	public function init()
	{
		$this->doneLink = function ($model) {
			return Url::to(['membership-card-product/view',
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
