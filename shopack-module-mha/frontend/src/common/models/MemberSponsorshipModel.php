<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\models;

use Yii;
use shopack\base\frontend\rest\RestClientActiveRecord;
use iranhmusic\shopack\mha\frontend\common\models\MemberModel;
use iranhmusic\shopack\mha\frontend\common\models\SponsorshipModel;

class MemberSponsorshipModel extends RestClientActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\MemberSponsorshipModelTrait;

	public static $resourceName = 'mha/member-sponsorship';
  public static $primaryKey = 'mbrspsID';

	public function attributeLabels()
	{
		return [
			'mbrspsID'               => Yii::t('app', 'ID'),
			'mbrspsMemberID'         => Yii::t('mha', 'Member'),
			'mbrspsType'             => Yii::t('mha', 'Relation Type'),
			'mbrspsShID'             => Yii::t('aaa', 'ShID'),
			'mbrspsSSN'              => Yii::t('aaa', 'SSN'),
			'mbrspsGender'           => Yii::t('aaa', 'Gender'),
			'mbrspsFirstName'        => Yii::t('aaa', 'First Name'),
			'mbrspsLastName'         => Yii::t('aaa', 'Last Name'),
			'mbrspsFatherName'       => Yii::t('aaa', 'Father Name'),
			'mbrspsBirthDate'        => Yii::t('aaa', 'Birth Date'),
			'mbrspsBirthLocation'    => Yii::t('aaa', 'Birth Location'),
			'mbrspsMasterInsTypeID'  => Yii::t('mha', 'Master Insurer Type'),
			'mbrspsSubstation'       => Yii::t('mha', 'Substation'),
			'mbrspsInsuranceCode'    => Yii::t('mha', 'Insurance Code'),
			'mbrspsCreatedAt'        => Yii::t('app', 'Created At'),
			'mbrspsCreatedBy'        => Yii::t('app', 'Created By'),
			'mbrspsCreatedBy_User'   => Yii::t('app', 'Created By'),
			'mbrspsUpdatedAt'        => Yii::t('app', 'Updated At'),
			'mbrspsUpdatedBy'        => Yii::t('app', 'Updated By'),
			'mbrspsUpdatedBy_User'   => Yii::t('app', 'Updated By'),
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
