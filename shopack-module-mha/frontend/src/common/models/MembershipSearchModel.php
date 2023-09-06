<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\models;

use yii\base\Model;
use yii\web\ServerErrorHttpException;
use shopack\base\frontend\rest\RestClientDataProvider;
use iranhmusic\shopack\mha\frontend\common\models\MembershipModel;

class MembershipSearchModel extends MembershipModel
{
  use \shopack\base\common\db\SearchModelTrait;

	// public function attributeLabels()
	// {
	// 	return ArrayHelper::merge(parent::attributeLabels(), [
	// 		'usrssnLoginDateTime' => 'آخرین ورود',
	// 		'loginDateTime' => 'آخرین ورود',
	// 		'online' => 'آنلاین',
	// 	]);
	// }

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
					'mshpID',
					'mshpTitle',
					'mshpStartFrom' => [
						'default' => SORT_DESC,
					],
					'mshpYearlyPrice',
					'mshpStatus',
					'mshpCreatedAt' => [
						'default' => SORT_DESC,
					],
					'mshpCreatedBy',
					'mshpUpdatedAt' => [
						'default' => SORT_DESC,
					],
					'mshpUpdatedBy',
					'mshpRemovedAt' => [
						'default' => SORT_DESC,
					],
					'mshpRemovedBy',
				],
				'defaultOrder' => [
					'mshpStartFrom' => SORT_DESC,
				]
			],
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			throw new ServerErrorHttpException('Unknown error sh01');
			// $query->where('0=1');
			return $dataProvider;
		}

		$this->applySearchValuesInQuery($query);

		return $dataProvider;
	}

}
