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
use iranhmusic\shopack\mha\backend\models\MemberSponsorshipModel;

class MemberSponsorshipController extends BaseRestController
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
		if (($model = MemberSponsorshipModel::findOne([
					'mbrspsID' => $id,
				])) !== null)
			return $model;

		throw new NotFoundHttpException('The requested item does not exist.');
	}

	public function actionIndex()
	{
		$filter = $this->checkPrivAndGetFilter('mha/member-sponsorship/crud', '0100', 'mbrspsMemberID');

		$searchModel = new MemberSponsorshipModel;
		$query = $searchModel::find()
			->select(MemberSponsorshipModel::selectableColumns())
			->joinWith('member.user')
			->joinWith('masterInsuranceType')
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
		if (PrivHelper::hasPriv('mha/member-sponsorship/crud', '0100') == false) {
			$justForMe = true;
		}

		$model = MemberSponsorshipModel::find()
			->select(MemberSponsorshipModel::selectableColumns())
			->joinWith('member.user')
			->joinWith('masterInsuranceType')
			->andWhere(['mbrspsID' => $id])
			->asArray()
			->one()
		;

		if ($model !== null) {
			if ($justForMe && ($model['mbrspsMemberID'] != Yii::$app->user->id))
				throw new ForbiddenHttpException('access denied');

			return $model;
		}

		throw new NotFoundHttpException('The requested item does not exist.');

		// return RESTfulHelper::modelToResponse($this->findModel($id));
	}

	public function actionCreate()
	{
		$justForMe = false;
		if (PrivHelper::hasPriv('mha/member-sponsorship/crud', '1000') == false) {
			$justForMe = true;
		}

		$model = new MemberSponsorshipModel();
		if ($model->load(Yii::$app->request->getBodyParams(), '') == false)
			throw new NotFoundHttpException("parameters not provided");

		if ($justForMe && ($model->mbrspsMemberID != Yii::$app->user->id))
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
				// 'mbrspsID' => $model->mbrspsID,
				// 'mbrStatus' => $model->mbrspsStatus,
				'mbrspsCreatedAt' => $model->mbrspsCreatedAt,
				'mbrspsCreatedBy' => $model->mbrspsCreatedBy,
			// ],
		];
	}

	public function actionUpdate($id)
	{
		$justForMe = false;
		if (PrivHelper::hasPriv('mha/member-sponsorship/crud', '0010') == false) {
			$justForMe = true;
		}

		$model = $this->findModel($id);
		if ($model->load(Yii::$app->request->getBodyParams(), '') == false)
			throw new NotFoundHttpException("parameters not provided");

		if ($justForMe && ($model->mbrspsMemberID != Yii::$app->user->id))
			throw new ForbiddenHttpException('access denied');

		if ($model->save() == false)
			throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

		return [
			// 'result' => [
				// 'message' => 'updated',
				// 'mbrUserID' => $model->mbrUserID,
				// 'mbrStatus' => $model->mbrStatus,
				'mbrspsUpdatedAt' => $model->mbrspsUpdatedAt,
				'mbrspsUpdatedBy' => $model->mbrspsUpdatedBy,
			// ],
		];
	}

	public function actionDelete($id)
	{
		$justForMe = false;
		if (PrivHelper::hasPriv('mha/member-sponsorship/crud', '0001') == false) {
			$justForMe = true;
		}

		$model = $this->findModel($id);

		if ($justForMe && ($model->mbrspsMemberID != Yii::$app->user->id))
			throw new ForbiddenHttpException('access denied');

		if ($model->delete() === false)
			throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

		return [
			'result' => 'ok',
			// 'result' => [
				// 'message' => 'deleted',
				// 'mbrUserID' => $model->mbrUserID,
				// 'mbrStatus' => $model->mbrStatus,
				// 'mbrspsRemovedAt' => $model->mbrspsRemovedAt,
				// 'mbrspsRemovedBy' => $model->mbrspsRemovedBy,
			// ],
		];
	}

}
