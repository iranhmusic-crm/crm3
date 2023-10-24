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

class MembershipForm extends Model
{
	public $startDate;
	public $endDate;
	public $years;
	public $unitPrice;
	public $totalPrice;
	public $saleableID;

	public function attributeLabels()
	{
		return [
			'startDate'		=> Yii::t('app', 'Start Date'),
			'endDate'			=> Yii::t('app', 'End Date'),
			'years'				=> Yii::t('app', 'Year'),
			'unitPrice'		=> Yii::t('aaa', 'Unit Price'),
			'totalPrice'	=> Yii::t('aaa', 'Total Price'),
			'saleableID'	=> Yii::t('aaa', 'Saleable'),
		];
	}

	public function load($data, $formName = null)
	{
		if (parent::load($data, $formName))
			return true;

		list ($startDate, $endDate, $years, $unitPrice, $totalPrice, $saleableID) =
			self::getRenewalInfo();

		$this->startDate	= $startDate;
		$this->endDate		= $endDate;
		$this->years			= $years;
		$this->unitPrice	= $unitPrice;
		$this->totalPrice	= $totalPrice;
		$this->saleableID	= $saleableID;

		return false;
	}

	public static function getRenewalInfo()
	{
		list ($resultStatus, $resultData) = HttpHelper::callApi('mha/accounting/membership/renewal-info',
			HttpHelper::METHOD_GET,
			// [
			// 	'memberID' => Yii::$app->user->id,
			// ]
		);

		if ($resultStatus < 200 || $resultStatus >= 300)
			throw new \yii\web\HttpException($resultStatus, Yii::t('mha', $resultData['message'], $resultData));

		return [
			$resultData['startDate'],
			$resultData['endDate'],
			$resultData['years'],
			$resultData['unitPrice'],
			$resultData['totalPrice'],
			$resultData['saleableID'],
		];
	}

	public function addToBasket($basketdata, $saleableID = null)
	{
		try {
			list ($resultStatus, $resultData) = HttpHelper::callApi('mha/accounting/membership/add-to-basket',
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
