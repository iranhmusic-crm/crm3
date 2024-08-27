<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\accounting\controllers;

use Yii;
use shopack\base\backend\accounting\controllers\BaseUserAssetController;

class UserAssetController extends BaseUserAssetController
{
	public $modelClass = \iranhmusic\shopack\mha\backend\accounting\models\UserAssetModel::class;

	public function permissions()
	{
		$checkOwner = function($model) : bool {
			return (($model != null) && ($model['uasActorID'] == Yii::$app->user->id));
		};

		return [
			'index'  => [
										'mha/accounting/user-asset/crud' => '0100',
										'filter' => function($query) {
											Yii::$app->user->assertIsNotGuest();
											$query->andWhere(['uasActorID' => Yii::$app->user->id]);
										},
									],
			'view'   => ['mha/accounting/user-asset/crud' => '0100', 'checker' => $checkOwner],
			'create' => ['mha/accounting/user-asset/crud' => '1000', 'checker' => $checkOwner],
			'update' => ['mha/accounting/user-asset/crud' => '0010', 'checker' => $checkOwner],
			'delete' => ['mha/accounting/user-asset/crud' => '0001', 'checker' => $checkOwner],
			'undelete' => ['mha/accounting/user-asset/undelete'],
		];
	}

	public function queryAugmentaters()
	{
		return [
			'index' => function($query) {
				$query
					->joinWith('actor')
					->joinWith('saleable')
					->joinWith('saleable.product')
					// ->joinWith('discount')
					->with('createdByUser')
					->with('updatedByUser')
					->with('removedByUser')
				;
			},
			'view' => function($query) {
				$query
					->joinWith('actor')
					->joinWith('saleable')
					->joinWith('saleable.product')
					// ->joinWith('discount')
					->with('createdByUser')
					->with('updatedByUser')
					->with('removedByUser')
				;
			},
		];
	}

}
