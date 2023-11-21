<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\controllers;

use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii\data\ActiveDataProvider;
use shopack\base\common\helpers\ExceptionHelper;
use shopack\base\backend\controller\BaseCrudController;
use shopack\base\backend\helpers\PrivHelper;
use iranhmusic\shopack\mha\backend\models\KanoonModel;
use iranhmusic\shopack\mha\backend\models\KanoonSendMessageForm;

class KanoonController extends BaseCrudController
{
	public function behaviors()
	{
		$behaviors = parent::behaviors();

		$behaviors[static::BEHAVIOR_AUTHENTICATOR]['except'] = [
			'index',
			'view',
		];

		return $behaviors;
	}

	public $modelClass = \iranhmusic\shopack\mha\backend\models\KanoonModel::class;

	public function permissions()
	{
		return [
			// 'index'  => ['mha/kanoon/crud', '0100'],
			// 'view'   => ['mha/kanoon/crud', '0100'],
			'create' => ['mha/kanoon/crud', '1000'],
			'update' => ['mha/kanoon/crud', '0010'],
			'delete' => ['mha/kanoon/crud', '0001'],
		];
	}

	public function queryAugmentaters()
	{
		return [
			'index' => function($query) {
				$query
					->joinWith('president')
					->joinWith('vicePresident')
					->joinWith('ozv1')
					->joinWith('ozv2')
					->joinWith('ozv3')
					->joinWith('warden')
					->joinWith('talker')
					->with('createdByUser')
					->with('updatedByUser')
					->with('removedByUser')
				;
			},
			'view' => function($query) {
				$query
					->joinWith('president')
					->joinWith('vicePresident')
					->joinWith('ozv1')
					->joinWith('ozv2')
					->joinWith('ozv3')
					->joinWith('warden')
					->joinWith('talker')
					->with('createdByUser')
					->with('updatedByUser')
					->with('removedByUser')
				;
			},
		];
	}

	public function fillGlobalSearchFromRequest(\yii\db\ActiveQuery $query, $q)
	{
		if (empty($q) || ($q == '***'))
			return;

		$query->andWhere([
			'OR',
			['LIKE', 'knnName', $q],
		]);
	}

	public function actionSendMessage()
	{
		PrivHelper::checkPriv(['mha/kanoon/send-message']);

		$model = new KanoonSendMessageForm();

		if ($model->load(Yii::$app->request->getBodyParams(), '') == false)
			throw new NotFoundHttpException("parameters not provided");

		try {
			$result = $model->process();

			if ($result === false)
				throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

			return $result;

		} catch(\Exception $exp) {
			$msg = ExceptionHelper::CheckDuplicate($exp, $model);
			throw new UnprocessableEntityHttpException($msg);
		}
	}

}
