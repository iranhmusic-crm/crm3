<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel\accounting\models;

use Yii;
use yii\base\Model;
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

	public $membershipItemKey;
	public $membershipCardItemKey;
	public $invoiceID;

	public function rules()
	{
		return [
			['ofpID', 'integer'],
			// ['startDate', 'safe'],
			['years', 'required'],
			['membershipSaleableID', 'safe'],
			['membershipCardSaleableID', 'safe'],
			// ['membershipSaleableID', 'required'],
			// ['membershipCardSaleableID', 'required'],

			['invoiceID', 'safe'],

      // [[
      //   'membershipSaleableID',
      //   'membershipCardSaleableID',
      // ], GroupRequiredValidator::class,
      //   'min' => 1,
      //   'in' => [
      //     'membershipSaleableID',
      //     'membershipCardSaleableID',
      //   ],
        // 'message' => 'one of email or mobile or ssid is required',
      // ],

			// ['discountCode', 'string'],
			// ['printCard', 'safe'],
		];
	}

	public function attributeLabels()
	{
		return [
			'memberID'									=> Yii::t('mha', 'Member'),
			'ofpID'											=> Yii::t('aaa', 'Offline Payment'),
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

		if (empty($this->memberID))
			$this->memberID = $this->memberModel['mbrUserID'];

		if (empty($this->years))
			$this->years = 1;

		if ($this->membershipSaleableID == null)
			$this->membershipSaleableID = $this->membershipSaleableModels[0]['slbID'];

		return $loaded;
	}

	public function getRenewalInfo()
	{
		$apiResponse = HttpHelper::callApi('mha/accounting/membership/renewal-info-for-invoice',
			HttpHelper::METHOD_GET,
			[
				'memberID' => $this->memberID,
				'ofpID' => $this->ofpID,
			]
		);

    HttpHelper::throwApiResponseIfFailed($apiResponse, 'mha');

		return [
			$apiResponse['body']['startDate'],
			$apiResponse['body']['maxYears'],
			$apiResponse['body']['memberModel'],
			$apiResponse['body']['offlinePaymentModel'],
			$apiResponse['body']['membershipSaleableModels'],
			$apiResponse['body']['membershipCardSaleableModels'],
		];
	}

	public function validate($attributeNames = null, $clearErrors = true)
	{
		if (parent::validate($attributeNames, $clearErrors) == false)
			return false;

		if (empty($this->membershipSaleableID) && empty($this->membershipCardSaleableID)) {
			$this->addErrors([
        'membershipSaleableID' => 'یکی از انواع دوره عضویت یا چاپ کارت را انتخاب کنید',
        'membershipCardSaleableID' => 'یکی از انواع دوره عضویت یا چاپ کارت را انتخاب کنید',
			]);

			return false;
		}

		return true;
	}

	public function process()
	{
		if ($this->validate() == false)
			return false;

		try {
			$apiResponse = HttpHelper::callApi('mha/accounting/membership/renew-via-invoice',
				HttpHelper::METHOD_POST,
				[],
				[
					'memberID'									=> $this->memberID,
					'ofpID'											=> $this->ofpID,
					'invoiceID'									=> $this->invoiceID,
					'years'											=> $this->years,
					'membershipSaleableID'			=> $this->membershipSaleableID,
					'membershipCardSaleableID'	=> $this->membershipCardSaleableID,
				]
			);

			HttpHelper::throwApiResponseIfFailed($apiResponse, 'mha');

			$this->membershipItemKey			= $apiResponse['body']['membershipItemKey'];
			$this->membershipCardItemKey	= $apiResponse['body']['membershipCardItemKey'];
			$this->invoiceID							= $apiResponse['body']['invoiceID'];

			return ((empty($this->membershipItemKey) == false)
				|| (empty($this->membershipCardItemKey) == false));

		} catch (\Throwable $th) {
			if (YII_ENV_DEV)
				throw $th;

			$this->addError('', Yii::t('mha', $th->getMessage()));

			return false;
		}

	}

}
