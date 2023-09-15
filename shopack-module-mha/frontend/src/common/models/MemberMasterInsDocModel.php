<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\models;

use Yii;
use shopack\base\frontend\common\rest\RestClientActiveRecord;
use iranhmusic\shopack\mha\frontend\common\models\MemberModel;
use iranhmusic\shopack\mha\frontend\common\models\MasterInsuranceModel;
use iranhmusic\shopack\mha\common\enums\enuInsurerDocStatus;

class MemberMasterInsDocModel extends RestClientActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\MemberMasterInsDocModelTrait;

	public static $resourceName = 'mha/member-master-ins-doc';

	public function attributeLabels()
	{
		return [
			'mbrminsdocID'               => Yii::t('app', 'ID'),
			'mbrminsdocMemberID'         => Yii::t('mha', 'Member'),
			'mbrminsdocDocNumber'        => Yii::t('mha', 'Document Number'),
			'mbrminsdocDocDate'          => Yii::t('mha', 'Document Date'),
			'mbrminsdocHistory'          => Yii::t('mha', 'History'),
			'mbrminsdocStatus'           => Yii::t('app', 'Status'),
			'mbrminsdocCreatedAt'        => Yii::t('app', 'Created At'),
			'mbrminsdocCreatedBy'        => Yii::t('app', 'Created By'),
			'mbrminsdocCreatedBy_User'   => Yii::t('app', 'Created By'),
			'mbrminsdocUpdatedAt'        => Yii::t('app', 'Updated At'),
			'mbrminsdocUpdatedBy'        => Yii::t('app', 'Updated By'),
			'mbrminsdocUpdatedBy_User'   => Yii::t('app', 'Updated By'),
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

	public function canAccept() {
		return ($this->mbrminsdocStatus == enuInsurerDocStatus::WaitForSurvey);
	}
	public function canReject() {
		return ($this->mbrminsdocStatus == enuInsurerDocStatus::WaitForSurvey);
	}
	public function canWaitForDocument() {
		return ($this->mbrminsdocStatus == enuInsurerDocStatus::Accepted);
	}
	public function canSetAsDocumented() {
		return ($this->mbrminsdocStatus == enuInsurerDocStatus::WaitForDocument);
	}
	public function canSetAsDelivered() {
		return ($this->mbrminsdocStatus == enuInsurerDocStatus::Documented);
	}

}
