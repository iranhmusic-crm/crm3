<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii\data\ActiveDataProvider;
use shopack\base\common\helpers\ExceptionHelper;
use shopack\base\backend\controller\BaseCrudController;
use shopack\base\backend\helpers\PrivHelper;
use iranhmusic\shopack\mha\backend\models\MemberMemberGroupModel;

class MemberMemberGroupController extends BaseCrudController
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

	public $modelClass = \iranhmusic\shopack\mha\backend\models\MemberMemberGroupModel::class;

	public function permissions()
	{
		return [
			'index'  => ['mha/member-member-group/crud' => '0100'],
			'view'   => ['mha/member-member-group/crud' => '0100'],
			'create' => ['mha/member-member-group/crud' => '1000'],
			'update' => ['mha/member-member-group/crud' => '0010'],
			'delete' => ['mha/member-member-group/crud' => '0001'],
		];
	}

	public function queryAugmentaters()
	{
		return [
			'index' => function($query) {
				$query
					// ->joinWith('member')
					->joinWith('member.user')
					->joinWith('memberGroup')
					->with('createdByUser')
					->with('updatedByUser')
					->with('removedByUser')
				;
			},
			'view' => function($query) {
				$query
					// ->joinWith('member')
					->joinWith('member.user')
					->joinWith('memberGroup')
					->with('createdByUser')
					->with('updatedByUser')
					->with('removedByUser')
				;
			},
		];
	}

}
