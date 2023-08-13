<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\controllers;

use shopack\aaa\frontend\common\auth\BaseCrudController;
use iranhmusic\shopack\mha\frontend\common\models\DocumentModel;
use iranhmusic\shopack\mha\frontend\common\models\DocumentSearchModel;
use iranhmusic\shopack\mha\common\enums\enuDocumentStatus;

class DocumentController extends BaseCrudController
{
	public $modelClass = DocumentModel::class;
	public $searchModelClass = DocumentSearchModel::class;

	public function actionCreate_afterCreateModel(&$model)
  {
		$model->docStatus = enuDocumentStatus::Active;
  }

}
