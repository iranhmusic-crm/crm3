<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\controllers;

use Yii;
use yii\web\Response;
use shopack\base\common\helpers\HttpHelper;
use shopack\aaa\frontend\common\auth\BaseCrudController;
use iranhmusic\shopack\mha\frontend\common\models\MemberGroupModel;
use iranhmusic\shopack\mha\frontend\common\models\MemberGroupSearchModel;

class MemberGroupController extends BaseCrudController
{
	public $modelClass = MemberGroupModel::class;
	public $searchModelClass = MemberGroupSearchModel::class;

}
