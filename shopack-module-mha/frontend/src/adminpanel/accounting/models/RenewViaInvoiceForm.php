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

	public $memberModel;
	public $offlinePaymentModel;
	public $membershipSaleableModels;
	public $membershipCardSaleableModels;

	public $startDate;
	public $years;
	public $maxYears;
	public $membershipSaleableID;
	public $membershipCardSaleableID;

	public function rules()
	{
		return [
			['ofpID', 'integer'],
			// ['startDate', 'safe'],
			['years', 'required'],
			['membershipSaleableID', 'required'],
			['membershipCardSaleableID', 'required'],

			// ['discountCode', 'string'],
			// ['printCard', 'safe'],
		];
	}

	public function attributeLabels()
	{
		return [
			'memberID'									=> Yii::t('mha', 'Member'),
			'ofpID'											=> 'پرداخت آفلاین',
			'startDate'									=> 'تاریخ شروع دوره عضویت',
			'years'											=> 'طول دوره',
			'membershipSaleableID'			=> 'دوره عضویت',
			'membershipCardSaleableID'	=> 'چاپ کارت',
		];
	}

	public function load($data, $formName = null)
	{
		$loaded = parent::load($data, $formName);

		list (
			$startDate,
			$maxYears,
			$memberModel,
			$offlinePaymentModel,
			$membershipSaleableModels,
			$membershipCardSaleableModels
		) = $this->getRenewalInfo();

		$this->startDate										= $startDate;
		$this->maxYears											= $maxYears;
		$this->memberModel									= $memberModel;
		$this->offlinePaymentModel					= $offlinePaymentModel;
		$this->membershipSaleableModels			=	$membershipSaleableModels;
		$this->membershipCardSaleableModels	= $membershipCardSaleableModels;

		if (empty($this->years))
			$this->years = 1;

		if (empty($this->membershipSaleableID))
			$this->membershipSaleableID = $this->membershipSaleableModels[0]['slbID'];

		return $loaded;
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
			$resultData['maxYears'],
			$resultData['memberModel'],
			$resultData['offlinePaymentModel'],
			$resultData['membershipSaleableModels'],
			$resultData['membershipCardSaleableModels'],
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
