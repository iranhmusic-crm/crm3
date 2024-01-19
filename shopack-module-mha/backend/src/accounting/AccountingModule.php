<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\accounting;

use iranhmusic\shopack\mha\backend\accounting\models\UnitModel;
use iranhmusic\shopack\mha\backend\accounting\models\ProductModel;
use iranhmusic\shopack\mha\backend\accounting\models\SaleableModel;
use iranhmusic\shopack\mha\backend\accounting\models\DiscountModel;
use iranhmusic\shopack\mha\backend\accounting\models\DiscountUsageModel;
use iranhmusic\shopack\mha\backend\accounting\models\UserAssetModel;
use iranhmusic\shopack\mha\backend\accounting\models\BasketModel;

class AccountingModule extends \shopack\base\backend\accounting\AccountingModule
{
	public $unitModelClass = UnitModel::class;
	public $productModelClass = ProductModel::class;
	public $saleableModelClass = SaleableModel::class;
	public $discountModelClass = DiscountModel::class;
	public $discountUsageModelClass = DiscountUsageModel::class;
	public $userAssetModelClass = UserAssetModel::class;
	public $basketModelClass = BasketModel::class;

	public function bootstrap($app)
	{
		parent::bootstrap($app);

		$parentID = $this->module->id;
		$thisID = $parentID . '/' . $this->id;

		if ($app instanceof \yii\web\Application) {
			$rules = [
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$thisID . '/membership'],
					'pluralize' => false,

					'patterns' => [
						'GET renewal-info' => 'renewal-info',
						'POST add-to-basket' => 'add-to-basket',
					],
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$thisID . '/membership-card'],
					'pluralize' => false,

					'patterns' => [
						'GET renewal-info' => 'renewal-info',
						'POST add-to-basket' => 'add-to-basket',
					],
				],
			];

			$app->urlManager->addRules($rules, false);
		}

	}

}
