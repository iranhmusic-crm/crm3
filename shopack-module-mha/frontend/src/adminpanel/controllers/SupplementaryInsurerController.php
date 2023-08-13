<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\controllers;

use shopack\aaa\frontend\common\auth\BaseCrudController;
use iranhmusic\shopack\mha\frontend\common\models\SupplementaryInsurerModel;
use iranhmusic\shopack\mha\frontend\common\models\SupplementaryInsurerSearchModel;
use iranhmusic\shopack\mha\common\enums\enuInsurerStatus;

class SupplementaryInsurerController extends BaseCrudController
{
	public $modelClass = SupplementaryInsurerModel::class;
	public $searchModelClass = SupplementaryInsurerSearchModel::class;

}
