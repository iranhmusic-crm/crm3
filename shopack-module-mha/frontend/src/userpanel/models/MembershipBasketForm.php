<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\userpanel\models;

use Yii;
use yii\base\Model;
use shopack\base\frontend\rest\RestClientActiveRecord;
use shopack\base\common\shop\ISaleableEntity;
use shopack\base\common\helpers\HttpHelper;
use iranhmusic\shopack\mha\common\enums\enuMembershipStatus;
use iranhmusic\shopack\mha\frontend\common\models\MemberMembershipModel;
use iranhmusic\shopack\mha\frontend\common\models\MembershipModel;

class MembershipBasketForm extends Model
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
			MemberMembershipModel::getRenewalInfo();

		$this->startDate	= $startDate;
		$this->endDate		= $endDate;
		$this->years			= $years;
		$this->unitPrice	= $unitPrice;
		$this->totalPrice	= $totalPrice;
		$this->saleableID	= $saleableID;

		return false;
	}

	public function addToBasket($basketdata, $saleableID = null)
	{
		try {
			return MembershipModel::addToBasket($basketdata, $saleableID);

		} catch (\Throwable $th) {
			if (YII_ENV_DEV)
				throw $th;

			$this->addError('', $th->getMessage());
			return false;
		}

	}

}
