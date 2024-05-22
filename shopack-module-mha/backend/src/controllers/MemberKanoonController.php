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
use iranhmusic\shopack\mha\backend\models\MemberKanoonModel;
use iranhmusic\shopack\mha\common\enums\enuMemberKanoonStatus;

class MemberKanoonController extends BaseRestController
{
	// public function behaviors()
	// {
	// 	$behaviors = parent::behaviors();
	// 	return $behaviors;
	// }

	protected function findModel($id)
	{
		if (($model = MemberKanoonModel::findOne(['mbrknnID' => $id])) !== null)
			return $model;

		throw new NotFoundHttpException('The requested item does not exist.');
	}

	public function actionIndex()
	{
		$filter = $this->checkPrivAndGetFilter('mha/member-kanoon/crud', '0100', 'mbrknnMemberID');

		$searchModel = new MemberKanoonModel;
		$query = $searchModel::find()
			->select(MemberKanoonModel::selectableColumns())
			->joinWith('member.user')
			->joinWith('kanoon')
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
		$justForMe = false;
		if (PrivHelper::hasPriv('mha/member-kanoon/crud', '0100') == false) {
			$justForMe = true;
		}

		$model = MemberKanoonModel::find()
			->select(MemberKanoonModel::selectableColumns())
			->joinWith('member.user')
			->joinWith('kanoon')
			->with('createdByUser')
			->with('updatedByUser')
			->with('removedByUser')
			->andWhere(['mbrknnID' => $id])
			->asArray()
			->one()
		;

		if ($model !== null) {
			if ($justForMe && ($model['mbrknnMemberID'] != Yii::$app->user->id))
				throw new ForbiddenHttpException('access denied');

			return $model;
		}

		throw new NotFoundHttpException('The requested item does not exist.');

		// return RESTfulHelper::modelToResponse($this->findModel($id));
	}

	public function actionCreate()
	{
		$justForMe = false;
		if (PrivHelper::hasPriv('mha/member-kanoon/crud', '1000') == false) {
			$justForMe = true;
		}

		$model = new MemberKanoonModel();
		if ($model->load(Yii::$app->request->getBodyParams(), '') == false)
			throw new NotFoundHttpException("parameters not provided");

		if ($justForMe && ($model->mbrknnMemberID != Yii::$app->user->id))
			throw new ForbiddenHttpException('access denied');

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
				// 'mbrknnID' => $model->mbrknnID,
				// 'mbrStatus' => $model->mbrknnStatus,
				'mbrknnCreatedAt' => $model->mbrknnCreatedAt,
				'mbrknnCreatedBy' => $model->mbrknnCreatedBy,
			// ],
		];
	}

	public function actionUpdate($id)
	{
		$justForMe = false;
		if (PrivHelper::hasPriv('mha/member-kanoon/crud', '0010') == false) {
			$justForMe = true;
		}

		$model = $this->findModel($id);
		if ($model->load(Yii::$app->request->getBodyParams(), '') == false)
			throw new NotFoundHttpException("parameters not provided");

		if ($justForMe && ($model->mbrknnMemberID != Yii::$app->user->id))
			throw new ForbiddenHttpException('access denied');

		if ($model->save() == false)
			throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

		return [
			// 'result' => [
				// 'message' => 'updated',
				// 'mbrUserID' => $model->mbrUserID,
				// 'mbrStatus' => $model->mbrStatus,
				'mbrRegisterCode' => $model->mbrRegisterCode,
				'mbrknnUpdatedAt' => $model->mbrknnUpdatedAt,
				'mbrknnUpdatedBy' => $model->mbrknnUpdatedBy,
			// ],
		];
	}

	public function actionDelete($id)
	{
		$justForMe = false;
		if (PrivHelper::hasPriv('mha/member-kanoon/crud', '0001') == false) {
			$justForMe = true;
		}

		$model = $this->findModel($id);

		if ($justForMe && ($model->mbrknnMemberID != Yii::$app->user->id))
			throw new ForbiddenHttpException('access denied');

		if ($model->delete() === false)
			throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

		return [
			'result' => 'ok',
			// 'result' => [
				// 'message' => 'deleted',
				// 'mbrUserID' => $model->mbrUserID,
				// 'mbrStatus' => $model->mbrStatus,
				// 'mbrknnRemovedAt' => $model->mbrknnRemovedAt,
				// 'mbrknnRemovedBy' => $model->mbrknnRemovedBy,
			// ],
		];
	}

	public function actionOptions()
	{
		return 'options';
	}
/*
	// #[Permission('mha/member-kanoon/accept')]
	public function actionAccept($id)
	{
		PrivHelper::checkPriv('mha/member-kanoon/accept');

		$model = $this->findModel($id);
		$model->doAccept();

		return [
			'result' => true,
			// 'mbrRegisterCode' => $mbrRegisterCode,
		];
	}

	public function actionReject($id)
	{
		PrivHelper::checkPriv('mha/member-kanoon/reject');

		$model = $this->findModel($id);
		$model->doReject();

		return [
			'result' => true,
		];
	}
*/

}
