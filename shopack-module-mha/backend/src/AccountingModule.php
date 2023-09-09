<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend;

use yii\base\BootstrapInterface;
use shopack\base\common\shop\ShopModuleTrait;
use iranhmusic\shopack\mha\backend\models\MembershipModel;
use iranhmusic\shopack\mha\backend\models\MemberMembershipModel;
use iranhmusic\shopack\mha\backend\accounting\controllers\DefaultController;
use iranhmusic\shopack\mha\backend\accounting\controllers\UnitController;
use iranhmusic\shopack\mha\backend\accounting\controllers\CouponController;
use iranhmusic\shopack\mha\backend\accounting\controllers\ProductController;
use iranhmusic\shopack\mha\backend\accounting\controllers\SaleableController;
use iranhmusic\shopack\mha\backend\accounting\controllers\UserAssetController;

class AccountingModule
	extends \shopack\base\common\base\BaseModule
	implements BootstrapInterface
{
	public $controllerNamespace = 'iranhmusic\shopack\mha\backend\accounting\controllers';

	public function init()
	{
		if (empty($this->id))
			$this->id = 'accounting';

		parent::init();
	}

	public function bootstrap($app)
	{
		$parentID = $this->module->id;
		$thisID = $parentID . '/' . $this->id;

		if ($app instanceof \yii\web\Application) {

			// $this->controllerMap['default']			= DefaultController::class;
			// $this->controllerMap['unit']				= UnitController::class;
			// $this->controllerMap['coupon']			= CouponController::class;
			// $this->controllerMap['product']			= ProductController::class;
			// $this->controllerMap['saleable']		= SaleableController::class;
			// $this->controllerMap['user-asset']	= UserAssetController::class;

			$rules = [];

			//-- accounting ---------------------------------
			$rules = array_merge($rules, [
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$thisID . '/unit'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$thisID . '/coupon'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$thisID . '/product'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$thisID . '/saleable'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$thisID . '/user-asset'],
					'pluralize' => false,
				],

				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$thisID => $thisID . '/default'],
					'pluralize' => false,

					'patterns' => [
						'GET remove-basket-item' => 'remove-basket-item',
					],
				],

			]);

			$app->urlManager->addRules($rules, false);

		} elseif ($app instanceof \yii\console\Application) {
			$this->controllerNamespace = 'iranhmusic\shopack\mha\backend\commands';
		}
	}

}
