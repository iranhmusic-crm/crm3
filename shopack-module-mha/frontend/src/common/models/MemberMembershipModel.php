<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\models;

use Yii;
use yii\web\UnprocessableEntityHttpException;
use shopack\base\frontend\rest\RestClientActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuMemberMembershipStatus;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\common\shop\IAssetEntity;

class MemberMembershipModel extends RestClientActiveRecord implements IAssetEntity
{
	use \iranhmusic\shopack\mha\common\models\MemberMembershipModelTrait;

	public static $resourceName = 'mha/member-membership';
	public static $primaryKey = 'mbrshpID';

	public function attributeLabels()
	{
		return [
			'mbrshpMemberID'         => Yii::t('mha', 'Member'),
			'mbrshpMembershipID'     => Yii::t('mha', 'Membership'),
			'mbrshpVoucherID'        => Yii::t('aaa', 'Order'), //'Voucher'),
			'mbrshpStartDate'        => Yii::t('app', 'Start Date'),
			'mbrshpEndDate'          => Yii::t('app', 'End Date'),
			'mbrshpStatus'           => Yii::t('app', 'Status'),
			'mbrshpCreatedAt'        => Yii::t('app', 'Created At'),
			'mbrshpCreatedBy'        => Yii::t('app', 'Created By'),
			'mbrshpCreatedBy_User'   => Yii::t('app', 'Created By'),
			'mbrshpUpdatedAt'        => Yii::t('app', 'Updated At'),
			'mbrshpUpdatedBy'        => Yii::t('app', 'Updated By'),
			'mbrshpUpdatedBy_User'   => Yii::t('app', 'Updated By'),
		];
	}

	public function load($data, $formName = null) {
		$ret = parent::load($data, $formName);

		//load relations
		try {
      // $this->member->load($data);
      // $this->membership->load($data);
		} catch (\Throwable $exp) {}

		return $ret;
	}

	public function isSoftDeleted()
  {
    return false;
  }

	public static function canCreate()
	{
		return true;

		// return ((empty($this->member->mbrRegisterCode) == false)
		// 	   && (empty($this->member->mbrAcceptedAt) == false));
	}

	// public function prepareForCreation()
	// {
	// 	list ($resultStatus, $resultData) = HttpHelper::callApi('mha/member-membership/renewal-info',
	// 		HttpHelper::METHOD_GET,
	// 		// [
	// 		// 	'memberID' => Yii::$app->user->id,
	// 		// ]
	// 	);

	// 	if ($resultStatus < 200 || $resultStatus >= 300)
	// 		throw new \yii\web\HttpException($resultStatus, Yii::t('mha', $resultData['message'], $resultData));

	// 	$this->mbrshpStartDate = $resultData['startDate'];
	// 	$this->mbrshpEndDate = $resultData['endDate'];
	// 	$this->years = $resultData['years'];
	// 	$this->unitPrice = $resultData['unitPrice'];
	// 	$this->totalPrice = $resultData['totalPrice'];

	// 	return true; //[$resultStatus, $resultData['result']];
	// }

	public static function getRenewalInfo()
	{
		list ($resultStatus, $resultData) = HttpHelper::callApi('mha/member-membership/renewal-info',
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

	// public function insert($runValidation = true, $attributes = null)
	// {
	// 	//call add to basket

  //   return parent::insert($runValidation, $attributes);
  // }

}
