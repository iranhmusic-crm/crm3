<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use Yii;
use shopack\aaa\backend\models\UserModel;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;
use iranhmusic\shopack\mha\common\enums\enuSpecialtyStatus;

class SpecialtyModel extends MhaActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\SpecialtyModelTrait;

  use \shopack\base\common\db\SoftDeleteActiveRecordTrait;
  public function initSoftDelete()
  {
    $this->softdelete_RemovedStatus  = enuSpecialtyStatus::Removed;
    // $this->softdelete_StatusField    = 'spcStatus';
    $this->softdelete_RemovedAtField = 'spcRemovedAt';
    $this->softdelete_RemovedByField = 'spcRemovedBy';
	}

	public static function tableName()
	{
		return '{{%MHA_Specialty}}';
	}

	public $fullName;

	public function behaviors()
	{
		return [
			[
				'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
				'createdAtAttribute' => 'spcCreatedAt',
				'createdByAttribute' => 'spcCreatedBy',
				'updatedAtAttribute' => 'spcUpdatedAt',
				'updatedByAttribute' => 'spcUpdatedBy',
			],
			'tree' => [
				'class' => \creocoder\nestedsets\NestedSetsBehavior::class,
				'treeAttribute'  => 'spcRoot',
				'leftAttribute'  => 'spcLeft',
				'rightAttribute' => 'spcRight',
				'depthAttribute' => 'spcLevel',
			],
		];
	}

	public function transactions()
	{
		return [
			self::SCENARIO_DEFAULT => self::OP_ALL,
		];
	}

	public static function find()
	{
		$tableName = self::tableName();
		$nodeAlias = $tableName; //'node';

		$query = (new SpecialtyModelQuery(get_called_class()))
			// ->alias($nodeAlias)
			->select(SpecialtyModel::selectableColumns($nodeAlias))
			->with('createdByUser')
			->with('updatedByUser')
			->with('removedByUser')
		;

		// fullName
		$query
			->addSelect(new \yii\db\Expression("tmpFullName.fullName AS fullName"))
			->leftJoin(['tmpFullName' => <<<SQL
			(
        SELECT tbl_MHA_Specialty.spcID
             , GROUP_CONCAT(parent.spcName ORDER BY parent.spcLeft SEPARATOR ' >> ') AS fullName
          FROM tbl_MHA_Specialty
    CROSS JOIN tbl_MHA_Specialty parent
         WHERE (tbl_MHA_Specialty.spcRoot = parent.spcRoot)
           AND (tbl_MHA_Specialty.spcLeft BETWEEN parent.spcLeft AND parent.spcRight)
      GROUP BY tbl_MHA_Specialty.spcID
      ORDER BY parent.spcLeft
			)
SQL
			], "tmpFullName.spcID = {$nodeAlias}.spcID")

			// ->join('CROSS JOIN', $tableName . ' parent')
			// ->andWhere($nodeAlias . '.spcRoot = parent.spcRoot')
			// ->andWhere($nodeAlias . '.spcLeft BETWEEN parent.spcLeft AND parent.spcRight')
			// // ->andWhere("node.spcID = {$this->spcID}")
			// ->groupBy($nodeAlias . '.spcID')
			// ->orderBy([$nodeAlias . '.spcRoot' => SORT_ASC, 'parent.spcLeft' => SORT_ASC]);
		;

		return $query;
	}

	public function getUser() {
		return $this->hasOne(UserModel::class, ['usrID' => 'spcID']);
	}

	public function save($runValidation = true, $attributeNames = null)
  {
		if (empty($this->spcDescFieldType) && (empty($this->spcDescFieldLabel) == false))
			$this->spcDescFieldLabel = null;

    return parent::save($runValidation, $attributeNames);
  }

}
