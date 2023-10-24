<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\userpanel\accounting\models;

use Yii;
use yii\base\Model;
use shopack\base\frontend\common\rest\RestClientActiveRecord;
use shopack\base\common\helpers\HttpHelper;
// use iranhmusic\shopack\mha\common\enums\enuMembershipStatus;
// use iranhmusic\shopack\mha\frontend\common\models\MemberMembershipModel;
// use iranhmusic\shopack\mha\frontend\common\models\MembershipModel;

class MembershipCardForm extends Model
{
	public $membershipUserAssetID;
	public $price;
	public $saleableModel;

	public function attributeLabels()
	{
		return [
			'membershipUserAssetID' => Yii::t('mha', 'Membership'),
			'price' => Yii::t('aaa', 'Price'),
		];
	}

	public function load($data, $formName = null)
	{
		if (parent::load($data, $formName))
			return true;

		list ($membershipUserAssetID, $price, $saleableModel) = self::getRenewalInfo();

		$this->membershipUserAssetID = $membershipUserAssetID;
		$this->price = $price;
		$this->saleableModel = $saleableModel;

		return false;
	}

	public static function getRenewalInfo()
	{
		list ($resultStatus, $resultData) = HttpHelper::callApi('mha/accounting/membership-card/renewal-info',
			HttpHelper::METHOD_GET,
			// [
			// 	'memberID' => Yii::$app->user->id,
			// ]
		);

		if ($resultStatus < 200 || $resultStatus >= 300)
			throw new \yii\web\HttpException($resultStatus, Yii::t('mha', $resultData['message'], $resultData));

		return [
			$resultData['membershipUserAssetID'],
			$resultData['price'],
			$resultData['saleableModel'],
		];
	}

	public function addToBasket($basketdata, $saleableID = null)
	{
		try {
			list ($resultStatus, $resultData) = HttpHelper::callApi('mha/accounting/membership-card/add-to-basket',
				HttpHelper::METHOD_POST,
				[],
				[
					'basketdata' => $basketdata,
				]
			);

			if ($resultStatus < 200 || $resultStatus >= 300)
				throw new \yii\web\HttpException($resultStatus, Yii::t('mha', $resultData['message'], $resultData));

			// $newBase64Basketdata = $resultData['basketdata'];
			// return $newBase64Basketdata;

		} catch (\Throwable $th) {
			if (YII_ENV_DEV)
				throw $th;

			$this->addError('', $th->getMessage());
			return false;
		}

	}

}
