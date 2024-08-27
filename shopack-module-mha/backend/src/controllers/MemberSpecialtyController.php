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
use iranhmusic\shopack\mha\backend\models\MemberSpecialtyModel;

class MemberSpecialtyController extends BaseRestController
{
	public function actionOptions()
	{
		return 'options';
	}

	// protected function findModel($mbrid, $spcid)
	// {
	// 	if (($model = MemberSpecialtyModel::findOne([
	// 				'mbrspcMemberID' => $mbrid,
	// 				'mbrspcSpecialtyID' => $spcid,
	// 			])) !== null)
	// 		return $model;

	// 	throw new NotFoundHttpException('The requested item does not exist.');
	// }

	protected function findModel($id)
	{
		if (($model = MemberSpecialtyModel::findOne($id)) !== null)
			return $model;

		throw new NotFoundHttpException('The requested item does not exist.');
	}

	public function actionIndex()
	{
		$filter = $this->checkPrivAndGetFilter('mha/member-specialty/crud', '0100', 'mbrspcMemberID');

		$searchModel = new MemberSpecialtyModel;
		$query = MemberSpecialtyModel::find()
			// ->select(MemberSpecialtyModel::selectableColumns())
			->joinWith('member.user')
			->joinWith('specialty')
		;

		$searchModel->fillQueryFromRequest($query);

		if (empty($filter) == false)
			$query->andWhere($filter);

		return $this->queryAllToResponse($query);
	}

	public function actionView($id) //$mbrid, $spcid)
	{
		$justForMe = false;
		if (PrivHelper::hasPriv('mha/member-specialty/crud', '0100') == false) {
			$justForMe = true;
		}

		$query = MemberSpecialtyModel::find()
			// ->select(MemberSpecialtyModel::selectableColumns())
			->joinWith('member.user')
			->joinWith('specialty')
			->andWhere(['mbrspcID' => $id])
			// ->andWhere(['mbrspcMemberID' => $mbrid])
			// ->andWhere(['mbrspcSpecialtyID' => $spcid])
		;

		return $this->queryOneToResponse($query, function($model) use($justForMe) {
			if ($justForMe && ($model['mbrspcMemberID'] != Yii::$app->user->id))
				throw new ForbiddenHttpException('access denied');
		});
	}

	public function actionCreate()
	{
		$justForMe = false;
		if (PrivHelper::hasPriv('mha/member-specialty/crud', '1000') == false) {
			$justForMe = true;
		}

		$model = new MemberSpecialtyModel();
		if ($model->load(Yii::$app->request->getBodyParams(), '') == false)
			throw new NotFoundHttpException("parameters not provided");

		if ($justForMe && ($model->mbrspcMemberID != Yii::$app->user->id))
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
				// 'mbrspcID' => $model->mbrspcID,
				// 'mbrStatus' => $model->mbrspcStatus,
				'mbrspcCreatedAt' => $model->mbrspcCreatedAt,
				'mbrspcCreatedBy' => $model->mbrspcCreatedBy,
			// ],
		];
	}

	public function actionUpdate($id) //$mbrid, $spcid)
	{
		$justForMe = false;
		if (PrivHelper::hasPriv('mha/member-specialty/crud', '0010') == false) {
			$justForMe = true;
		}

		$model = $this->findModel($id); //mbrid, $spcid);
		if ($model->load(Yii::$app->request->getBodyParams(), '') == false)
			throw new NotFoundHttpException("parameters not provided");

		if ($justForMe && ($model->mbrspcMemberID != Yii::$app->user->id))
			throw new ForbiddenHttpException('access denied');

		if ($model->save() == false)
			throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

		return [
			// 'result' => [
				// 'message' => 'updated',
				// 'mbrUserID' => $model->mbrUserID,
				// 'mbrStatus' => $model->mbrStatus,
				'mbrspcUpdatedAt' => $model->mbrspcUpdatedAt,
				'mbrspcUpdatedBy' => $model->mbrspcUpdatedBy,
			// ],
		];
	}

	public function actionDelete($id) //mbrid, $spcid)
	{
		$justForMe = false;
		if (PrivHelper::hasPriv('mha/member-specialty/crud', '0001') == false) {
			$justForMe = true;
		}

		$model = $this->findModel($id); //mbrid, $spcid);

		if ($justForMe && ($model->mbrspcMemberID != Yii::$app->user->id))
			throw new ForbiddenHttpException('access denied');

		if ($model->delete() === false)
			throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

		return [
			'result' => 'ok',
			// 'result' => [
				// 'message' => 'deleted',
				// 'mbrspcUserID' => $model->mbrspcUserID,
				// 'mbrspcStatus' => $model->mbrspcStatus,
				// 'mbrspcRemovedAt' => $model->mbrspcRemovedAt,
				// 'mbrspcRemovedBy' => $model->mbrspcRemovedBy,
			// ],
		];
	}

}
