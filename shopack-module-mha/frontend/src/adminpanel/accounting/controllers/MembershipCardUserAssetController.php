<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\accounting\controllers;

use Yii;
use yii\web\Response;
use shopack\base\common\helpers\Url;
use shopack\base\common\helpers\StringHelper;
use shopack\base\frontend\common\helpers\Html;
use shopack\aaa\frontend\common\auth\BaseCrudController;
use iranhmusic\shopack\mha\frontend\common\accounting\models\MembershipCardUserAssetModel;
use iranhmusic\shopack\mha\frontend\common\accounting\models\MembershipCardUserAssetSearchModel;
// use iranhmusic\shopack\mha\common\enums\enuMemberMembershipStatus;

class MembershipCardUserAssetController extends BaseCrudController
{
	public $modelClass = MembershipCardUserAssetModel::class;
	public $searchModelClass = MembershipCardUserAssetSearchModel::class;
	public $modalDoneFragment = 'membership-cards';

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

}
