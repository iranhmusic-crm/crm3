<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\accounting\controllers;

use shopack\base\backend\accounting\controllers\BaseProductController;

class ProductController extends BaseProductController
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

	public $modelClass = \iranhmusic\shopack\mha\backend\accounting\models\ProductModel::class;

	public function permissions()
	{
		return [
			// 'index'  => ['mha/accounting/product/crud', '0100'],
			// 'view'   => ['mha/accounting/product/crud', '0100'],
			'create' => ['mha/accounting/product/crud', '1000'],
			'update' => ['mha/accounting/product/crud', '0010'],
			'delete' => ['mha/accounting/product/crud', '0001'],
		];
	}

	public function queryAugmentaters()
	{
		return [
			'index' => function($query) {
				$query
					->joinWith('unit')
					->with('createdByUser')
					->with('updatedByUser')
					->with('removedByUser')
				;
			},
			'view' => function($query) {
				$query
					->joinWith('unit')
					->with('createdByUser')
					->with('updatedByUser')
					->with('removedByUser')
				;
			},
		];
	}

}
