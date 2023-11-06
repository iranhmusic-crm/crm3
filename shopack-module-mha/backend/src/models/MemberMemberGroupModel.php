<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use shopack\aaa\backend\classes\AAAActiveRecord;

class MemberMemberGroupModel extends AAAActiveRecord
{
  use \iranhmusic\shopack\mha\common\models\MemberMemberGroupModelTrait;

	public static function tableName()
	{
		return '{{%MHA_Member_MemberGroup}}';
	}

	public function behaviors()
	{
		return [
			[
				'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
				'createdAtAttribute' => 'mbrmgpCreatedAt',
				'createdByAttribute' => 'mbrmgpCreatedBy',
				'updatedAtAttribute' => 'mbrmgpUpdatedAt',
				'updatedByAttribute' => 'mbrmgpUpdatedBy',
			],
		];
	}

}
