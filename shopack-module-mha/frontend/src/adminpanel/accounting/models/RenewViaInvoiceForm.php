<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\accounting\models;

use Yii;
use yii\base\Model;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use shopack\base\common\helpers\HttpHelper;

// use shopack\base\frontend\common\rest\RestClientActiveRecord;
// use iranhmusic\shopack\mha\common\enums\enuMembershipStatus;
// use iranhmusic\shopack\mha\frontend\common\models\MemberMembershipModel;
// use iranhmusic\shopack\mha\frontend\common\models\MembershipModel;

class RenewViaInvoiceForm extends Model
{
	public $memberID;
	public $ofpID;

	public $startDate;
	public $endDate;
	public $years;
	public $unitPrice;
	public $totalPrice;
	public $saleableID;
	public $discountCode;
	public $printCard = true;
	public $printCardAmount;

	public function rules()
	{
		return [
			['ofpID', 'integer'],
			// ['startDate', 'safe'],
			['years', 'required'],

			// ['discountCode', 'string'],
			// ['printCard', 'safe'],
		];
	}

	// public function attributeLabels()
	// {
	// 	return [
	// 		'startDate'				=> Yii::t('app', 'Start Date'),
	// 		'endDate'					=> Yii::t('app', 'End Date'),
	// 		'years'						=> Yii::t('app', 'Year'),
	// 		'unitPrice'				=> Yii::t('aaa', 'Unit Price'),
	// 		'totalPrice'			=> Yii::t('aaa', 'Total Price'),
	// 		'saleableID'			=> Yii::t('aaa', 'Saleable'),
	// 		'discountCode'		=> Yii::t('aaa', 'Discount Code'),
	// 		'printCard'				=> Yii::t('mha', 'Print Card'),
	// 		'printCardAmount'	=> Yii::t('mha', 'Card Print Price'),
	// 	];
	// }

	public function load($data, $formName = null)
	{
		$loaded = parent::load($data, $formName);
		if ($loaded)
			return true;

		list ($startDate, $endDate, $years, $unitPrice, $totalPrice, $saleableID, $printCardAmount) =
			$this->getRenewalInfo();

		$this->startDate	= $startDate;
		$this->endDate		= $endDate;
		$this->years			= $years;
		$this->unitPrice	= $unitPrice;
		$this->totalPrice	= $totalPrice;
		$this->saleableID	= $saleableID;
		$this->printCardAmount	= $printCardAmount;

		return false;
	}

	public function getRenewalInfo()
	{
		list ($resultStatus, $resultData) = HttpHelper::callApi('mha/accounting/membership/renewal-info-for-invoice',
			HttpHelper::METHOD_GET,
			[
				'memberID' => $this->memberID,
				'ofpID' => $this->ofpID,
			]
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
			$resultData['printCardAmount'],
		];
	}

	public function process()
	{
		try {
			list ($resultStatus, $resultData) = HttpHelper::callApi('mha/accounting/membership/renew-via-invoice',
				HttpHelper::METHOD_POST,
				[],
				[
					'ofpID' => $this->ofpID,
				]
			);

			if ($resultStatus < 200 || $resultStatus >= 300)
				throw new \yii\web\HttpException($resultStatus, Yii::t('mha', $resultData['message'], $resultData));

			return true; //$resultData;

		} catch (\Throwable $th) {
			if (YII_ENV_DEV)
				throw $th;

			$this->addError('', Yii::t('mha', $th->getMessage()));
			return false;
		}

	}

}
