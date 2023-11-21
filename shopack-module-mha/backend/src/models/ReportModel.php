<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use Yii;
use yii\web\NotFoundHttpException;
use shopack\aaa\backend\models\UserModel;
use iranhmusic\shopack\mha\common\enums\enuReportType;
use iranhmusic\shopack\mha\common\enums\enuReportStatus;
use iranhmusic\shopack\mha\common\enums\enuMemberKanoonStatus;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;
use iranhmusic\shopack\mha\backend\models\MemberModel;
use iranhmusic\shopack\mha\backend\models\MemberKanoonModel;

class ReportModel extends MhaActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\ReportModelTrait;

  use \shopack\base\common\db\SoftDeleteActiveRecordTrait;
  public function initSoftDelete()
  {
    $this->softdelete_RemovedStatus  = enuReportStatus::Removed;
    // $this->softdelete_StatusField    = 'rptStatus';
    $this->softdelete_RemovedAtField = 'rptRemovedAt';
    $this->softdelete_RemovedByField = 'rptRemovedBy';
	}

	public static function tableName()
	{
		return '{{%MHA_Report}}';
	}

	public function behaviors()
	{
		return [
			[
				'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
				'createdAtAttribute' => 'rptCreatedAt',
				'createdByAttribute' => 'rptCreatedBy',
				'updatedAtAttribute' => 'rptUpdatedAt',
				'updatedByAttribute' => 'rptUpdatedBy',
			],
		];
	}

	/**
	 * return query
	 */
	public function run()
	{
		switch ($this->rptType) {
			case enuReportType::Members:
				return $this->runMembers();

			case enuReportType::Members:
				return $this->runFinancial();
		}

		throw new NotFoundHttpException('Report type not supported.');
	}

	/**
	 * return query
	 */
	private function runMembers()
	{
		$query = MemberModel::find();
		$joins = [];

		// $joinToUser = false;
		// $joinToUserImage = false;
		// $joinToUserBirthLocation = false;
		// $joinToUserHomeLocation = false;
		// $joinToKanoon = false;

		//-- rptInputFields ------------------------------
		/*
		{
			"mbrknnParams": {"I": "55"},
			"mbrknnKanoonID": "8",
			"usrBirthLocation": {"City": "877", "State": "1227"}
		}
		*/

		$fnAddBetweenCondition = function($field, $values) use (&$query) {
			if (empty($values['From']) == false) {
				if (empty($values['To']) == false)
					$query->andWhere(['BETWEEN', $field, $values['From'], $values['To']]);
				else
					$query->andWhere(['>=', $field, $values['From']]);
			} else if (empty($values['To']) == false)
				$query->andWhere(['<=', $field, $values['To']]);
		};

		$fnApplyLikeSearchCondition = function($field, $values) use (&$query) {
			$vals = explode(' ', $values);

			$ors = ['OR'];

			foreach ($vals as $val) {
				$ors[] = ['LIKE', $field, $val];
			}
			$query->andWhere($ors);
		};

		$inputFieldsSchema = [
			'usrBirthLocation' => [
				'filterCallback' => function($query, $key, $value) {
					if (empty($value['State']) == false)
						$query->andWhere(['birthstate.sttID' => $value['State']]);

					if (empty($value['City']) == false)
						$query->andWhere(['birthcity.ctvID' => $value['City']]);
				},
				'noneCallback' => function($query, $key, $value) {
					$query->andWhere(['birthstate.sttID' => null]);
					$query->andWhere(['birthcity.ctvID' => null]);
				},
				'join' => [
					'user',
					'userBirthLocation',
				],
			],

			'usrBirthDate' => [ // [From], [To]
				'filterCallback' => function($query, $key, $value) use ($fnAddBetweenCondition) {
					$fnAddBetweenCondition($key, $value);
				},
				'noneCallback' => function($query, $key, $value) {
					$query->andWhere([$key => null]);
				},
				'join' => [
					'user',
				],
			],

			'usrStateID' => [
				'filterCallback' => function($query, $key, $value) {
					$query->andWhere([$key => $value]);
				},
				'noneCallback' => function($query, $key, $value) {
					$query->andWhere([$key => null]);
				},
				'join' => [
					'user',
					'userHomeLocation',
				],
			],

			'usrCityOrVillageID' => [
				'filterCallback' => function($query, $key, $value) {
					$query->andWhere([$key => $value]);
				},
				'noneCallback' => function($query, $key, $value) {
					$query->andWhere([$key => null]);
				},
				'join' => [
					'user',
					'userHomeLocation',
				],
			],

			'mbrAcceptedAt' => [ // [From], [To]
				'filterCallback' => function($query, $key, $value) use ($fnAddBetweenCondition) {
					$fnAddBetweenCondition($key, $value);
				},
				'noneCallback' => function($query, $key, $value) {
					$query->andWhere([$key => null]);
				},
			],

			'mbrExpireDate' => [ // [From], [To]
				'filterCallback' => function($query, $key, $value) use ($fnAddBetweenCondition) {
					$fnAddBetweenCondition($key, $value);
				},
				'noneCallback' => function($query, $key, $value) {
					$query->andWhere([$key => null]);
				},
			],

			'mbrknnMembershipDegree' => [
				'filterCallback' => function($query, $key, $value) {
					$query->andWhere(['IN', $key, $value]);
				},
				'noneCallback' => function($query, $key, $value) {
					$query->andWhere([$key => null]);
				},
				'join' => [
					'kanoon',
				],
			],

			// case 'mbrknnParams':         // [I], [S], [R]
			// 	$joinToKanoon = true;
			// 	$vals = implode(',', $v);
			// 	$query->andWhere(new \yii\db\Expression(
			// 		"JSON_UNQUOTE(JSON_EXTRACT(mbrknnParams, '$.desc')) IN ({$vals})"
			// 	));
			// 	break;

			'mbrJob' => [
				'filterCallback' => function($query, $key, $value) use ($fnApplyLikeSearchCondition) {
					$fnApplyLikeSearchCondition($key, $value);
				},
				'noneCallback' => function($query, $key, $value) {
					$query->andWhere([$key => null]);
				},
			],

			'mbrRegisterCode' => [
				'filterCallback' => function($query, $key, $value) use ($fnApplyLikeSearchCondition) {
					$fnApplyLikeSearchCondition($key, $value);
				},
				'noneCallback' => function($query, $key, $value) {
					$query->andWhere([$key => null]);
				},
			],

		];

		$fnApplyFilter = function($key, $value, $applyNone)
			use ($inputFieldsSchema, &$query, &$joins)
		{
			if (isset($inputFieldsSchema[$key])) {
				$schema = $inputFieldsSchema[$key];
			} else {
				$schema = [
					'filterCallback' => function($query, $key, $value) {
						$query->andWhere(['IN', $key, $value]);
					},
					'noneCallback' => function($query, $key, $value) {
						$query->andWhere([$key => null]);
					},
				];
			}

			$callback = ($applyNone
				? ($schema['noneCallback'] ?? null)
				: $schema['filterCallback']
			);

			if ($callback !== null) {
				call_user_func($callback, $query, $key, $value);
			}

			if (isset($schema['join'])) {
				foreach ((array)$schema['join'] as $j) {
					$joins[$j] = true;
				}
			}
		};

		foreach ($this->rptInputFields as $k => $v) {
			if (str_ends_with($k, '_None')) {
				if ($v == 1) {
					$kk = substr($k, 0, -5); //strip _None fron end
					// if (isset($inputFieldsSchema[$kk]))
						$fnApplyFilter($kk, $v, true);
				}
			} else { //if (isset($inputFieldsSchema[$k])) {
				if (($this->rptInputFields[$k . '_None'] ?? 0) == 0)
					$fnApplyFilter($k, $v, false);
			}
		}







/*
		foreach ($this->rptInputFields as $k => $v) {
			if (str_starts_with($k, 'usr')) {
				$joinToUser = true;
			} else if (str_starts_with($k, 'mbrknn') || str_starts_with($k, 'knn')) {
				$joinToKanoon = true;
			}

			switch ($k) {
				case 'usrBirthLocation': // [State], [City]
				case 'usrBirthLocation_None':
					$joinToUser = true;
					$joinToUserBirthLocation = true;

					if ((str_ends_with($k, '_None') && ($v == 1))
						|| (isset($this->rptInputFields[$k . '_None'])
							&& ($this->rptInputFields[$k . '_None'] == 1)
						)
					) {
						$query->andWhere(['birthstate.sttID' => null]);
						$query->andWhere(['birthcity.ctvID' => null]);
					} else {
						if (empty($v['State']) == false)
							$query->andWhere(['birthstate.sttID' => $v['State']]);

						if (empty($v['City']) == false)
							$query->andWhere(['birthcity.ctvID' => $v['City']]);
					}

					break;

				case 'usrStateID':
					$joinToUser = true;
					$joinToUserHomeLocation = true;
					$query->andWhere(['usrStateID' => $v]);
					break;

				case 'usrCityOrVillageID':
					$joinToUser = true;
					$joinToUserHomeLocation = true;
					$query->andWhere(['usrCityOrVillageID' => $v]);
					break;

				case 'usrBirthDate':         // [From], [To]
					$joinToUser = true;
					$fnAddBetweenCondition('usrBirthDate', $v);
					break;

				case 'mbrAcceptedAt':        // [From], [To]
					$fnAddBetweenCondition('mbrAcceptedAt', $v);
					break;

				case 'mbrExpireDate':        // [From], [To]
					$fnAddBetweenCondition('mbrExpireDate', $v);
					break;

				case 'mbrknnMembershipDegree':
					$joinToKanoon = true;
					$query->andWhere(['mbrknnMembershipDegree' => $v]);
					break;

				// case 'mbrknnParams':         // [I], [S], [R]
				// 	$joinToKanoon = true;
				// 	$vals = implode(',', $v);
				// 	$query->andWhere(new \yii\db\Expression(
				// 		"JSON_UNQUOTE(JSON_EXTRACT(mbrknnParams, '$.desc')) IN ({$vals})"
				// 	));
				// 	break;

				default:
					$query->andWhere([$k => $v]);
					break;
			}
		}
*/
		//-- rptOutputFields ---------------------------------
		$rptOutputFields = array_keys($this->rptOutputFields);
		foreach ($rptOutputFields as $k => &$v) {
			if (str_starts_with($v, 'usr') || ($v == 'hasPassword')) {
				$joins['user'] = true;

				if ($v == 'usrImage')
					$joins['userImage'] = true;
				else if ($v == 'usrBirthCityID')
					$joins['userBirthLocation'] = true;
				else if (in_array($v, [
							'usrCountryID',
							'usrStateID',
							'usrCityOrVillageID',
							'usrTownID',
						]))
					$joins['userHomeLocation'] = true;
			} else if (str_starts_with($v, 'mbrknn')) {
				$joins['kanoon'] = true;
			} else if (str_starts_with($v, 'knn')) {
				$joins['kanoon'] = true;
			} else if (str_starts_with($v, 'mbr')) {
			} else  {
				// unknown field
			}
		}

		//columns
		$query
			->select('mbrUserID')
			// ->addSelect($rptOutputFields)
		;

		foreach ($rptOutputFields as $k) {
			switch ($k) {
				case 'usrBirthCityID':
					$query->addSelect([
						'birthcity.ctvName AS BirthCityName',
						'birthstate.sttName AS BirthStateName',
					]);
					break;

				case 'usrStateID':
					$query->addSelect([
						'homestate.sttName AS HomeStateName',
					]);
					break;

				case 'usrCityOrVillageID':
					$query->addSelect([
						'homecity.ctvName AS HomeCityName',
					]);
					break;

				case 'knnName':
					$query->addSelect([
						'knnID',
						'knnName',
						// 'mbrknnParams',
						'knnDescFieldType',
					]);
					break;

				case 'hasPassword':
					$query->addSelect(new \yii\db\Expression("usrPasswordHash IS NOT NULL AND usrPasswordHash != '' AS hasPassword"));
					break;

				case 'mbrInstrumentID':
					$query->joinWith('instrument instrument', false);
					$query->addSelect('instrument.bdfName AS InstrumentName');
					break;

				case 'mbrSingID':
					$query->joinWith('sing sing', false);
					$query->addSelect('sing.bdfName AS SingName');
					break;

				case 'mbrResearchID':
					$query->joinWith('research research', false);
					$query->addSelect('research.bdfName AS ResearchName');
					break;

				default:
					$query->addSelect($k);
					break;
			}
		}

		//join
		if (isset($joins['user'])) {
			$query->innerJoinWith('user', false);

			if (isset($joins['userImage']))
				$query->joinWith('user.imageFile', false);

			if (isset($joins['userBirthLocation'])) {
				$query
					->joinWith(['user.birthCityOrVillage birthcity' => function($q) {
						$q->joinWith('state birthstate');
					}], false)
					// ->addSelect([
					// 	'birthcity.ctvName',
					// 	'birthstate.sttName',
					// ])
				;
			}

			if (isset($joins['userHomeLocation'])) {
				$query
					->joinWith(['user.cityOrVillage homecity'], false)
					->joinWith(['user.state homestate'], false)
					// ->addSelect([
					// 	'homecity.ctvName',
					// 	'homestate.sttName',
					// ])
				;
			}
		}

		if (isset($joins['kanoon'])) {
			$query
				->leftJoin(MemberKanoonModel::tableName(), [
					'AND',
					MemberKanoonModel::tableName() . '.mbrknnMemberID = '
					. MemberModel::tableName() . '.mbrUserID',
					MemberKanoonModel::tableName() . ".mbrknnStatus = '" . enuMemberKanoonStatus::Accepted . "'"
				])
				->leftJoin(KanoonModel::tableName(),
					KanoonModel::tableName() . '.knnID = '
					. MemberKanoonModel::tableName() . '.mbrknnKanoonID'
				)
			;
		}

		return $query;
	}

	/**
	 * return query
	 */
	private function runFinancial()
	{
		//*******************************

		throw new \Exception('not implemented yet!');

		//*******************************
	}

}
