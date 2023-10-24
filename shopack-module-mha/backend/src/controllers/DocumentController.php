<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\controllers;

use Yii;
use yii\web\ForbiddenHttpException;
use shopack\base\backend\controller\BaseCrudController;
use shopack\base\backend\helpers\PrivHelper;
use iranhmusic\shopack\mha\backend\models\DocumentModel;

class DocumentController extends BaseCrudController
{
	public function behaviors()
	{
		$behaviors = parent::behaviors();

		$behaviors[static::BEHAVIOR_AUTHENTICATOR]['except'] = [
			'index',
			'view',
		];

		return $behaviors;
	}

	public $modelClass = \iranhmusic\shopack\mha\backend\models\DocumentModel::class;

	public function permissions()
	{
		return [
			// 'index'  => ['mha/document/crud', '0100'],
			// 'view'   => ['mha/document/crud', '0100'],
			'create' => ['mha/document/crud', '1000'],
			'update' => ['mha/document/crud', '0010'],
			'delete' => ['mha/document/crud', '0001'],
		];
	}

	public function queryAugmentaters()
	{
		return [
			'index' => function($query) {
				$query
					->with('createdByUser')
					->with('updatedByUser')
					->with('removedByUser')
				;
			},
			'view' => function($query) {
				$query
					->with('createdByUser')
					->with('updatedByUser')
					->with('removedByUser')
				;
			},
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
			->addSelect(new \yii\db\Expression("IFNULL(tmpmbrdoc.cnt, 0) AS providedCount"))
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

		return $this->queryAllToResponse($query);
	}

}
