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
	public $years;
	public $maxYears;
	public $saleableModels;
	public $saleableID;
	public $memberModel;

	public function rules()
	{
		return [
			['ofpID', 'integer'],
			// ['startDate', 'safe'],
			['years', 'required'],
			['saleableID', 'required'],

			// ['discountCode', 'string'],
			// ['printCard', 'safe'],
		];
	}

	public function attributeLabels()
	{
		return [
			'memberID'				=> Yii::t('mha', 'Member'),
			'startDate'				=> Yii::t('app', 'Start Date'),
			'years'						=> Yii::t('app', 'Year'),
			'saleableID'			=> Yii::t('aaa', 'Saleable'),
		];
	}

	public function load($data, $formName = null)
	{
		$loaded = parent::load($data, $formName);

		list (
			$startDate,
			$maxYears,
			$saleableModels,
			$memberModel
		) = $this->getRenewalInfo();

		$this->startDate			= $startDate;
		$this->maxYears				= $maxYears;
		$this->saleableModels	= $saleableModels;
		$this->memberModel		= $memberModel;

		if (empty($this->years))
			$this->years = 1;

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
			$resultData['saleableModels'],
			$resultData['memberModel'],
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
