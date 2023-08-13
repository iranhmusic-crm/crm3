<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\controllers;

use shopack\aaa\frontend\common\auth\BaseCrudController;
use iranhmusic\shopack\mha\common\enums\enuBasicDefinitionStatus;
use iranhmusic\shopack\mha\frontend\common\models\BasicDefinitionModel;
use iranhmusic\shopack\mha\frontend\common\models\BasicDefinitionSearchModel;

class BasicDefinitionController extends BaseCrudController
{
	public $modelClass = BasicDefinitionModel::class;
	public $searchModelClass = BasicDefinitionSearchModel::class;

	public function actionCreate_afterCreateModel(&$model)
  {
		$model->bdfStatus = enuBasicDefinitionStatus::Active;
  }

}
