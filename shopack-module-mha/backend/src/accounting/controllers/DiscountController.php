<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\accounting\controllers;

use shopack\interface\accounting\backend\controllers\BaseDiscountController;

class DiscountController extends BaseDiscountController
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

	public $modelClass = \iranhmusic\shopack\mha\backend\accounting\models\DiscountModel::class;

	public function permissions()
	{
		return [
			// 'index'  => ['mha/accounting/discount/crud', '0100'],
			// 'view'   => ['mha/accounting/discount/crud', '0100'],
			'create' => ['mha/accounting/discount/crud', '1000'],
			'update' => ['mha/accounting/discount/crud', '0010'],
			'delete' => ['mha/accounting/discount/crud', '0001'],
			'undelete' => ['mha/accounting/discount/undelete'],
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
