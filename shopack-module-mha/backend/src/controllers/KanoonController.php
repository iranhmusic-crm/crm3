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
use shopack\base\backend\controller\BaseRestController;
use shopack\base\backend\helpers\PrivHelper;
use iranhmusic\shopack\mha\backend\models\KanoonModel;
use iranhmusic\shopack\mha\backend\models\KanoonSendMessageForm;

class KanoonController extends BaseRestController
{
	// public function behaviors()
	// {
	// 	$behaviors = parent::behaviors();
	// 	return $behaviors;
	// }

	public function actionOptions()
	{
		return 'options';
	}

	protected function findModel($id)
	{
		if (($model = KanoonModel::findOne($id)) !== null)
			return $model;

		throw new NotFoundHttpException('The requested item not exist.');
	}

	public function actionIndex()
	{
		$filter = [];
		// PrivHelper::checkPriv('mha/kanoon/crud', '0100');

		$searchModel = new KanoonModel;
		$query = $searchModel::find()
			->select(KanoonModel::selectableColumns())
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
			->asArray()
		;

		$searchModel->fillQueryFromRequest($query);

		if (empty($filter) == false)
			$query->andWhere($filter);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if (Yii::$app->request->getMethod() == 'HEAD') {
			$totalCount = $dataProvider->getTotalCount();
			// $totalCount = $query->count();
			Yii::$app->response->headers->add('X-Pagination-Total-Count', $totalCount);
			return [];
		}

		return [
			'data' => $dataProvider->getModels(),
			// 'pagination' => [
			// 	'totalCount' => $totalCount,
			// ],
		];
	}

	public function actionView($id)
	{
		// PrivHelper::checkPriv('mha/kanoon/crud', '0100');

		$model = KanoonModel::find()
			->select(KanoonModel::selectableColumns())
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
			->where(['knnID' => $id])
			->asArray()
			->one()
		;

		if ($model !== null)
			return $model;

		throw new NotFoundHttpException('The requested item not exist.');

		// return RESTfulHelper::modelToResponse($this->findModel($id));
	}

	public function actionCreate()
	{
		PrivHelper::checkPriv('mha/kanoon/crud', '1000');

		$model = new KanoonModel();
		if ($model->load(Yii::$app->request->getBodyParams(), '') == false)
			throw new NotFoundHttpException("parameters not provided");

		try {
			if ($model->save() == false)
				throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));
		} catch(\Exception $exp) {
			$msg = ExceptionHelper::CheckDuplicate($exp, $model);
			throw new UnprocessableEntityHttpException($msg);
		}

		return [
			// 'result' => [
				// 'message' => 'created',
				'knnID' => $model->knnID,
				'knnStatus' => $model->knnStatus,
				'knnCreatedAt' => $model->knnCreatedAt,
				'knnCreatedBy' => $model->knnCreatedBy,
			// ],
		];
	}

	public function actionUpdate($id)
	{
		PrivHelper::checkPriv('mha/kanoon/crud', '0010');

		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->getBodyParams(), '') == false)
			throw new NotFoundHttpException("parameters not provided");

		if ($model->save() == false)
			throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

		return [
			// 'result' => [
				// 'message' => 'updated',
				'knnID' => $model->knnID,
				'knnStatus' => $model->knnStatus,
				'knnUpdatedAt' => $model->knnUpdatedAt,
				'knnUpdatedBy' => $model->knnUpdatedBy,
			// ],
		];
	}

	public function actionDelete($id)
	{
		PrivHelper::checkPriv('mha/kanoon/crud', '0001');

		$model = $this->findModel($id);

		if ($model->delete() === false)
			throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

		return [
			// 'result' => [
				// 'message' => 'deleted',
				'knnID' => $model->knnID,
				'knnStatus' => $model->knnStatus,
				'knnRemovedAt' => $model->knnRemovedAt,
				'knnRemovedBy' => $model->knnRemovedBy,
			// ],
		];
	}

	public function actionSendMessage()
	{
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
