<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\models;

use Yii;
use shopack\base\frontend\rest\RestClientActiveRecord;
use iranhmusic\shopack\mha\frontend\common\models\MemberModel;
use iranhmusic\shopack\mha\frontend\common\models\MasterInsuranceModel;

class MemberMasterInsuranceModel extends RestClientActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\MemberMasterInsuranceModelTrait;

	public static $resourceName = 'mha/member-master-insurance';
  public static $primaryKey = 'mbrminshstID';

	public function attributeLabels()
	{
		return [
			'mbrminshstMemberID'         => Yii::t('mha', 'Member'),
			'mbrminshstMasterInsTypeID'  => Yii::t('mha', 'Master Insurer Type'),
			'mbrminshstSubstation'       => Yii::t('mha', 'Substation'),
			'mbrminshstStartDate'        => Yii::t('app', 'Start Date'),
			'mbrminshstEndDate'          => Yii::t('app', 'End Date'),
			'mbrminshstInsuranceCode'    => Yii::t('mha', 'Insurance Code'),
			'mbrminshstCoCode'           => Yii::t('mha', 'Company Code'),
			'mbrminshstCoName'           => Yii::t('mha', 'Company Name'),
			'mbrminshstIssuanceDate'     => Yii::t('mha', 'Issuance Date'),
			'mbrminshstCreatedAt'        => Yii::t('app', 'Created At'),
			'mbrminshstCreatedBy'        => Yii::t('app', 'Created By'),
			'mbrminshstCreatedBy_User'   => Yii::t('app', 'Created By'),
			'mbrminshstUpdatedAt'        => Yii::t('app', 'Updated At'),
			'mbrminshstUpdatedBy'        => Yii::t('app', 'Updated By'),
			'mbrminshstUpdatedBy_User'   => Yii::t('app', 'Updated By'),
		];
	}

	public function isSoftDeleted()
  {
    return false;
  }

	public static function canCreate() {
		return true;
	}

	public function canUpdate() {
		return true;
	}

	public function canDelete() {
		return true;
	}

	public function canUndelete() {
		return false;
	}

	public function load($data, $formName = null) {
		$ret = parent::load($data, $formName);

		//load relations
		try {
      // $this->member->load($data);
      // $this->masterInsuranceType->load($data);
		} catch (\Throwable $exp) {}

		return $ret;
	}

}
