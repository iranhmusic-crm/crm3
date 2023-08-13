<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\models;

use Yii;
use shopack\base\frontend\rest\RestClientActiveRecord;

class MemberSpecialtyModel extends RestClientActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\MemberSpecialtyModelTrait;

	public static $resourceName = 'mha/member-specialty';
  public static $primaryKey = 'mbrspcID'; //['mbrspcMemberID', 'mbrspcSpecialtyID'];

	public $form_specialties;

	public function attributeLabels()
	{
		return [
			'mbrspcMemberID'         => Yii::t('mha', 'Member'),
			'mbrspcSpecialtyID'      => Yii::t('mha', 'Specialty'),
			'mbrspcDesc'             => Yii::t('app', 'Description'),
			'mbrspcCreatedAt'        => Yii::t('app', 'Created At'),
			'mbrspcCreatedBy'        => Yii::t('app', 'Created By'),
			'mbrspcCreatedBy_User'   => Yii::t('app', 'Created By'),
			'mbrspcUpdatedAt'        => Yii::t('app', 'Updated At'),
			'mbrspcUpdatedBy'        => Yii::t('app', 'Updated By'),
			'mbrspcUpdatedBy_User'   => Yii::t('app', 'Updated By'),
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
      // $this->specialty->load($data);
		} catch (\Throwable $exp) {}

		return $ret;
	}

}
