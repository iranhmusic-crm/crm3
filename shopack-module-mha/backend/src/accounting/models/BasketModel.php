<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\accounting\models;

use Yii;
use shopack\base\backend\accounting\models\BaseBasketModel;

class BasketModel extends BaseBasketModel
{
	public $unitModelClass = UnitModel::class;
	public $productModelClass = ProductModel::class;
	public $saleableModelClass = SaleableModel::class;
	public $discountModelClass = DiscountModel::class;
	public $userAssetModelClass = UserAssetModel::class;
}
