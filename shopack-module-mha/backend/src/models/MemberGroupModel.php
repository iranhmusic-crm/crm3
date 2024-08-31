<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use shopack\aaa\backend\classes\AAAActiveRecord;

class MemberGroupModel extends AAAActiveRecord
{
  use \iranhmusic\shopack\mha\common\models\MemberGroupModelTrait;

	public static function tableName()
	{
		return '{{%MHA_MemberGroup}}';
	}

	public function behaviors()
	{
		return [
			[
				'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
				'createdAtAttribute' => 'mgpCreatedAt',
				'createdByAttribute' => 'mgpCreatedBy',
				'updatedAtAttribute' => 'mgpUpdatedAt',
				'updatedByAttribute' => 'mgpUpdatedBy',
			],
		];
	}

}
