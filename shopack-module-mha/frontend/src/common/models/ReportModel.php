<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\models;

use Yii;
use yii\data\ArrayDataProvider;
use shopack\base\frontend\rest\RestClientActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuReportStatus;
use shopack\base\common\helpers\ArrayHelper;
use shopack\base\common\helpers\HttpHelper;
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
			'rptName'                 => Yii::t('mha', 'Report Title'),
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
		$errors = [];

		$this->rptInputFields = ArrayHelper::filterNullOrEmpty($this->rptInputFields);
		if (empty($this->rptInputFields))
			$errors[] = 'فیلترهای ورودی مشخص نشده‌اند';

		$this->rptOutputFields = ArrayHelper::filterNullOrEmpty($this->rptOutputFields, true);
		if (empty($this->rptOutputFields))
			$errors[] = 'ستون‌های خروجی مشخص نشده‌اند';

		if (empty($errors) == false) {
			$this->addError(null, $errors);
			return false;
		}

		//---------------------
    return parent::save($runValidation, $attributeNames);
  }

	public function run()
	{
		$params = ['id' => $this->rptID];

		if (empty($_GET['sort']) == false) $params['sort'] = $_GET['sort'];
		if (empty($_GET['page']) == false) $params['page'] = $_GET['page'];
		if (empty($_GET['per-page']) == false) $params['per-page'] = $_GET['per-page'];

		$result = HttpHelper::callApi(self::$resourceName . "/run", HttpHelper::METHOD_GET, $params);

    if ($result[0] != 200)
			return null;

		$config = [
			'allModels' => $result[1]['data'],
			// 'sort' => [
			// 	'attributes' => [
			// 	],
			// ],
		];

		// $config['pagination'] = $result[1]['pagination'];

		$dataProvider = new ArrayDataProvider($config);

		$dataProvider->setModels($result[1]['data']);

		$dataProvider->setTotalCount($result[1]['pagination']['totalCount']);

		$page = 0;
		if (empty($_GET['page']) == false)
			$page = intval($_GET['page']) - 1;

		// $dataProvider->pagination->setPage($page);

		$dataProvider->setPagination([
			'page' => $page,
			// 'pageSize' => 20,
			'totalCount' => $result[1]['pagination']['totalCount'],
		]);

		// if (isset($result[1]['pagination']['totalCount'])) {
		// 	// $config['pagination'] = $result[1]['pagination'];
		// 	$dataProvider->setTotalCount($result[1]['pagination']['totalCount']);
		// }

		return $dataProvider;

		/*
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
			// 	],
			// ],
		]);

		return $dataProvider;
		*/
	}

}
