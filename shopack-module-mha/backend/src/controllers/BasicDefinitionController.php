<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\controllers;

use shopack\base\backend\controller\BaseCrudController;

class BasicDefinitionController extends BaseCrudController
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

	public $modelClass = \iranhmusic\shopack\mha\backend\models\BasicDefinitionModel::class;

	public function permissions()
	{
		return [
			// 'index'  => ['mha/basic-definition/crud', '0100'],
			// 'view'   => ['mha/basic-definition/crud', '0100'],
			'create' => ['mha/basic-definition/crud', '1000'],
			'update' => ['mha/basic-definition/crud', '0010'],
			'delete' => ['mha/basic-definition/crud', '0001'],
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
