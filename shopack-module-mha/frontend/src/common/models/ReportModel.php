<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\models;

use Yii;
use shopack\base\frontend\rest\RestClientActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuReportStatus;
use shopack\base\common\helpers\ArrayHelper;
use shopack\base\frontend\rest\RestClientDataProvider;

class ReportModel extends RestClientActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\ReportModelTrait;

	public static $resourceName = 'mha/report';
  public static $primaryKey = ['rptID'];

	public function attributeLabels()
	{
		return [
			'rptID'                   => Yii::t('app', 'ID'),
			'rptName'                 => Yii::t('app', 'Name'),
			'rptType'                	=> Yii::t('app', 'Type'),
			'rptInputFields'          => Yii::t('app', 'Input Fields'),
			'rptOutputFields'         => Yii::t('app', 'Output Fields'),
			'rptStatus'               => Yii::t('app', 'Status'),
			'rptCreatedAt'            => Yii::t('app', 'Created At'),
			'rptCreatedBy'            => Yii::t('app', 'Created By'),
			'rptCreatedBy_User'       => Yii::t('app', 'Created By'),
			'rptUpdatedAt'            => Yii::t('app', 'Updated At'),
			'rptUpdatedBy'            => Yii::t('app', 'Updated By'),
			'rptUpdatedBy_User'       => Yii::t('app', 'Updated By'),
			'rptRemovedAt'            => Yii::t('app', 'Removed At'),
			'rptRemovedBy'            => Yii::t('app', 'Removed By'),
			'rptRemovedBy_User'       => Yii::t('app', 'Removed By'),
		];
	}

	public function isSoftDeleted()
  {
    return ($this->rptStatus == enuReportStatus::Removed);
  }

	public static function canCreate() {
		return true;
	}

	public function canUpdate() {
		return ($this->rptStatus != enuReportStatus::Removed);
	}

	public function canDelete() {
		return ($this->rptStatus != enuReportStatus::Removed);
	}

	public function canUndelete() {
		return ($this->rptStatus == enuReportStatus::Removed);
	}

	public function save($runValidation = true, $attributeNames = null)
  {
		$this->rptInputFields = ArrayHelper::filterNullOrEmpty($this->rptInputFields);
		$this->rptOutputFields = ArrayHelper::filterNullOrEmpty($this->rptOutputFields, true);

		if (empty($this->rptOutputFields)) {
			$this->addError(null, 'ستون‌های خروجی مشخص نشده‌اند');
			return false;
		}

		//---------------------
    return parent::save($runValidation, $attributeNames);
  }

	public function run()
	{
		$query = self::find()
			->endpoint('run')
			// ->limit(null)
			// ->offset(null)
			->addUrlParameter('id', $this->rptID)
		;

		$dataProvider = new RestClientDataProvider([
			'query' => $query,
			// 'pagination' => false, //prevent HEAD request
			// 'sort' => [
			// 	// 'enableMultiSort' => true,
			// 	'attributes' => [
			// 		'docID',
			// 		'docName',
			// 		'docType',
			// 		'docCreatedAt' => [
			// 			'default' => SORT_DESC,
			// 		],
			// 		'docCreatedBy',
			// 		'docUpdatedAt' => [
			// 			'default' => SORT_DESC,
			// 		],
			// 		'docUpdatedBy',
			// 		'docRemovedAt' => [
			// 			'default' => SORT_DESC,
			// 		],
			// 		'docRemovedBy',
			// 	],
			// ],
		]);

		return $dataProvider;

		// // $response = self::find()->restExecute('get', 'documentTypesForMember', [
		// // 	'memberID' => $memberID,
		// // ]);

		// $result = HttpHelper::callApi(self::$resourceName . "/member-document-types", HttpHelper::METHOD_GET, [
		// 	'memberID' => $memberID,
		// ]);

		// if ($result && $result[0] == 200) {
		// 	$list = $result[1];


		// }

		// return null;
	}

}
