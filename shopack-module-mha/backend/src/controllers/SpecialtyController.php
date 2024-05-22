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
use iranhmusic\shopack\mha\backend\models\SpecialtyModel;

class SpecialtyController extends BaseRestController
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
		if (($model = SpecialtyModel::findOne($id)) !== null)
			return $model;

		throw new NotFoundHttpException('The requested item does not exist.');
	}

	public function actionIndex(
		$parentid = null,
		$q = null
	) {
		// PrivHelper::checkPriv(['mha/specialty/crud' => '0100']);

		$tableName = SpecialtyModel::tableName();
		$nodeAlias = $tableName; //'node';

		if (empty($q) == false) {
			$query = SpecialtyModel::find();
			if ($q != '***')
				$query->andWhere(['like', 'LOWER(' . $nodeAlias . '.spcName)', trim(strtolower($q))]);
		} else if ($parentid == null)
			$query = SpecialtyModel::find()
				->andWhere($nodeAlias . '.spcID = ' . $nodeAlias . '.spcRoot');
		else {
			// $model = $this->findModel($parentid);
			$model = SpecialtyModel::find()
				->andWhere([$nodeAlias . '.spcID' => $parentid])
				->one();

			// $query = $model->children(1);

			$condition = [
				'and',
				['>', $nodeAlias . '.spcLeft', $model->spcLeft],
				['<', $nodeAlias . '.spcRight', $model->spcRight],
			];
			$condition[] = ['<=', $nodeAlias . '.spcLevel', $model->spcLevel + 1];
			$condition = [
        'and',
        $condition,
        [$nodeAlias . '.spcRoot' => $model->spcRoot]
			];
			$query = SpecialtyModel::find()
				->andWhere($condition)
			;
		}

		$query = $query
			->addOrderBy([$nodeAlias . '.spcLeft' => SORT_ASC])
		;

		$query = $query
			// ->select(SpecialtyModel::selectableColumns($nodeAlias))
			// ->with('createdByUser')
			// ->with('updatedByUser')
			// ->with('removedByUser')
			->asArray()
		;

		// // fullName
		// $query
		// 	->addSelect(new \yii\db\Expression("CONCAT(REPEAT('.    ', " . $nodeAlias . ".spcLevel), GROUP_CONCAT(parent.spcName ORDER BY parent.spcLeft SEPARATOR ' >> ')) AS fullName"))
		// 	->join('CROSS JOIN', $tableName . ' parent')
		// 	->andWhere($nodeAlias . '.spcRoot = parent.spcRoot')
		// 	->andWhere($nodeAlias . '.spcLeft BETWEEN parent.spcLeft AND parent.spcRight')
		// 	// ->andWhere("node.spcID = {$this->spcID}")
		// 	->groupBy($nodeAlias . '.spcID')
		// 	->orderBy([$nodeAlias . '.spcRoot' => SORT_ASC, 'parent.spcLeft' => SORT_ASC]);
		// ;

		$searchModel = new SpecialtyModel;
		$searchModel->fillQueryFromRequest($query);

		// if (empty($filter) == false)
		// 	$query->andWhere($filter);

		return $this->queryAllToResponse($query);
	}

	public function actionView($id)
	{
		// PrivHelper::checkPriv(['mha/specialty/crud' => '0100']);

		$tableName = SpecialtyModel::tableName();
		$nodeAlias = $tableName; //'node';

		$model = SpecialtyModel::find()
			// ->select(SpecialtyModel::selectableColumns())
			// ->with('createdByUser')
			// ->with('updatedByUser')
			// ->with('removedByUser')
			->where([$nodeAlias . '.spcID' => $id])
			->asArray()
			->one()
		;

		if ($model !== null)
			return $model;

		throw new NotFoundHttpException('The requested item does not exist.');

		// return RESTfulHelper::modelToResponse($this->findModel($id));
	}

	public function actionCreate($parentid = null)
	{
		PrivHelper::checkPriv(['mha/specialty/crud' => '1000']);

		$model = new SpecialtyModel();
		if ($model->load(Yii::$app->request->getBodyParams(), '') == false)
			throw new NotFoundHttpException("parameters not provided");

		try {
			if (empty($model->spcDescFieldType))
				$model->spcDescFieldLabel = null;

			if ($parentid == null)
				$done = $model->makeRoot();
			else {
				$parentModel = $this->findModel($parentid);
				$done = $model->appendTo($parentModel);
			}

			if ($done == false)
				throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

		} catch(\Exception $exp) {
			$msg = ExceptionHelper::CheckDuplicate($exp, $model);
			throw new UnprocessableEntityHttpException($msg);
		}

		return [
			// 'result' => [
				// 'message' => 'created',
				'spcID' => $model->spcID,
				'spcRoot' => $model->spcRoot,
				'spcLeft' => $model->spcLeft,
				'spcRight' => $model->spcRight,
				'spcLevel' => $model->spcLevel,
				'spcStatus' => $model->spcStatus,
				'spcCreatedAt' => $model->spcCreatedAt,
				'spcCreatedBy' => $model->spcCreatedBy,
			// ],
		];
	}

	public function actionUpdate($id)
	{
		PrivHelper::checkPriv(['mha/specialty/crud' => '0010']);

		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->getBodyParams(), '') == false)
			throw new NotFoundHttpException("parameters not provided");

		if (empty($model->spcDescFieldType))
			$model->spcDescFieldLabel = null;

		if ($model->save() == false)
			throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

		return [
			// 'result' => [
				// 'message' => 'updated',
				'spcID' => $model->spcID,
				'spcStatus' => $model->spcStatus,
				'spcUpdatedAt' => $model->spcUpdatedAt,
				'spcUpdatedBy' => $model->spcUpdatedBy,
			// ],
		];
	}

	public function actionDelete($id)
	{
		PrivHelper::checkPriv(['mha/specialty/crud' => '0001']);

		$model = $this->findModel($id);

		if ($model->delete() === false)
			throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

		return [
			// 'result' => [
				// 'message' => 'deleted',
				'spcID' => $model->spcID,
				'spcStatus' => $model->spcStatus,
				'spcRemovedAt' => $model->spcRemovedAt,
				'spcRemovedBy' => $model->spcRemovedBy,
			// ],
		];
	}

}
