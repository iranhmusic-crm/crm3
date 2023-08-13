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
use iranhmusic\shopack\mha\backend\models\MemberMembershipModel;

class MemberMembershipController extends BaseRestController
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
		if (($model = MemberMembershipModel::findOne([
					'mbrshpID' => $id,
				])) !== null)
			return $model;

		throw new NotFoundHttpException('The requested item not exist.');
	}

	public function actionIndex()
	{
		$filter = $this->checkPrivAndGetFilter('mha/member-membership/crud', '0100', 'mbrshpMemberID');

		$searchModel = new MemberMembershipModel;
		$query = $searchModel::find()
			->select(MemberMembershipModel::selectableColumns())
			->joinWith('member.user')
			->joinWith('membership')
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
		$justForMe = false;
		if (PrivHelper::hasPriv('mha/member-membership/crud', '0100') == false) {
			$justForMe = true;
		}

		$model = MemberMembershipModel::find()
			->select(MemberMembershipModel::selectableColumns())
			->joinWith('member.user')
			->joinWith('membership')
			->andWhere(['mbrshpID' => $id])
			->asArray()
			->one()
		;

		if ($model !== null) {
			if ($justForMe && ($model->mbrshpMemberID != Yii::$app->user->id))
				throw new ForbiddenHttpException('access denied');

			return $model;
		}

		throw new NotFoundHttpException('The requested item not exist.');

		// return RESTfulHelper::modelToResponse($this->findModel($id));
	}

	// public function actionCreate()
	// {
	// 	$justForMe = false;
	// 	if (PrivHelper::hasPriv('mha/member-membership/crud', '1000') == false) {
	// 		$justForMe = true;
	// 	}

	// 	$model = new MemberMembershipModel();
	// 	if ($model->load(Yii::$app->request->getBodyParams(), '') == false)
	// 		throw new NotFoundHttpException("parameters not provided");

	// 	if ($justForMe && ($model->mbrshpMemberID != Yii::$app->user->id))
	// 		throw new ForbiddenHttpException('access denied');

	// 	try {
	// 		if ($model->save() == false)
	// 			throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));
	// 	} catch(\Exception $exp) {
	//		$msg = ExceptionHelper::CheckDuplicate($exp, $model);
	// 		throw new UnprocessableEntityHttpException($msg);
	// 	}

	// 	return [
	// 		// 'result' => [
	// 			// 'message' => 'created',
	// 			// 'mbrshpID' => $model->mbrshpID,
	// 			// 'mbrStatus' => $model->mbrshpStatus,
	// 			'mbrshpCreatedAt' => $model->mbrshpCreatedAt,
	// 			'mbrshpCreatedBy' => $model->mbrshpCreatedBy,
	// 		// ],
	// 	];
	// }

	// public function actionUpdate($id)
	// {
	// 	$justForMe = false;
	// 	if (PrivHelper::hasPriv('mha/member-membership/crud', '0010') == false) {
	// 		$justForMe = true;
	// 	}

	// 	$model = $this->findModel($id);
	// 	if ($model->load(Yii::$app->request->getBodyParams(), '') == false)
	// 		throw new NotFoundHttpException("parameters not provided");

	// 	if ($justForMe && ($model->mbrshpMemberID != Yii::$app->user->id))
	// 		throw new ForbiddenHttpException('access denied');

	// 	if ($model->save() == false)
	// 		throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

	// 	return [
	// 		// 'result' => [
	// 			// 'message' => 'updated',
	// 			// 'mbrUserID' => $model->mbrUserID,
	// 			// 'mbrStatus' => $model->mbrStatus,
	// 			'mbrshpUpdatedAt' => $model->mbrshpUpdatedAt,
	// 			'mbrshpUpdatedBy' => $model->mbrshpUpdatedBy,
	// 		// ],
	// 	];
	// }

	// public function actionDelete($id)
	// {
	// 	$justForMe = false;
	// 	if (PrivHelper::hasPriv('mha/member-membership/crud', '0001') == false) {
	// 		$justForMe = true;
	// 	}

	// 	$model = $this->findModel($id);

	// 	if ($justForMe && ($model->mbrshpMemberID != Yii::$app->user->id))
	// 		throw new ForbiddenHttpException('access denied');

	// 	if ($model->delete() === false)
	// 		throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

	// 	return [
	// 		'result' => 'ok',
	// 		// 'result' => [
	// 			// 'message' => 'deleted',
	// 			// 'mbrUserID' => $model->mbrUserID,
	// 			// 'mbrStatus' => $model->mbrStatus,
	// 			// 'mbrshpRemovedAt' => $model->mbrshpRemovedAt,
	// 			// 'mbrshpRemovedBy' => $model->mbrshpRemovedBy,
	// 		// ],
	// 	];
	// }

	public function actionRenewalInfo($memberID = null)
	{
		if ($memberID == null)
			$memberID = Yii::$app->user->id;
		else if (($memberID != Yii::$app->user->id)
				&& (PrivHelper::hasPriv('mha/member-membership/crud', '0100') == false)) {
			throw new ForbiddenHttpException('access denied');
		}

		list ($startDate, $endDate, $years, $unitPrice, $totalPrice, $saleableID) = MemberMembershipModel::getRenewalInfo($memberID);

		return [
			'startDate'		=> $startDate,
			'endDate'			=> $endDate,
			'years'				=> $years,
			'unitPrice'		=> $unitPrice,
			'totalPrice'	=> $totalPrice,
			'saleableID'	=> $saleableID,
		];
	}

}
