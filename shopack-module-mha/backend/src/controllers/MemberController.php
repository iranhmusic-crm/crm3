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
use shopack\aaa\backend\models\UserModel;
use iranhmusic\shopack\mha\backend\models\MemberModel;
use iranhmusic\shopack\mha\backend\models\MemberSignupForm;

class MemberController extends BaseRestController
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
		if (($model = MemberModel::findOne($id)) !== null)
			return $model;

		throw new NotFoundHttpException('The requested item not exist.');
	}

	public function actionIndex($q = null)
	{
		$filter = $this->checkPrivAndGetFilter('mha/member/crud', '0100', 'mbrUserID');

		$searchModel = new MemberModel;
		$query = $searchModel::find()
			->select(MemberModel::selectableColumns())
			->addSelect(UserModel::selectableColumns())
			->innerJoinWith('user')
			->joinWith('user.imageFile')
			->with('createdByUser')
			->with('updatedByUser')
			->with('removedByUser')
			->asArray()
		;

		if (empty($q) == false) {
			$query->andWhere([
				'OR',
				['LIKE', 'mbrRegisterCode', $q],
				['LIKE', 'usrFirstName', $q],
				['LIKE', 'usrFirstName_en', $q],
				['LIKE', 'usrLastName', $q],
				['LIKE', 'usrLastName_en', $q],
				['LIKE', 'usrEmail', $q],
				['LIKE', 'usrMobile', $q],
				['LIKE', 'usrSSID', $q],
			]);
		}

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
		if (PrivHelper::hasPriv('mha/member/crud', '0100') == false) {
			if (Yii::$app->user->id != $id)
				throw new ForbiddenHttpException('access denied');
		}

		$model = MemberModel::find()
			->select(MemberModel::selectableColumns())
			->joinWith('user')
			->joinWith('user.imageFile')
			->with('createdByUser')
			->with('updatedByUser')
			->with('removedByUser')
			->where(['mbrUserID' => $id])
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
		PrivHelper::checkPriv('mha/member/crud', '1000');

		$model = new MemberModel();
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
				'mbrUserID' => $model->mbrUserID,
				'mbrStatus' => $model->mbrStatus,
				'mbrCreatedAt' => $model->mbrCreatedAt,
				'mbrCreatedBy' => $model->mbrCreatedBy,
			// ],
		];
	}

	public function actionUpdate($id)
	{
		if (PrivHelper::hasPriv('mha/member/crud', '0010') == false) {
			if (Yii::$app->user->id != $id)
				throw new ForbiddenHttpException('access denied');
		}

		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->getBodyParams(), '') == false)
			throw new NotFoundHttpException("parameters not provided");

		if ($model->save() == false)
			throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

		return [
			// 'result' => [
				// 'message' => 'updated',
				'mbrUserID' => $model->mbrUserID,
				'mbrStatus' => $model->mbrStatus,
				'mbrUpdatedAt' => $model->mbrUpdatedAt,
				'mbrUpdatedBy' => $model->mbrUpdatedBy,
			// ],
		];
	}

	public function actionDelete($id)
	{
		if (PrivHelper::hasPriv('mha/member/crud', '0001') == false) {
			if (Yii::$app->user->id != $id)
				throw new ForbiddenHttpException('access denied');
		}

		$model = $this->findModel($id);

		if ($model->delete() === false)
			throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

		return [
			// 'result' => [
				// 'message' => 'deleted',
				'mbrUserID' => $model->mbrUserID,
				'mbrStatus' => $model->mbrStatus,
				'mbrRemovedAt' => $model->mbrRemovedAt,
				'mbrRemovedBy' => $model->mbrRemovedBy,
			// ],
		];
	}

	public function actionSignup()
	{
		$model = new MemberSignupForm;

		if ($model->load(Yii::$app->request->getBodyParams(), '') == false)
			throw new NotFoundHttpException("parameters not provided");

		if ($model->mbrUserID != Yii::$app->user->id)
			PrivHelper::checkPriv('mha/member/crud', '1000');

		try {
			$result = $model->signup();

			if ($result == false)
				throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

			return $result;

		} catch(\Exception $exp) {
			$msg = ExceptionHelper::CheckDuplicate($exp, $model);
			throw new UnprocessableEntityHttpException($msg);
		}
	}

}
