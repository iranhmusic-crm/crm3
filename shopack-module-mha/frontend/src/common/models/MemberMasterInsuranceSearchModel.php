<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\models;

use yii\base\Model;
use shopack\base\frontend\rest\RestClientDataProvider;
use iranhmusic\shopack\mha\frontend\common\models\MemberMasterInsuranceModel;

class MemberMasterInsuranceSearchModel extends MemberMasterInsuranceModel
{
  use \shopack\base\common\db\SearchModelTrait;

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
					'mbrminshstCreatedAt' => [
						'default' => SORT_DESC,
					],
				],
				'defaultOrder' => [
					'mbrminshstCreatedAt' => SORT_DESC,
				]
			],
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			$query->where('0=1');
			return $dataProvider;
		}

		if (isset($params['mbrminshstMemberID']))
			$query->andWhere(['mbrminshstMemberID' => $params['mbrminshstMemberID']]);

		$this->applySearchValuesInQuery($query);

		return $dataProvider;
	}

}
