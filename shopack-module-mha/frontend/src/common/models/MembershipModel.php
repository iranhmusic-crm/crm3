<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\models;

use Yii;
use shopack\base\frontend\rest\RestClientActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuMembershipStatus;
use shopack\base\common\shop\ISaleableEntity;
use shopack\base\common\helpers\HttpHelper;

class MembershipModel extends RestClientActiveRecord implements ISaleableEntity
{
	use \iranhmusic\shopack\mha\common\models\MembershipModelTrait;

	public static $resourceName = 'mha/membership';
  public static $primaryKey = ['mshpID'];

	public function attributeLabels()
	{
		return [
			'mshpID'                    => Yii::t('app', 'ID'),
			'mshpTitle'                 => Yii::t('app', 'Title'),
			'mshpStartFrom'             => Yii::t('app', 'Start From'),
			'mshpYearlyPrice'           => Yii::t('mha', 'Yearly Price'),
			'mshpStatus'                => Yii::t('app', 'Status'),
			'mshpCreatedAt'             => Yii::t('app', 'Created At'),
			'mshpCreatedBy'             => Yii::t('app', 'Created By'),
			'mshpCreatedBy_User'        => Yii::t('app', 'Created By'),
			'mshpUpdatedAt'             => Yii::t('app', 'Updated At'),
			'mshpUpdatedBy'             => Yii::t('app', 'Updated By'),
			'mshpUpdatedBy_User'        => Yii::t('app', 'Updated By'),
			'mshpRemovedAt'             => Yii::t('app', 'Removed At'),
			'mshpRemovedBy'             => Yii::t('app', 'Removed By'),
			'mshpRemovedBy_User'        => Yii::t('app', 'Removed By'),
		];
	}

	public function isSoftDeleted()
  {
    return ($this->mshpStatus == enuMembershipStatus::Removed);
  }

	public static function canCreate() {
		return true;
	}

	public function canUpdate() {
		return ($this->mshpStatus != enuMembershipStatus::Removed);
	}

	public function canDelete() {
		return ($this->mshpStatus != enuMembershipStatus::Removed);
	}

	public function canUndelete() {
		return ($this->mshpStatus == enuMembershipStatus::Removed);
	}

	//ISaleableEntity:
	public static function saleableKey()
	{
		return 'mbrshp';
	}

	public static function addToBasket($basketdata, $saleableID = null)
	{
		list ($resultStatus, $resultData) = HttpHelper::callApi('mha/membership/add-to-basket',
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

		return true;
	}

}
