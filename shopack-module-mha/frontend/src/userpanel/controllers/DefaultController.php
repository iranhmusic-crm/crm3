<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\userpanel\controllers;

use Yii;
use shopack\aaa\frontend\common\auth\BaseController;
use iranhmusic\shopack\mha\frontend\common\models\MemberModel;

class DefaultController extends BaseController
{
	public function actionIndex()
  {
		if (Yii::$app->member->isMember)
			return $this->render('member', [
				'model'=> Yii::$app->member->memberModel,
			]);

		return $this->render('notMember');
  }

}
