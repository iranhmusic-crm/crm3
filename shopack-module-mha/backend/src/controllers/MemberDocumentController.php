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
use iranhmusic\shopack\mha\backend\models\MemberDocumentModel;

class MemberDocumentController extends BaseRestController
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
		if (($model = MemberDocumentModel::findOne([
					'mbrdocID' => $id,
				])) !== null)
			return $model;

		throw new NotFoundHttpException('The requested item does not exist.');
	}

	public function actionIndex()
	{
		$filter = $this->checkPrivAndGetFilter('mha/member-document/crud', '0100', 'mbrdocMemberID');

		$searchModel = new MemberDocumentModel;
		$query = $searchModel::find()
			->select(MemberDocumentModel::selectableColumns())
			->joinWith('member.user')
			->joinWith('document')
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
		if (PrivHelper::hasPriv('mha/member-document/crud', '0100') == false) {
			$justForMe = true;
		}

		$model = MemberDocumentModel::find()
			->select(MemberDocumentModel::selectableColumns())
			->joinWith('member.user')
			->joinWith('document')
			->with('createdByUser')
			->with('updatedByUser')
			->with('removedByUser')
			->andWhere(['mbrdocID' => $id])
			->asArray()
			->one()
		;

		if ($model !== null) {
			if ($justForMe && ($model['mbrdocMemberID'] != Yii::$app->user->id))
				throw new ForbiddenHttpException('access denied');

			return $model;
		}

		throw new NotFoundHttpException('The requested item does not exist.');

		// return RESTfulHelper::modelToResponse($this->findModel($id));
	}

	public function actionCreate()
	{
		$justForMe = false;
		if (PrivHelper::hasPriv('mha/member-document/crud', '1000') == false) {
			$justForMe = true;
		}

		$model = new MemberDocumentModel();
		if ($model->load(Yii::$app->request->getBodyParams(), '') == false)
			throw new NotFoundHttpException("parameters not provided");

		if ($justForMe && ($model->mbrdocMemberID != Yii::$app->user->id))
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
				// 'mbrdocID' => $model->mbrdocID,
				// 'mbrStatus' => $model->mbrdocStatus,
				'mbrdocFileID' => $model->mbrdocFileID,
				'mbrdocCreatedAt' => $model->mbrdocCreatedAt,
				'mbrdocCreatedBy' => $model->mbrdocCreatedBy,
			// ],
		];
	}

	public function actionUpdate($id)
	{
		$justForMe = false;
		if (PrivHelper::hasPriv('mha/member-document/crud', '0010') == false) {
			$justForMe = true;
		}

		$model = $this->findModel($id);
		if ($model->load(Yii::$app->request->getBodyParams(), '') == false)
			throw new NotFoundHttpException("parameters not provided");

		if ($justForMe && ($model->mbrdocMemberID != Yii::$app->user->id))
			throw new ForbiddenHttpException('access denied');

		if ($model->save() == false)
			throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

		return [
			// 'result' => [
				// 'message' => 'updated',
				// 'mbrUserID' => $model->mbrUserID,
				// 'mbrStatus' => $model->mbrStatus,
				'mbrdocUpdatedAt' => $model->mbrdocUpdatedAt,
				'mbrdocUpdatedBy' => $model->mbrdocUpdatedBy,
			// ],
		];
	}

	public function actionDelete($id)
	{
		$justForMe = false;
		if (PrivHelper::hasPriv('mha/member-document/crud', '0001') == false) {
			$justForMe = true;
		}

		$model = $this->findModel($id);

		if ($justForMe && ($model->mbrdocMemberID != Yii::$app->user->id))
			throw new ForbiddenHttpException('access denied');

		if ($model->delete() === false)
			throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

		return [
			'result' => 'ok',
			// 'result' => [
				// 'message' => 'deleted',
				// 'mbrUserID' => $model->mbrUserID,
				// 'mbrStatus' => $model->mbrStatus,
				// 'mbrdocRemovedAt' => $model->mbrdocRemovedAt,
				// 'mbrdocRemovedBy' => $model->mbrdocRemovedBy,
			// ],
		];
	}

}
