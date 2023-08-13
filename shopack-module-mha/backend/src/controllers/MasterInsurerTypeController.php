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
use iranhmusic\shopack\mha\backend\models\MasterInsurerTypeModel;

class MasterInsurerTypeController extends BaseRestController
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
		if (($model = MasterInsurerTypeModel::findOne($id)) !== null)
			return $model;

		throw new NotFoundHttpException('The requested item not exist.');
	}

	public function actionIndex()
	{
		$filter = [];
		// PrivHelper::checkPriv('mha/master-insurer-type/crud', '0100');

		$searchModel = new MasterInsurerTypeModel;
		$query = $searchModel::find()
			->select(MasterInsurerTypeModel::selectableColumns())
			->joinWith('masterInsurer')
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
		// PrivHelper::checkPriv('mha/master-insurer-type/crud', '0100');

		$model = MasterInsurerTypeModel::find()
			->select(MasterInsurerTypeModel::selectableColumns())
			->joinWith('masterInsurer')
			->with('createdByUser')
			->with('updatedByUser')
			->with('removedByUser')
			->where(['minstypID' => $id])
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
		PrivHelper::checkPriv('mha/master-insurer-type/crud', '1000');

		$model = new MasterInsurerTypeModel();
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
				'minstypID' => $model->minstypID,
				// 'minstypStatus' => $model->minstypStatus,
				'minstypCreatedAt' => $model->minstypCreatedAt,
				'minstypCreatedBy' => $model->minstypCreatedBy,
			// ],
		];
	}

	public function actionUpdate($id)
	{
		PrivHelper::checkPriv('mha/master-insurer-type/crud', '0010');

		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->getBodyParams(), '') == false)
			throw new NotFoundHttpException("parameters not provided");

		if ($model->save() == false)
			throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

		return [
			// 'result' => [
				// 'message' => 'updated',
				'minstypID' => $model->minstypID,
				'minstypStatus' => $model->minstypStatus,
				'minstypUpdatedAt' => $model->minstypUpdatedAt,
				'minstypUpdatedBy' => $model->minstypUpdatedBy,
			// ],
		];
	}

	public function actionDelete($id)
	{
		PrivHelper::checkPriv('mha/master-insurer-type/crud', '0001');

		$model = $this->findModel($id);

		if ($model->delete() === false)
			throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

		return [
			// 'result' => [
				// 'message' => 'deleted',
				'minstypID' => $model->minstypID,
				'minstypStatus' => $model->minstypStatus,
				'minstypRemovedAt' => $model->minstypRemovedAt,
				'minstypRemovedBy' => $model->minstypRemovedBy,
			// ],
		];
	}

}
