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
use iranhmusic\shopack\mha\backend\models\ReportModel;

class ReportController extends BaseRestController
{
	public function behaviors()
	{
		$behaviors = parent::behaviors();

		if (YII_ENV_DEV) {
			$behaviors[BaseRestController::BEHAVIOR_AUTHENTICATOR]['except'] = [
				'run',
			];
		}

		return $behaviors;
	}

	public function actionOptions()
	{
		return 'options';
	}

	protected function findModel($id)
	{
		if (($model = ReportModel::findOne($id)) !== null)
			return $model;

		throw new NotFoundHttpException('The requested item does not exist.');
	}

	public function actionIndex()
	{
		$filter = [];
		PrivHelper::checkPriv(['mha/Report/crud' => '0100']);

		$searchModel = new ReportModel;
		$query = $searchModel::find()
			->select(ReportModel::selectableColumns())
			->with('createdByUser')
			->with('updatedByUser')
			->with('removedByUser')
			->asArray()
		;

		$searchModel->fillQueryFromRequest($query);

		if (empty($filter) == false)
			$query->andWhere($filter);

		return $this->queryAllToResponse($query);
	}

	public function actionView($id)
	{
		PrivHelper::checkPriv(['mha/Report/crud' => '0100']);

		$model = ReportModel::find()
			->select(ReportModel::selectableColumns())
			->with('createdByUser')
			->with('updatedByUser')
			->with('removedByUser')
			->where(['rptID' => $id])
			->asArray()
			->one()
		;

		if ($model !== null)
			return $model;

		throw new NotFoundHttpException('The requested item does not exist.');

		// return RESTfulHelper::modelToResponse($this->findModel($id));
	}

	public function actionCreate()
	{
		PrivHelper::checkPriv(['mha/Report/crud' => '1000']);

		$model = new ReportModel();
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
				'rptID' => $model->rptID,
				'rptStatus' => $model->rptStatus,
				'rptCreatedAt' => $model->rptCreatedAt,
				'rptCreatedBy' => $model->rptCreatedBy,
			// ],
		];
	}

	public function actionUpdate($id)
	{
		PrivHelper::checkPriv(['mha/Report/crud' => '0010']);

		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->getBodyParams(), '') == false)
			throw new NotFoundHttpException("parameters not provided");

		if ($model->save() == false)
			throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

		return [
			// 'result' => [
				// 'message' => 'updated',
				'rptID' => $model->rptID,
				'rptStatus' => $model->rptStatus,
				'rptUpdatedAt' => $model->rptUpdatedAt,
				'rptUpdatedBy' => $model->rptUpdatedBy,
			// ],
		];
	}

	public function actionDelete($id)
	{
		PrivHelper::checkPriv(['mha/Report/crud' => '0001']);

		$model = $this->findModel($id);

		if ($model->delete() === false)
			throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

		return [
			// 'result' => [
				// 'message' => 'deleted',
				'rptID' => $model->rptID,
				'rptStatus' => $model->rptStatus,
				'rptRemovedAt' => $model->rptRemovedAt,
				'rptRemovedBy' => $model->rptRemovedBy,
			// ],
		];
	}

	public function actionRun($id)
	{
		if (YII_ENV_DEV == false) {
			PrivHelper::checkPriv(['mha/Report/crud' => '0100']);
		}

		$model = $this->findModel($id);
		$query = $model->run();

		$queryParams = Yii::$app->request->getQueryParams();
		$model->fillQueryOrderByPart($queryParams, $query);

		return $this->queryAllToResponse($query);
	}

}
