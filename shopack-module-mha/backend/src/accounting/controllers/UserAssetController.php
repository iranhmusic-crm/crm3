<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\accounting\controllers;

use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii\data\ActiveDataProvider;
use shopack\base\common\helpers\ExceptionHelper;
use shopack\base\backend\accounting\controllers\BaseUserAssetController;
use shopack\base\backend\helpers\PrivHelper;

class UserAssetController extends BaseUserAssetController
{
	public function behaviors()
	{
		$behaviors = parent::behaviors();

		// $behaviors[static::BEHAVIOR_AUTHENTICATOR]['except'] = [
		// 	'index',
		// 	'view',
		// ];

		return $behaviors;
	}

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
											if (Yii::$app->user->isGuest)
												throw new \yii\web\ForbiddenHttpException("not allowed for guest");
											$query->andWhere(['uasActorID' => Yii::$app->user->id]);
										},
									],
			'view'   => ['mha/accounting/user-asset/crud' => '0100', 'checker' => $checkOwner],
			'create' => ['mha/accounting/user-asset/crud' => '1000'],
			'update' => ['mha/accounting/user-asset/crud' => '0010', 'checker' => $checkOwner],
			'delete' => ['mha/accounting/user-asset/crud' => '0001', 'checker' => $checkOwner],
		];
	}

	public function queryAugmentaters()
	{
		return [
			'index' => function($query) {
				$query
					->joinWith('saleable')
					->joinWith('saleable.product')
					->joinWith('coupon')
					->with('createdByUser')
					->with('updatedByUser')
					->with('removedByUser')
				;
			},
			'view' => function($query) {
				$query
					->joinWith('saleable')
					->joinWith('saleable.product')
					->joinWith('coupon')
					->with('createdByUser')
					->with('updatedByUser')
					->with('removedByUser')
				;
			},
		];
	}

}
