<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\accounting\models;

use Yii;
use yii\base\Model;
use yii\db\Expression;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;
use shopack\base\common\helpers\Json;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\common\security\RsaPrivate;
use shopack\base\common\accounting\enums\enuSaleableStatus;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuMembershipStatus;
use iranhmusic\shopack\mha\backend\models\MemberModel;
use iranhmusic\shopack\mha\backend\accounting\models\UserAssetModel;
use iranhmusic\shopack\mha\backend\accounting\models\SaleableModel;
use iranhmusic\shopack\mha\common\accounting\enums\enuMhaProductType;

//todo: (vi) must be deprecated?

class MembershipCardForm extends Model
{
	//list ($membershipUserAssetID, $price, $saleableModel, $lastMembership)
	public static function getRenewalInfo($memberID)
	{
		if (empty($memberID))
			throw new UnprocessableEntityHttpException('The MemberID not provided');

		$memberModel = MemberModel::find()->where(['mbrUserID' => $memberID])->one();
		if ($memberModel == null)
			throw new NotFoundHttpException('The requested item does not exist.');

		if (empty($memberModel->mbrRegisterCode))
			throw new UnprocessableEntityHttpException('The member does not have a register code');

		if (empty($memberModel->mbrAcceptedAt))
			throw new UnprocessableEntityHttpException('Membership start date is blank');

		$lastMembership = UserAssetModel::find()
			->joinWith('saleable', false, 'INNER JOIN')
			->joinWith('saleable.product', false, 'INNER JOIN')
			->andWhere(['uasActorID' => $memberID])
			->andWhere(['>=', 'uasValidToDate', new Expression('NOW()')])
			->orderBy('uasValidToDate DESC')
			->one();

		if ($lastMembership == null)
			throw new UnprocessableEntityHttpException('Active Membership not found');

		$saleableModel = SaleableModel::find()
			->joinWith('product', false, 'INNER JOIN')
			->andWhere(['prdMhaType' => enuMhaProductType::MembershipCard])
			->andWhere(['<=', 'slbAvailableFromDate', new Expression('NOW()')])
			->andWhere(['slbStatus' => enuSaleableStatus::Active])
			->orderBy('slbAvailableFromDate DESC')
			->one();

		if ($saleableModel == null)
			throw new NotFoundHttpException('Definition of membership card at this date was not found.');

		return [$lastMembership->uasID, $saleableModel->slbBasePrice, $saleableModel, $lastMembership];
	}

	public static function addToBasket($basketdata, $saleableID = null)
	{
		if (is_string($basketdata))
			$basketdata = Json::decode(base64_decode($basketdata));

		if ($basketdata === null)
			$basketdata = [];

		//get saleable info
		list ($membershipUserAssetID, $price, $saleableModel, $lastMembership) = self::getRenewalInfo(Yii::$app->user->id);

		$parentModule = Yii::$app->topModule;

		$data = [
			'userid' => Yii::$app->user->id,
			'items' => [
				[//1: membership card
					'service'		=> $parentModule->id,
					'slbid'			=> $saleableModel->slbID,
					'desc' 			=> $saleableModel->slbName,
					'qty'				=> 1,
					'unit'			=> $saleableModel->product->unit->untName,
					'prdtype'		=> $saleableModel->product->prdType,
					'unitprice' => $price,
					'dependencies' => [$lastMembership->uasUUID],
					// 'slbinfo'		=> [
					// 	'membershipUserAssetID' => $membershipUserAssetID,
					// ],
					'maxqty'		=> 1,
					'qtystep'		=> 0, //0: do not allow to change qty in basket
				],
			],
		];
		$data = Json::encode($data);

		if (empty($parentModule->servicePrivateKey))
			$data = base64_encode($data);
		else
			$data = RsaPrivate::model($parentModule->servicePrivateKey)->encrypt($data);

		list ($resultStatus, $resultData) = HttpHelper::callApi('aaa/basket/item',
			HttpHelper::METHOD_POST,
			[],
			[
				'data' => $data,
				'service'	=> $parentModule->id,
			]
		);

		if ($resultStatus < 200 || $resultStatus >= 300)
			throw new \yii\web\HttpException($resultStatus, Yii::t('mha', $resultData['message'], $resultData));

		return $resultData;
	}

}
