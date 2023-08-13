<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\models;

use yii\base\Model;
use shopack\base\common\helpers\ArrayHelper;
use shopack\base\frontend\rest\RestClientDataProvider;
use iranhmusic\shopack\mha\frontend\common\models\BasicDefinitionModel;

class BasicDefinitionSearchModel extends BasicDefinitionModel
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
					'bdfID',
					'bdfType',
					'bdfName',
					'bdfCreatedAt' => [
						'default' => SORT_DESC,
					],
					'bdfCreatedBy',
					'bdfUpdatedAt' => [
						'default' => SORT_DESC,
					],
					'bdfUpdatedBy',
					'bdfRemovedAt' => [
						'default' => SORT_DESC,
					],
					'bdfRemovedBy',
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
