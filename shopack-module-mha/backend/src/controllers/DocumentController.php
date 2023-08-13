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
use iranhmusic\shopack\mha\backend\models\DocumentModel;

class DocumentController extends BaseRestController
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
		if (($model = DocumentModel::findOne($id)) !== null)
			return $model;

		throw new NotFoundHttpException('The requested item not exist.');
	}

	public function actionIndex()
	{
		$filter = [];
		// PrivHelper::checkPriv('mha/document/crud', '0100');

		$searchModel = new DocumentModel;
		$query = $searchModel::find()
			->select(DocumentModel::selectableColumns())
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
		// PrivHelper::checkPriv('mha/document/crud', '0100');

		$model = DocumentModel::find()
			->select(DocumentModel::selectableColumns())
			->with('createdByUser')
			->with('updatedByUser')
			->with('removedByUser')
			->where(['docID' => $id])
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
		PrivHelper::checkPriv('mha/document/crud', '1000');

		$model = new DocumentModel();
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
				'docID' => $model->docID,
				'docStatus' => $model->docStatus,
				'docCreatedAt' => $model->docCreatedAt,
				'docCreatedBy' => $model->docCreatedBy,
			// ],
		];
	}

	public function actionUpdate($id)
	{
		PrivHelper::checkPriv('mha/document/crud', '0010');

		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->getBodyParams(), '') == false)
			throw new NotFoundHttpException("parameters not provided");

		if ($model->save() == false)
			throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

		return [
			// 'result' => [
				// 'message' => 'updated',
				'docID' => $model->docID,
				'docStatus' => $model->docStatus,
				'docUpdatedAt' => $model->docUpdatedAt,
				'docUpdatedBy' => $model->docUpdatedBy,
			// ],
		];
	}

	public function actionDelete($id)
	{
		PrivHelper::checkPriv('mha/document/crud', '0001');

		$model = $this->findModel($id);

		if ($model->delete() === false)
			throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

		return [
			// 'result' => [
				// 'message' => 'deleted',
				'docID' => $model->docID,
				'docStatus' => $model->docStatus,
				'docRemovedAt' => $model->docRemovedAt,
				'docRemovedBy' => $model->docRemovedBy,
			// ],
		];
	}

	public function actionMemberDocumentTypes($memberID)
	{
		if (PrivHelper::hasPriv('mha/document/crud', '0100') == false) {
			if (Yii::$app->user->id != $memberID)
				throw new ForbiddenHttpException('access denied');
		}

		$searchModel = new DocumentModel;
		$query = $searchModel::find()
			->select(DocumentModel::selectableColumns())
			->with('createdByUser')
			->with('updatedByUser')
			->with('removedByUser')
			->asArray()
		;

		$query
			->addSelect('tmpmbrdoc.cnt AS providedCount')
			->leftJoin("(
		SELECT mbrdocMemberID
				 , mbrdocDocumentID
				 , COUNT(*) AS cnt
			FROM tbl_MHA_Member_Document mbrdoc
		 WHERE mbrdocStatus != 'R'
	GROUP BY mbrdocMemberID
				 , mbrdocDocumentID
					 ) AS tmpmbrdoc",
			"tmpmbrdoc.mbrdocDocumentID = tbl_MHA_Document.docID "
			. " AND tmpmbrdoc.mbrdocMemberID = {$memberID}")
		;

		$searchModel->fillQueryFromRequest($query);

		// if (empty($filter) == false)
		// 	$query->andWhere($filter);

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

}
