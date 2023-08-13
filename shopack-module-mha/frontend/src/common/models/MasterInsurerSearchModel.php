<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\models;

use yii\base\Model;
use shopack\base\frontend\rest\RestClientDataProvider;
use iranhmusic\shopack\mha\frontend\common\models\MasterInsurerModel;

class MasterInsurerSearchModel extends MasterInsurerModel
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
					'minsID',
					'minsName',
					'minsStatus',
					'minsCreatedAt' => [
						'default' => SORT_DESC,
					],
					'minsCreatedBy',
					'minsUpdatedAt' => [
						'default' => SORT_DESC,
					],
					'minsUpdatedBy',
					'minsRemovedAt' => [
						'default' => SORT_DESC,
					],
					'minsRemovedBy',
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

}
