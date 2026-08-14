<?php

/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use yii\web\NotFoundHttpException;
use shopack\base\common\db\DbExpression;
use iranhmusic\shopack\mha\common\enums\enuReportType;
use iranhmusic\shopack\mha\common\enums\enuReportStatus;
use iranhmusic\shopack\mha\common\enums\enuMemberKanoonStatus;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;
use iranhmusic\shopack\mha\backend\models\MemberModel;
use iranhmusic\shopack\mha\backend\models\MemberKanoonModel;
use shopack\base\common\helpers\ArrayHelper;

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

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert) == false)
            return false;

        if ((empty($this->rptOutputFields) == false)
            && (ArrayHelper::isIndexed($this->rptOutputFields) == false)
        ) {
            //it is converted to indexed to order the appropriate output fields
            $this->rptOutputFields = array_keys($this->rptOutputFields);
        }

        return true;
    }

    /**
     * return query
     */
    public function run()
    {
        switch ($this->rptType) {
            case enuReportType::Members:
                return $this->runMembers();

            case enuReportType::Fiancial:
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

        $fnAddBetweenCondition = function ($field, $values) use (&$query) {
            if (empty($values))
                return false;

            if (empty($values['From']) == false) {
                if (empty($values['To']) == false)
                    $query->andWhere(['BETWEEN', $field, $values['From'], $values['To']]);
                else
                    $query->andWhere(['>=', $field, $values['From']]);
            } else if (empty($values['To']) == false)
                $query->andWhere(['<=', $field, $values['To']]);

            return (empty($values['From']) == false || empty($values['To']) == false);
        };

        $fnApplyLikeSearchCondition = function ($field, $values) use (&$query) {
            if (empty($values))
                return false;

            $vals = explode(' ', $values);

            $ors = ['OR'];

            foreach ($vals as $val) {
                $ors[] = ['LIKE', $field, $val];
            }
            $query->andWhere($ors);
        };

        $fnParseHasValue = function ($hasvalue) {
            if ($hasvalue === NULL)
                return NULL;

            if (is_array($hasvalue) == false)
                $hasvalue = (array)$hasvalue;

            $hv = (isset($hasvalue[0]) ? $hasvalue[0] : NULL);

            if ($hv === 0 || $hv === '0')
                return false;

            if ($hv === 1 || $hv === '1')
                return true;

            //may be not ['0'] or ['1']
            return $hasvalue;
        };

        $appliedHas = [];

        $inputFieldsSchema = [
            'usrBirthLocation' => [
                'hasCallback' => function ($query, $key, $hasvalue) {
                    if ($hasvalue) {
                        $query->andWhere(['IS', 'birthstate.sttID', DbExpression::notNull()]);
                        $query->andWhere(['IS', 'birthcity.ctvID', DbExpression::notNull()]);
                    } else {
                        $query->andWhere(['birthstate.sttID' => null]);
                        $query->andWhere(['birthcity.ctvID' => null]);
                    }
                },
                'filterCallback' => function ($query, $key, $value, $hasvalue) {
                    if (empty($value['State']) == false)
                        $query->andWhere(['birthstate.sttID' => $value['State']]);

                    if (empty($value['City']) == false)
                        $query->andWhere(['birthcity.ctvID' => $value['City']]);
                },
                'join' => [
                    'user',
                    'userBirthLocation',
                ],
            ],

            'usrBirthDate' => [ // [From], [To]
                'hasCallback' => function ($query, $key, $hasvalue) {
                    if ($hasvalue)
                        $query->andWhere(['IS', $key, DbExpression::notNull()]);
                    else
                        $query->andWhere([$key => null]);
                },
                'filterCallback' => function ($query, $key, $value, $hasvalue) use ($fnAddBetweenCondition) {
                    $fnAddBetweenCondition($key, $value);
                },
                'join' => [
                    'user',
                ],
            ],

            'usrDeadAt' => [ // [From], [To]
                'hasCallback' => function ($query, $key, $hasvalue) {
                    if ($hasvalue)
                        $query->andWhere(['IS', $key, DbExpression::notNull()]);
                    else
                        $query->andWhere([$key => null]);
                },
                'filterCallback' => function ($query, $key, $value, $hasvalue) use ($fnAddBetweenCondition) {
                    $fnAddBetweenCondition($key, $value);
                },
                'join' => [
                    'user',
                ],
            ],

            'usrStateID' => [
                'hasCallback' => function ($query, $key, $hasvalue) {
                    if ($hasvalue)
                        $query->andWhere(['IS', $key, DbExpression::notNull()]);
                    else
                        $query->andWhere([$key => null]);
                },
                'filterCallback' => function ($query, $key, $value, $hasvalue) {
                    $query->andWhere([$key => $value]);
                },
                'join' => [
                    'user',
                    'userHomeLocation',
                ],
            ],

            'usrCityOrVillageID' => [
                'hasCallback' => function ($query, $key, $hasvalue) {
                    if ($hasvalue)
                        $query->andWhere(['IS', $key, DbExpression::notNull()]);
                    else
                        $query->andWhere([$key => null]);
                },
                'filterCallback' => function ($query, $key, $value, $hasvalue) {
                    $query->andWhere([$key => $value]);
                },
                'join' => [
                    'user',
                    'userHomeLocation',
                ],
            ],

            'mbrAcceptedAt' => [ // [From], [To]
                'hasCallback' => function ($query, $key, $hasvalue) {
                    if ($hasvalue)
                        $query->andWhere(['IS', $key, DbExpression::notNull()]);
                    else
                        $query->andWhere([$key => null]);
                },
                'filterCallback' => function ($query, $key, $value, $hasvalue) use ($fnAddBetweenCondition) {
                    $fnAddBetweenCondition($key, $value);
                },
            ],

            'mbrExpireDate' => [ // [From], [To]
                'hasCallback' => function ($query, $key, $hasvalue) {
                    if ($hasvalue)
                        $query->andWhere(['IS', $key, DbExpression::notNull()]);
                    else
                        $query->andWhere([$key => null]);
                },
                'filterCallback' => function ($query, $key, $value, $hasvalue) use ($fnAddBetweenCondition) {
                    $fnAddBetweenCondition($key, $value);
                },
            ],

            /*
                case| kanoon   | degree   | query
                ====|==========|==========|======================================================================================
                1.1 | [EMPTY]  | [EMPTY]  | NOTHING
                1.2 | [EMPTY]  | C{,D...} | LIKE '%:C|%' { OR LIKE '%:D|%' ... }
                1.3 | [EMPTY]  | has not  | LIKE '%:NULL|%'
                1.4 | [EMPTY]  | has      | LIKE '%:_|%'
                2.1 | 9{,4...} | [EMPTY]  | LIKE '%|9:%' { OR LIKE '%|4:%' ...}
                2.2 | 9{,4...} | C{,D...} | LIKE '%|9:C|%' { OR LIKE '%|9:D|%' ... OR LIKE '%|4:C|%' OR LIKE '%|4:D|%' ...}
                2.3 | 9{,4...} | has not  | LIKE '%|9:NULL|%' { OR LIKE '%|4:NULL|%' ...}
                2.4 | 9{,4...} | has      | LIKE '%|9:_|%' { OR NOT LIKE '%|4:_|%' ...}
                3.1 | has not  | [EMPTY]  | IS NULL
                3.2 | has not  | C{,D...} | IS NULL
                3.3 | has not  | has not  | IS NULL
                3.4 | has not  | has      | IS NULL
                4.1 | has      | [EMPTY]  | IS NOT NULL
                4.2 | has      | C{,D...} | LIKE '%:C|%' { OR LIKE '%:D|%' ... }
                4.3 | has      | has not  | IS NOT NULL AND NOT LIKE '%:_|%'
                4.4 | has      | has      | IS NOT NULL AND NOT LIKE '%:NULL|%'
            */

            'mbrknn' => [
                // 'hasCallback' => function($query, $key, $value) {
                //   if ($value == 0)
                //     $query->andWhere(['kanoonIDs' => null]);
                //   else
                //     $query->andWhere(['IS', 'kanoonIDs', DbExpression::notNull()]);
                // },
                'filterCallback' => function ($query, $key, $value, $hasvalue)
                use ($fnParseHasValue, &$appliedHas) {
                    $appliedHas['mbrknn'] = true;

                    $hasvalue_KanoonID = $fnParseHasValue($hasvalue['KanoonID'] ?? NULL);
                    $hasvalue_MembershipDegree = $fnParseHasValue($hasvalue['MembershipDegree'] ?? NULL);

                    $KanoonID = $value["KanoonID"] ?? [];
                    $MembershipDegree = $value["MembershipDegree"] ?? [];

                    if (empty($KanoonID) == false) {
                        if (is_string($KanoonID))
                            $KanoonID = (array)$KanoonID;
                    }

                    if (empty($MembershipDegree) == false) {
                        if (is_string($MembershipDegree))
                            $MembershipDegree = (array)$MembershipDegree;
                    }

                    if ($hasvalue_KanoonID === NULL) {
                        if (empty($KanoonID)) {
                            if ($hasvalue_MembershipDegree === NULL) {
                                if (empty($MembershipDegree)) {
                                    //1.1: do nothing
                                } else {
                                    //1.2
                                    $vv = ['OR'];
                                    foreach ($MembershipDegree as $vd) {
                                        $vv[] = ['LIKE', 'kanoonIDDegrees', new DbExpression("'%:{$vd}|%'")];
                                    }
                                    $query->andWhere($vv);
                                }
                            } else if ($hasvalue_MembershipDegree == false) {
                                //1.3
                                $query->andWhere(['LIKE', 'kanoonIDDegrees', new DbExpression("'%:NULL|%'")]);
                            } else if ($hasvalue_MembershipDegree == true) {
                                //1.4
                                $query->andWhere(['LIKE', 'kanoonIDDegrees', new DbExpression("'%:_|%'")]);
                            }
                        } else { //C{,D...}
                            if ($hasvalue_MembershipDegree === NULL) {
                                if (empty($MembershipDegree)) {
                                    //2.1:
                                    $vv = ['OR'];
                                    foreach ($KanoonID as $vk) {
                                        $vv[] = ['LIKE', 'kanoonIDDegrees', new DbExpression("'%|{$vk}:%'")];
                                    }
                                    $query->andWhere($vv);
                                } else {
                                    //2.2
                                    $vv = ['OR'];
                                    foreach ($KanoonID as $vk) {
                                        foreach ($MembershipDegree as $vd) {
                                            $vv[] = ['LIKE', 'kanoonIDDegrees', new DbExpression("'%|{$vk}:{$vd}|%'")];
                                        }
                                    }
                                    $query->andWhere($vv);
                                }
                            } else if ($hasvalue_MembershipDegree == false) {
                                //2.3
                                $vv = ['OR'];
                                foreach ($KanoonID as $vk) {
                                    $vv[] = ['LIKE', 'kanoonIDDegrees', new DbExpression("'%|{$vk}:NULL|%'")];
                                }
                                $query->andWhere($vv);
                            } else if ($hasvalue_MembershipDegree == true) {
                                //2.4
                                $vv = ['OR'];
                                foreach ($KanoonID as $vk) {
                                    $vv[] = ['LIKE', 'kanoonIDDegrees', new DbExpression("'%|{$vk}:_|%'")];
                                }
                                $query->andWhere($vv);
                            }
                        }
                    } else if ($hasvalue_KanoonID == false)
                        //3.1, 3.2, 3.3, 3.4:
                        $query->andWhere(['kanoonIDs' => null]);
                    else if ($hasvalue_KanoonID == true) {
                        if ($hasvalue_MembershipDegree === NULL) {
                            if (empty($MembershipDegree)) {
                                //4.1:
                                $query->andWhere(['IS', 'kanoonIDDegrees', DbExpression::notNull()]);
                            } else {
                                //4.2:
                                $vv = ['OR'];
                                foreach ($MembershipDegree as $vd) {
                                    $vv[] = ['LIKE', 'kanoonIDDegrees', new DbExpression("'%:{$vd}|%'")];
                                }
                                $query->andWhere($vv);
                            }
                        } else if ($hasvalue_MembershipDegree == false) {
                            //4.3:
                            $query
                                ->andWhere(['IS', 'kanoonIDDegrees', DbExpression::notNull()])
                                ->andWhere(['NOT LIKE', 'kanoonIDDegrees', new DbExpression("'%:_|%'")]);
                        } else if ($hasvalue_MembershipDegree == true) {
                            //4.4:
                            $query
                                ->andWhere(['IS', 'kanoonIDDegrees', DbExpression::notNull()])
                                ->andWhere(['NOT LIKE', 'kanoonIDDegrees', new DbExpression("'%:NULL|%'")]);
                        }
                    }
                },
                'join' => [
                    'kanoon',
                ],
            ],

            // case 'mbrknnParams':         // [I], [S], [R]
            // 	$joinToKanoon = true;
            // 	$vals = implode(',', $v);
            // 	$query->andWhere(new DbExpression(
            // 		"JSON_UNQUOTE(JSON_EXTRACT(mbrknnParams, '$.desc')) IN ({$vals})"
            // 	));
            // 	break;

            'mbrJob' => [
                'hasCallback' => function ($query, $key, $hasvalue) {
                    if ($hasvalue)
                        $query->andWhere(['IS', $key, DbExpression::notNull()]);
                    else
                        $query->andWhere([$key => null]);
                },
                'filterCallback' => function ($query, $key, $value, $hasvalue) use ($fnApplyLikeSearchCondition) {
                    $fnApplyLikeSearchCondition($key, $value);
                },
            ],

            'mbrRegisterCode' => [
                'hasCallback' => function ($query, $key, $hasvalue) {
                    if ($hasvalue)
                        $query->andWhere(['IS', $key, DbExpression::notNull()]);
                    else
                        $query->andWhere([$key => null]);
                },
                'filterCallback' => function ($query, $key, $value, $hasvalue) use ($fnApplyLikeSearchCondition) {
                    $fnApplyLikeSearchCondition($key, $value);
                },
            ],

            'finBalance' => [ // [From], [To]
                'hasCallback' => function ($query, $key, $hasvalue) {
                    if ($hasvalue)
                        $query->andWhere(['AND', ['IS', $key, DbExpression::notNull()], ['>', $key, 0]]);
                    else
                        $query->andWhere(['OR', [$key => null], [$key => 0]]);
                },
                'filterCallback' => function ($query, $key, $value, $hasvalue) use ($fnAddBetweenCondition) {
                    $fnAddBetweenCondition($key, $value);
                },
            ],
        ];

        foreach ($this->rptInputFields as $k => $v) {
            if ($k == 'Has')
                continue;

            $schema = $inputFieldsSchema[$k] ?? [];
            if (isset($schema['filterCallback']) == false) {
                $schema['filterCallback'] = function ($query, $key, $value, $hasvalue)
                use (&$appliedHas) {
                    if ($hasvalue === NULL)
                        $query->andWhere(['IN', $key, $value]);
                    else if (empty($appliedHas[$key])) {
                        if ($hasvalue == 0)
                            $query->andWhere([$key => null]);
                        else
                            $query->andWhere(['IS', $key, DbExpression::notNull()]);

                        $appliedHas[$key] = true;
                    }
                };
            }

            if (isset($this->rptInputFields['Has'][$k]))
                $hasvalue = $fnParseHasValue($this->rptInputFields['Has'][$k]);
            else
                $hasvalue = NULL;

            if ($hasvalue !== NULL && empty($schema['hasCallback']) == false) {
                if (empty($appliedHas[$k])) {
                    $schema['hasCallback']($query, $k, $hasvalue);
                    $appliedHas[$k] = true;
                }
            } else {
                $schema['filterCallback']($query, $k, $v, $hasvalue);
            }

            if (isset($schema['join'])) {
                foreach ((array)$schema['join'] as $j) {
                    $joins[$j] = true;
                }
            }
        }

        if (isset($this->rptInputFields['Has'])) {

            foreach ($this->rptInputFields['Has'] as $k => $v) {

                $hasvalue = $fnParseHasValue($v);
                if ($hasvalue === NULL)
                    continue;

                if (empty($appliedHas[$k])) {
                    $schema = $inputFieldsSchema[$k] ?? [];
                    if (isset($schema['hasCallback'])) {
                        $schema['hasCallback']($query, $k, $hasvalue);
                        $appliedHas[$k] = true;
                    } else if (isset($schema['filterCallback'])) {
                        $schema['filterCallback']($query, $k, [], $hasvalue);
                        $appliedHas[$k] = true;
                    }
                }
            }
        }

        //-- rptOutputFields ---------------------------------
        foreach ($this->rptOutputFields as $field) {
            if (str_starts_with($field, 'user.')) {
                $joins['user'] = true;

                if ($field == 'user.usrImage')
                    $joins['userImage'] = true;
                else if ($field == 'user.usrBirthCityID')
                    $joins['userBirthLocation'] = true;
                else if (in_array($field, [
                    'user.usrCountryID',
                    'user.usrStateID',
                    'user.usrCityOrVillageID',
                    'user.usrTownID',
                ]))
                    $joins['userHomeLocation'] = true;
            } else if (
                str_starts_with($field, 'mbrknn')
                || str_starts_with($field, 'knn')
                || str_starts_with($field, 'kanoon')
            ) {
                $joins['kanoon'] = true;
                // } else if (str_starts_with($field, 'mbr')) {
                // } else  {
                // unknown field
            } else if ($field == 'finBalance') {
                $joins['wallet'] = true;
            }
        }

        //columns
        // $query
        // 	->select('mbrUserID')
        // ;

        foreach ($this->rptOutputFields as $field) {
            switch ($field) {
                case 'usrBirthCityID':
                    // $query->addSelect([
                    // 	'birthcity.ctvName AS BirthCityName',
                    // 	'birthstate.sttName AS BirthStateName',
                    // ]);
                    break;

                case 'usrStateID':
                    // $query->addSelect([
                    // 	'homestate.sttName AS HomeStateName',
                    // ]);
                    break;

                case 'usrCityOrVillageID':
                    // $query->addSelect([
                    // 	'homecity.ctvName AS HomeCityName',
                    // ]);
                    break;

                case 'knnName':
                    // $query->addSelect([
                    // 	'knnID',
                    // 	'knnName',
                    // 	// 'mbrknnParams',
                    // 	'knnDescFieldType',
                    // ]);
                    break;

                case 'hasPassword':
                    // $query->addSelect(new DbExpression("usrPasswordHash IS NOT NULL AND usrPasswordHash != '' AS hasPassword"));
                    break;

                case 'mbrInstrumentID':
                    $query->joinWith('instrument'); //, false);
                    break;

                case 'mbrSingID':
                    $query->joinWith('sing'); //, false);
                    break;

                case 'mbrResearchID':
                    $query->joinWith('research'); //, false);
                    break;

                default:
                    // $query->addSelect($field);
                    break;
            }
        }

        //join
        if (isset($joins['user'])) {
            $query->innerJoinWith('user'); //, false);

            if (isset($joins['userImage']))
                $query->joinWith('user.imageFile'); //, false);

            if (isset($joins['userBirthLocation']))
                $query->joinWith('user.birthCityOrVillage'); //, false);

            if (isset($joins['userHomeLocation'])) {
                $query
                    ->joinWith('user.country') //, false)
                    ->joinWith('user.state') //, false)
                    ->joinWith('user.cityOrVillage') //, false)
                    ->joinWith('user.town') //, false)
                ;
            }
        }

        $fnGetValue = function ($value, $qouted = false) {
            return ($qouted ? "'" : "") . "{$value}" . ($qouted ? "'" : "");
        };

        if (isset($joins['kanoon'])) {
            $knnNameFieldName = 'knnName';
            $query
                ->addSelect(new DbExpression("kanoons.kanoonNames AS kanoonNames"))
                ->addSelect(new DbExpression("kanoons.kanoonIDs AS kanoonIDs"))
                ->addSelect(new DbExpression("kanoons.kanoonDegrees AS kanoonDegrees"))
                ->addSelect(new DbExpression("kanoons.kanoonIDDegrees AS kanoonIDDegrees"))
                ->leftJoin(
                    "(
    SELECT  mbrknnMemberID
         ,  GROUP_CONCAT(knn.{$knnNameFieldName} SEPARATOR '|') AS kanoonNames
         ,  GROUP_CONCAT(knn.knnID SEPARATOR '|') AS kanoonIDs
         ,  GROUP_CONCAT(mbrknn.mbrknnMembershipDegree SEPARATOR '|') AS kanoonDegrees
         ,  CONCAT('|', GROUP_CONCAT(CONCAT(knn.knnID, ':', IFNULL(mbrknn.mbrknnMembershipDegree, 'NULL')) SEPARATOR '|'), '|') AS kanoonIDDegrees
      FROM  tbl_MHA_Member_Kanoon mbrknn
INNER JOIN  tbl_MHA_Kanoon knn
        ON  knn.knnID = mbrknn.mbrknnKanoonID
     WHERE  mbrknnStatus = '{$fnGetValue(enuMemberKanoonStatus::Accepted)}'
  GROUP BY  mbrknnMemberID
            ) AS kanoons",
                    "kanoons.mbrknnMemberID = tbl_MHA_Member.mbrUserID"
                )
            ;

            // $query
            // 	->leftJoin(MemberKanoonModel::tableName(), [
            // 		'AND',
            // 		MemberKanoonModel::tableName() . '.mbrknnMemberID = '
            // 		. MemberModel::tableName() . '.mbrUserID',
            // 		MemberKanoonModel::tableName() . ".mbrknnStatus = '" . enuMemberKanoonStatus::Accepted . "'"
            // 	])
            // 	->leftJoin(KanoonModel::tableName(),
            // 		KanoonModel::tableName() . '.knnID = '
            // 		. MemberKanoonModel::tableName() . '.mbrknnKanoonID'
            // 	)
            // ;
        }

        if (isset($joins['wallet'])) {
            $knnNameFieldName = 'knnName';
            $query
                ->addSelect(new DbExpression("walletBalance.finBalance AS finBalance"))
                ->leftJoin(
                    "(
    SELECT  walOwnerUserID
         ,  SUM(walRemainedAmount) AS finBalance
      FROM  tbl_AAA_Wallet
  GROUP BY  walOwnerUserID
            ) AS walletBalance",
                    "walletBalance.walOwnerUserID = tbl_MHA_Member.mbrUserID"
                )
            ;

            // $query
            // 	->leftJoin(MemberKanoonModel::tableName(), [
            // 		'AND',
            // 		MemberKanoonModel::tableName() . '.mbrknnMemberID = '
            // 		. MemberModel::tableName() . '.mbrUserID',
            // 		MemberKanoonModel::tableName() . ".mbrknnStatus = '" . enuMemberKanoonStatus::Accepted . "'"
            // 	])
            // 	->leftJoin(KanoonModel::tableName(),
            // 		KanoonModel::tableName() . '.knnID = '
            // 		. MemberKanoonModel::tableName() . '.mbrknnKanoonID'
            // 	)
            // ;
        }

        return $query;
    }

    /*	private function runMembers1()
  {
    $query = MemberModel::find();
    $joins = [];

    // $joinToUser = false;
    // $joinToUserImage = false;
    // $joinToUserBirthLocation = false;
    // $joinToUserHomeLocation = false;
    // $joinToKanoon = false;

    //-- rptInputFields ------------------------------
    // {
    // 	"mbrknnParams": {"I": "55"},
    // 	"mbrknnKanoonID": "8",
    // 	"usrBirthLocation": {"City": "877", "State": "1227"}
    // }

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
        'filterCallback' => function($query, $key, $value, $hasvalue) {
          if (empty($value['State']) == false)
            $query->andWhere(['birthstate.sttID' => $value['State']]);

          if (empty($value['City']) == false)
            $query->andWhere(['birthcity.ctvID' => $value['City']]);
        },
        'hasCallback' => function($query, $key, $value) {
          if ($value == 0) {
            $query->andWhere(['birthstate.sttID' => null]);
            $query->andWhere(['birthcity.ctvID' => null]);
          } else {
            $query->andWhere(['IS', 'birthstate.sttID', DbExpression::notNull()]);
            $query->andWhere(['IS', 'birthcity.ctvID', DbExpression::notNull()]);
          }
        },
        'join' => [
          'user',
          'userBirthLocation',
        ],
      ],

      'usrBirthDate' => [ // [From], [To]
        'filterCallback' => function($query, $key, $value, $hasvalue) use ($fnAddBetweenCondition) {
          $fnAddBetweenCondition($key, $value);
        },
        'hasCallback' => function($query, $key, $value) {
          if ($value == 0)
            $query->andWhere([$key => null]);
          else
            $query->andWhere(['IS', $key, DbExpression::notNull()]);
        },
        'join' => [
          'user',
        ],
      ],

      'usrStateID' => [
        'filterCallback' => function($query, $key, $value, $hasvalue) {
          $query->andWhere([$key => $value]);
        },
        'hasCallback' => function($query, $key, $value) {
          if ($value == 0)
            $query->andWhere([$key => null]);
          else
            $query->andWhere(['IS', $key, DbExpression::notNull()]);
        },
        'join' => [
          'user',
          'userHomeLocation',
        ],
      ],

      'usrCityOrVillageID' => [
        'filterCallback' => function($query, $key, $value, $hasvalue) {
          $query->andWhere([$key => $value]);
        },
        'hasCallback' => function($query, $key, $value) {
          if ($value == 0)
            $query->andWhere([$key => null]);
          else
            $query->andWhere(['IS', $key, DbExpression::notNull()]);
        },
        'join' => [
          'user',
          'userHomeLocation',
        ],
      ],

      'mbrAcceptedAt' => [ // [From], [To]
        'filterCallback' => function($query, $key, $value, $hasvalue) use ($fnAddBetweenCondition) {
          $fnAddBetweenCondition($key, $value);
        },
        'hasCallback' => function($query, $key, $value) {
          if ($value == 0)
            $query->andWhere([$key => null]);
          else
            $query->andWhere(['IS', $key, DbExpression::notNull()]);
        },
      ],

      'mbrExpireDate' => [ // [From], [To]
        'filterCallback' => function($query, $key, $value, $hasvalue) use ($fnAddBetweenCondition) {
          $fnAddBetweenCondition($key, $value);
        },
        'hasCallback' => function($query, $key, $value) {
          if ($value == 0)
            $query->andWhere([$key => null]);
          else
            $query->andWhere(['IS', $key, DbExpression::notNull()]);
        },
      ],

      'mbrknnMembershipDegree' => [
        'filterCallback' => function($query, $key, $value, $hasvalue) {
          $query->andWhere(['IN', $key, $value]);
        },
        'hasCallback' => function($query, $key, $value) {
          if ($value == 0)
            $query->andWhere([$key => null]);
          else
            $query->andWhere(['IS', $key, DbExpression::notNull()]);
        },
        'join' => [
          'kanoon',
        ],
      ],

      // case 'mbrknnParams':         // [I], [S], [R]
      // 	$joinToKanoon = true;
      // 	$vals = implode(',', $v);
      // 	$query->andWhere(new DbExpression(
      // 		"JSON_UNQUOTE(JSON_EXTRACT(mbrknnParams, '$.desc')) IN ({$vals})"
      // 	));
      // 	break;

      'mbrJob' => [
        'filterCallback' => function($query, $key, $value, $hasvalue) use ($fnApplyLikeSearchCondition) {
          $fnApplyLikeSearchCondition($key, $value);
        },
        'hasCallback' => function($query, $key, $value) {
          if ($value == 0)
            $query->andWhere([$key => null]);
          else
            $query->andWhere(['IS', $key, DbExpression::notNull()]);
        },
      ],

      'mbrRegisterCode' => [
        'filterCallback' => function($query, $key, $value, $hasvalue) use ($fnApplyLikeSearchCondition) {
          $fnApplyLikeSearchCondition($key, $value);
        },
        'hasCallback' => function($query, $key, $value) {
          if ($value == 0)
            $query->andWhere([$key => null]);
          else
            $query->andWhere(['IS', $key, DbExpression::notNull()]);
        },
      ],

    ];

    $fnApplyFilter = function($key, $value, $applyHas)
      use ($inputFieldsSchema, &$query, &$joins)
    {
      if (isset($inputFieldsSchema[$key])) {
        $schema = $inputFieldsSchema[$key];
      } else {
        $schema = [
          'filterCallback' => function($query, $key, $value, $hasvalue) {
            $query->andWhere(['IN', $key, $value]);
          },
          'hasCallback' => function($query, $key, $value) {
            if ($value == 0)
              $query->andWhere([$key => null]);
            else
              $query->andWhere(['IS', $key, DbExpression::notNull()]);
          },
        ];
      }

      $callback = ($applyHas
        ? ($schema['hasCallback'] ?? null)
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
      if (str_ends_with($k, '_Has')) {
        // if ($v == 1) {
          $kk = substr($k, 0, -4); //strip _Has fron end
          // if (isset($inputFieldsSchema[$kk]))
            $fnApplyFilter($kk, $v[0] ?? $v, true);
        // }
      } else { //if (isset($inputFieldsSchema[$k])) {
        if (array_key_exists($k . '_Has', $this->rptInputFields) == false)
          $fnApplyFilter($k, $v, false);
      }
    }

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
          $query->addSelect(new DbExpression("usrPasswordHash IS NOT NULL AND usrPasswordHash != '' AS hasPassword"));
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
*/

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
