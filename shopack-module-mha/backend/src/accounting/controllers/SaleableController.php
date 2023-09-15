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
use shopack\base\backend\accounting\controllers\BaseSaleableController;
use shopack\base\backend\helpers\PrivHelper;

class SaleableController extends BaseSaleableController
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

	public $modelClass = \iranhmusic\shopack\mha\backend\accounting\models\SaleableModel::class;

	public function permissions()
	{
		return [
			// 'index'  => ['mha/accounting/saleable/crud', '0100'],
			// 'view'   => ['mha/accounting/saleable/crud', '0100'],
			'create' => ['mha/accounting/saleable/crud', '1000'],
			'update' => ['mha/accounting/saleable/crud', '0010'],
			'delete' => ['mha/accounting/saleable/crud', '0001'],
		];
	}

	public function queryAugmentaters()
	{
		return [
			'index' => function($query) {
				$query
					->joinWith('product')
					->joinWith('product.unit')
					->with('createdByUser')
					->with('updatedByUser')
					->with('removedByUser')
				;
			},
			'view' => function($query) {
				$query
					->joinWith('product')
					->joinWith('product.unit')
					->with('createdByUser')
					->with('updatedByUser')
					->with('removedByUser')
				;
			},
		];
	}

}
