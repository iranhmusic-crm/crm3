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
use shopack\base\backend\accounting\controllers\BaseUnitController;
use shopack\base\backend\helpers\PrivHelper;

class UnitController extends BaseUnitController
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

	public $modelClass = \iranhmusic\shopack\mha\backend\accounting\models\UnitModel::class;

	public function permissions()
	{
		return [
			// 'index'  => ['mha/accounting/unit/crud', '0100'],
			// 'view'   => ['mha/accounting/unit/crud', '0100'],
			'create' => ['mha/accounting/unit/crud', '1000'],
			'update' => ['mha/accounting/unit/crud', '0010'],
			'delete' => ['mha/accounting/unit/crud', '0001'],
		];
	}

}
