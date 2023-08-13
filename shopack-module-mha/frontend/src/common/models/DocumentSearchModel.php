<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\models;

use yii\base\Model;
use shopack\base\common\helpers\ArrayHelper;
use shopack\base\frontend\rest\RestClientDataProvider;
use iranhmusic\shopack\mha\frontend\common\models\DocumentModel;

class DocumentSearchModel extends DocumentModel
{
  use \shopack\base\common\db\SearchModelTrait;

	public $providedCount;

	public function attributeLabels()
	{
		return ArrayHelper::merge(parent::attributeLabels(), [
			'providedCount' => 'درج شده',
		]);
	}

	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	public function search($params)
	{
		$query = self::find();

		$dataProvider = new RestClientDataProvider([
			'query' => $query,
			'sort' => [
				// 'enableMultiSort' => true,
				'attributes' => [
					'docID',
					'docName',
					'docType',
					'docCreatedAt' => [
						'default' => SORT_DESC,
					],
					'docCreatedBy',
					'docUpdatedAt' => [
						'default' => SORT_DESC,
					],
					'docUpdatedBy',
					'docRemovedAt' => [
						'default' => SORT_DESC,
					],
					'docRemovedBy',
				],
			],
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		$this->applySearchValuesInQuery($query);

		return $dataProvider;
	}

	public function getDocumentTypesForMember($memberID)
	{
		$query = self::find()
			->endpoint('member-document-types')
			->limit(null)
			->offset(null)
			->addUrlParameter('memberID', $memberID)
		;

		$dataProvider = new RestClientDataProvider([
			'query' => $query,
			'pagination' => false, //prevent HEAD request
			'sort' => [
				// 'enableMultiSort' => true,
				'attributes' => [
					'docID',
					'docName',
					'docType',
					'docCreatedAt' => [
						'default' => SORT_DESC,
					],
					'docCreatedBy',
					'docUpdatedAt' => [
						'default' => SORT_DESC,
					],
					'docUpdatedBy',
					'docRemovedAt' => [
						'default' => SORT_DESC,
					],
					'docRemovedBy',
				],
			],
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
