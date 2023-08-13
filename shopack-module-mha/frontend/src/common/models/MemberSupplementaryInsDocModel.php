<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\models;

use Yii;
use shopack\base\frontend\rest\RestClientActiveRecord;
use iranhmusic\shopack\mha\frontend\common\models\MemberModel;
use iranhmusic\shopack\mha\frontend\common\models\MasterInsuranceModel;
use iranhmusic\shopack\mha\common\enums\enuInsurerDocStatus;

class MemberSupplementaryInsDocModel extends RestClientActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\MemberSupplementaryInsDocModelTrait;

	public static $resourceName = 'mha/member-supplementary-ins-doc';
  public static $primaryKey = 'mbrsinsdocID';

	public function attributeLabels()
	{
		return [
			'mbrsinsdocID'                     => Yii::t('app', 'ID'),
			'mbrsinsdocMemberID'               => Yii::t('mha', 'Member'),
			'mbrsinsdocSupplementaryInsurerID' => Yii::t('mha', 'Supplementary Insurer'),
			'mbrsinsdocDocNumber'              => Yii::t('mha', 'Document Number'),
			'mbrsinsdocDocDate'                => Yii::t('mha', 'Document Date'),
			'mbrsinsdocHistory'                => Yii::t('mha', 'History'),
			'mbrsinsdocStatus'                 => Yii::t('app', 'Status'),
			'mbrsinsdocCreatedAt'              => Yii::t('app', 'Created At'),
			'mbrsinsdocCreatedBy'              => Yii::t('app', 'Created By'),
			'mbrsinsdocCreatedBy_User'         => Yii::t('app', 'Created By'),
			'mbrsinsdocUpdatedAt'              => Yii::t('app', 'Updated At'),
			'mbrsinsdocUpdatedBy'              => Yii::t('app', 'Updated By'),
			'mbrsinsdocUpdatedBy_User'         => Yii::t('app', 'Updated By'),
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
		return ($this->mbrsinsdocStatus == enuInsurerDocStatus::WaitForSurvey);
	}
	public function canReject() {
		return ($this->mbrsinsdocStatus == enuInsurerDocStatus::WaitForSurvey);
	}
	public function canWaitForDocument() {
		return ($this->mbrsinsdocStatus == enuInsurerDocStatus::Accepted);
	}
	public function canSetAsDocumented() {
		return ($this->mbrsinsdocStatus == enuInsurerDocStatus::WaitForDocument);
	}
	public function canSetAsDelivered() {
		return ($this->mbrsinsdocStatus == enuInsurerDocStatus::Documented);
	}

}
