<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\accounting\models;

use yii\base\Model;
use yii\web\ServerErrorHttpException;
use shopack\base\common\helpers\ArrayHelper;
use shopack\base\frontend\rest\RestClientDataProvider;
use iranhmusic\shopack\mha\frontend\common\accounting\models\CouponModel;

class CouponSearchModel extends CouponModel
{
  use \shopack\base\common\db\SearchModelTrait;

	// public $providedCount;

	// public function attributeLabels()
	// {
	// 	return ArrayHelper::merge(parent::attributeLabels(), [
	// 		'providedCount' => 'درج شده',
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
			throw new ServerErrorHttpException('Unknown error sh01');
			// $query->where('0=1');
			return $dataProvider;
		}

		$this->applySearchValuesInQuery($query);

		return $dataProvider;
	}

}
