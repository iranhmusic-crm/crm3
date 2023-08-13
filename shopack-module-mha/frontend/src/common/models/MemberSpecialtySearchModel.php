<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\models;

use yii\base\Model;
use shopack\base\frontend\rest\RestClientDataProvider;
use iranhmusic\shopack\mha\frontend\common\models\MemberSpecialtyModel;

class MemberSpecialtySearchModel extends MemberSpecialtyModel
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
			// 'sort' => [
			// 	// 'enableMultiSort' => true,
			// 	'attributes' => [
			// 		'mbrUserID',
			// 		'mbrRegisterCode',
			// 		'mbrStatus',
			// 		'mbrCreatedAt' => [
			// 			'default' => SORT_DESC,
			// 		],
			// 		'mbrCreatedBy',
			// 		'mbrUpdatedAt' => [
			// 			'default' => SORT_DESC,
			// 		],
			// 		'mbrUpdatedBy',
			// 		'mbrRemovedAt' => [
			// 			'default' => SORT_DESC,
			// 		],
			// 		'mbrRemovedBy',
			// 	],
			// ],
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			$query->where('0=1');
			return $dataProvider;
		}

		if (isset($params['mbrspcMemberID']))
			$query->andWhere(['mbrspcMemberID' => $params['mbrspcMemberID']]);

		$this->applySearchValuesInQuery($query);

		return $dataProvider;
	}

}
