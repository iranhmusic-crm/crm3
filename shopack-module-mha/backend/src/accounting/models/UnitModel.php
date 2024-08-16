<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\accounting\models;

use Yii;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;

class UnitModel extends MhaActiveRecord
{
	use \iranhmusic\shopack\mha\common\accounting\models\UnitModelTrait;

	public static function tableName()
	{
		return '{{%MHA_Accounting_Unit}}';
	}

	public function behaviors()
	{
		return [
			[
				'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
				'createdAtAttribute' => 'untCreatedAt',
				'createdByAttribute' => 'untCreatedBy',
				'updatedAtAttribute' => 'untUpdatedAt',
				'updatedByAttribute' => 'untUpdatedBy',
			],
		];
	}

}
