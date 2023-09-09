<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend;

use yii\base\BootstrapInterface;
use shopack\base\common\shop\ShopModuleTrait;
use iranhmusic\shopack\mha\backend\models\MembershipModel;
use iranhmusic\shopack\mha\backend\models\MemberMembershipModel;
use iranhmusic\shopack\mha\backend\accounting\controllers\AccountingController;
use iranhmusic\shopack\mha\backend\accounting\controllers\UnitController;
use iranhmusic\shopack\mha\backend\accounting\controllers\CouponController;
use iranhmusic\shopack\mha\backend\accounting\controllers\ProductController;
use iranhmusic\shopack\mha\backend\accounting\controllers\SaleableController;
use iranhmusic\shopack\mha\backend\accounting\controllers\UserAssetController;

class AccountingModule
	extends \shopack\base\common\base\BaseModule
	implements BootstrapInterface
{
	public function init()
	{
		if (empty($this->id))
			$this->id = 'accounting';

		parent::init();
	}

	public function bootstrap($app)
	{
		if ($app instanceof \yii\web\Application) {
			$rules = [];

			//-- accounting ---------------------------------
			$rules = array_merge($rules, [
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/accounting'],
					'pluralize' => false,

					'patterns' => [
						// 'GET,HEAD'					=> 'index',
						// 'GET,HEAD {uuid}'		=> 'view',
						// 'POST'							=> 'create',
						// 'PUT,PATCH {uuid}'	=> 'update',
						// 'DELETE {uuid}'			=> 'delete',
						// '{uuid}'						=> 'options',
						// ''									=> 'options',
						'GET remove-basket-item' => 'remove-basket-item',
					],
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/accounting/unit'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/accounting/coupon'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/accounting/product'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/accounting/saleable'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/accounting/user-asset'],
					'pluralize' => false,
				],
			]);

			$app->urlManager->addRules($rules, false);

		} elseif ($app instanceof \yii\console\Application) {
			$this->controllerNamespace = 'iranhmusic\shopack\mha\backend\commands';
		}
	}

}
