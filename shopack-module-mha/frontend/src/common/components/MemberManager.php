<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\components;

use Yii;
use yii\base\Component;
// use yii\web\NotFoundHttpException;
use iranhmusic\shopack\mha\frontend\common\models\MemberModel;

class MemberManager extends Component
{
	public function getMemberModel()
	{
		try {
			$memberModel = MemberModel::findOne([
				'mbrUserID' => Yii::$app->user->id,
			]);

			return $memberModel;
		} catch (\Throwable $th) {
		}

		return null;
	}

	public function getIsMember()
	{
		return ($this->memberModel != null);
	}

}
