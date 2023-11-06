<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\models;

use yii\base\Model;
use yii\web\ServerErrorHttpException;
use shopack\base\frontend\common\rest\RestClientDataProvider;
use iranhmusic\shopack\mha\frontend\common\models\MemberMemberGroupModel;

class MemberMemberGroupSearchModel extends MemberMemberGroupModel
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
					'mbrmgpID',
					'mbrmgpCreatedAt' => [
						'default' => SORT_DESC,
					],
					'mbrmgpCreatedBy',
					'mbrmgpUpdatedAt' => [
						'default' => SORT_DESC,
					],
					'mbrmgpUpdatedBy',
					'mbrmgpRemovedAt' => [
						'default' => SORT_DESC,
					],
					'mbrmgpRemovedBy',
				],
			],
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		$this->applySearchValuesInQuery($query, $params);

		return $dataProvider;
	}

}
