<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\controllers;

use shopack\aaa\frontend\common\auth\BaseCrudController;
use iranhmusic\shopack\mha\frontend\common\models\MasterInsurerModel;
use iranhmusic\shopack\mha\frontend\common\models\MasterInsurerSearchModel;
use iranhmusic\shopack\mha\common\enums\enuInsurerStatus;

class MasterInsurerController extends BaseCrudController
{
	public $modelClass = MasterInsurerModel::class;
	public $searchModelClass = MasterInsurerSearchModel::class;

}
