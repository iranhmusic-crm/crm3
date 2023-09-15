<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\models;

use yii;
use yii\base\Model;
use yii\web\ServerErrorHttpException;
use shopack\base\frontend\common\rest\RestClientDataProvider;
use iranhmusic\shopack\mha\frontend\common\models\MemberModel;
use shopack\base\common\helpers\ArrayHelper;

class MemberSearchModel extends MemberModel
{
  use \shopack\base\common\db\SearchModelTrait;

	const FILTER_MODE_ALL												= 0;
	const FILTER_MODE_WAIT_FOR_BASE_APPROVAL		= 1;
	const FILTER_MODE_WAIT_FOR_KANOON_APPROVAL	= 2;

	public $filter_mode;

	public function extraRules()
	{
		return [
			[['usrEmail',
  			'usrMobile',
	  		'usrSSID',
				'usrGender',
				'usrFirstName',
				'usrFirstName_en',
				'usrLastName',
        'usrLastName_en',
			], 'string'],

			[[
				'filter_mode',
			], 'number'],
			[[
				'filter_mode',
			], 'default', 'value' => 0],
		];
	}

	public function attributeLabels()
	{
		return ArrayHelper::merge(parent::attributeLabels(), [
			'filter_mode' => Yii::t('app', 'Status'),
		]);
	}

	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	public function search($params)
	{
		$this->load($params);

		$query = self::find();

		$dataProvider = new RestClientDataProvider([
			'query' => $query,
			'sort' => [
				// 'enableMultiSort' => true,
				'attributes' => [
					'mbrUserID',
					'mbrRegisterCode',
					'mbrAcceptedAt' => [
						'default' => SORT_DESC,
					],
					'mbrExpireDate' => [
						'default' => SORT_DESC,
					],
					'mbrStatus',
					'mbrCreatedAt' => [
						'default' => SORT_DESC,
					],
					'mbrCreatedBy',
					'mbrUpdatedAt' => [
						'default' => SORT_DESC,
					],
					'mbrUpdatedBy',
					'mbrRemovedAt' => [
						'default' => SORT_DESC,
					],
					'mbrRemovedBy',

					'usrFirstName' => [
						'asc' => ['usrFirstName' => SORT_ASC, 'usrLastName' => SORT_ASC],
						'desc' => ['usrFirstName' => SORT_DESC, 'usrLastName' => SORT_DESC],
						//'label' => 'usrssnLoginDateTime',
						'default' => SORT_ASC
					],
					'usrLastName' => [
						'asc' => ['usrLastName' => SORT_ASC, 'usrFirstName' => SORT_ASC],
						'desc' => ['usrLastName' => SORT_DESC, 'usrFirstName' => SORT_DESC],
						//'label' => 'usrssnLoginDateTime',
						'default' => SORT_ASC
					],
					'usrEmail',
					'usrMobile',
					'usrSSID',
					'usrCreatedAt' => [
						'default' => SORT_DESC
					],
					'usrUpdatedAt' => [
						'default' => SORT_DESC
					],
				],
				'defaultOrder' => [
					'mbrCreatedAt' => SORT_DESC,
					// 'usrLastName' => SORT_ASC,
					// 'usrFirstName' => SORT_ASC,
				],
			],
		]);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			throw new ServerErrorHttpException('Unknown error sh01');
			// $query->where('0=1');
			return $dataProvider;
		}

		$dataProvider->query
			->andFilterWhere(['like', 'usrEmail', $this->usrEmail])
			->andFilterWhere(['like', 'usrMobile', $this->usrMobile])
			->andFilterWhere(['like', 'usrSSID', $this->usrSSID])
			->andFilterWhere(['usrGender' => $this->usrGender])
			->andFilterWhere(['like', 'usrFirstName', $this->usrFirstName])
			->andFilterWhere(['like', 'usrFirstName_en', $this->usrFirstName_en])
			->andFilterWhere(['like', 'usrLastName', $this->usrLastName])
			->andFilterWhere(['like', 'usrLastName_en', $this->usrLastName_en])
		;

		$this->applySearchValuesInQuery($query);

		switch ($this->filter_mode)
		{
			case self::FILTER_MODE_ALL:
				break;

			case self::FILTER_MODE_WAIT_FOR_BASE_APPROVAL:
				$dataProvider->query
					->andWhere('IFNULL(shpobjPrice, 0) > 0')
				;
				break;

			case self::FILTER_MODE_WAIT_FOR_KANOON_APPROVAL:
				$dataProvider->query
					->andWhere('IFNULL(shpobjPrice, 0) = 0')
				;
				break;
		}

		return $dataProvider;
	}

}
