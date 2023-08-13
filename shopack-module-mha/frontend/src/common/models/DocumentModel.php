<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\models;

use Yii;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\frontend\rest\RestClientActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuDocumentStatus;

class DocumentModel extends RestClientActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\DocumentModelTrait;

	public static $resourceName = 'mha/document';
  public static $primaryKey = ['docID'];

	public function attributeLabels()
	{
		return [
			'docID'                    => Yii::t('app', 'ID'),
			'docName'                  => Yii::t('app', 'Name'),
			'docType'                  => Yii::t('app', 'Type'),
			'docStatus'                => Yii::t('app', 'Status'),
			'docCreatedAt'             => Yii::t('app', 'Created At'),
			'docCreatedBy'             => Yii::t('app', 'Created By'),
			'docCreatedBy_User'        => Yii::t('app', 'Created By'),
			'docUpdatedAt'             => Yii::t('app', 'Updated At'),
			'docUpdatedBy'             => Yii::t('app', 'Updated By'),
			'docUpdatedBy_User'        => Yii::t('app', 'Updated By'),
			'docRemovedAt'             => Yii::t('app', 'Removed At'),
			'docRemovedBy'             => Yii::t('app', 'Removed By'),
			'docRemovedBy_User'        => Yii::t('app', 'Removed By'),
		];
	}

	public function isSoftDeleted()
  {
    return ($this->docStatus == enuDocumentStatus::Removed);
  }

	public static function canCreate() {
		return true;
	}

	public function canUpdate() {
		return ($this->docType != enuDocumentStatus::Removed);
	}

	public function canDelete() {
		return ($this->docType != enuDocumentStatus::Removed);
	}

	public function canUndelete() {
		return ($this->docType == enuDocumentStatus::Removed);
	}

}
