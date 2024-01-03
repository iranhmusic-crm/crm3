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
use shopack\base\backend\accounting\controllers\BaseDiscountGroupController;
use shopack\base\backend\helpers\PrivHelper;

class DiscountGroupController extends BaseDiscountGroupController
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

	public $modelClass = \iranhmusic\shopack\mha\backend\accounting\models\DiscountGroupModel::class;

	public function permissions()
	{
		return [
			// 'index'  => ['mha/accounting/discount-group/crud', '0100'],
			// 'view'   => ['mha/accounting/discount-group/crud', '0100'],
			'create' => ['mha/accounting/discount-group/crud', '1000'],
			'update' => ['mha/accounting/discount-group/crud', '0010'],
			'delete' => ['mha/accounting/discount-group/crud', '0001'],
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

}
