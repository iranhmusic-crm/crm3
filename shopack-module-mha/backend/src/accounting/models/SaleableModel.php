<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\accounting\models;

use Yii;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;
use iranhmusic\shopack\mha\common\accounting\enums\enuMhaProductType;
use iranhmusic\shopack\mha\backend\accounting\models\UserAssetModel;
use shopack\base\common\accounting\enums\enuUserAssetStatus;
use iranhmusic\shopack\mha\backend\models\MemberModel;

class SaleableModel extends MhaActiveRecord
{
	use \iranhmusic\shopack\mha\common\accounting\models\SaleableModelTrait;

	public static function tableName()
	{
		return '{{%MHA_Accounting_Saleable}}';
	}

	public static function ProcessVoucherItem($voucherID, $userid, $voucherItemdata)
	{
		$slbid = $voucherItemdata['slbid'];
		$saleableModel = SaleableModel::find()->andWhere(['slbid' => $slbid])
			->joinWith('product')
			->one();

		if ($saleableModel == null)
			throw new UnprocessableEntityHttpException("Invalid saleable id ({$slbid})");

		//check existance
		$key = $voucherItemdata['key'];
		$userAssetModel = UserAssetModel::find()->andWhere(['uasUUID' => $key])->one();
		if ($userAssetModel != null)
			return true; //already exists

		//1: save user asset
		$service		= $voucherItemdata['service'];
		// $slbkey			= $voucherItemdata['slbkey'];
		$desc				= $voucherItemdata['desc'];
		$qty				= $voucherItemdata['qty'];
		$unitprice	= $voucherItemdata['unitprice'];
    //additives
    //discount
    //tax
    //totalprice
		$startDate	= $voucherItemdata['slbinfo']['startDate'] ?? null;
		$endDate		= $voucherItemdata['slbinfo']['endDate'] ?? null;

		$userAssetModel = new UserAssetModel;
		$userAssetModel->uasUUID						= $key;
		$userAssetModel->uasActorID         = $userid;
		$userAssetModel->uasSaleableID      = $slbid;
		$userAssetModel->uasQty             = $qty;
		$userAssetModel->uasVoucherID       = $voucherID;
		$userAssetModel->uasVoucherItemInfo = $voucherItemdata;
		// $userAssetModel->uasDiscountID        =
		// $userAssetModel->uasDiscountAmount  =
		// $userAssetModel->uasPrefered        =
		$userAssetModel->uasValidFromDate   = $startDate;
		$userAssetModel->uasValidToDate     = $endDate;
		// $userAssetModel->uasValidFromHour   =
		// $userAssetModel->uasValidToHour     =
		// $userAssetModel->uasDurationMinutes =
		// $userAssetModel->uasBreakedAt       =

		if ($saleableModel->product->prdMhaType == enuMhaProductType::Membership) {
			$userAssetModel->uasStatus = enuUserAssetStatus::Active;
		} else if ($saleableModel->product->prdMhaType == enuMhaProductType::MembershipCard) {
			// $userAssetModel->uasStatus = wait for card print
		} else {
			throw new UnprocessableEntityHttpException("Invalid mha product type ({$saleableModel->product->prdMhaType})");
		}

		if ($userAssetModel->save() == false)
			throw new ServerErrorHttpException('It is not possible to create user asset');

		//2: ?
		if ($saleableModel->product->prdMhaType == enuMhaProductType::Membership) {
			self::ProcessVoucherItem_Membership($userAssetModel, $voucherItemdata);
		} else if ($saleableModel->product->prdMhaType == enuMhaProductType::MembershipCard) {
			self::ProcessVoucherItem_MembershipCard($userAssetModel, $voucherItemdata);
		}
	}

	public static function ProcessVoucherItem_Membership($userAssetModel, $voucherItemdata)
	{
		$memberModel = MemberModel::find()
			->andWhere(['mbrUserID' => $userAssetModel->uasActorID])
			->one();

		$memberModel->mbrExpireDate = $userAssetModel->uasValidToDate;

		$memberModel->save();
	}

	public static function ProcessVoucherItem_MembershipCard($userAssetModel, $voucherItemdata)
	{
	}

}
