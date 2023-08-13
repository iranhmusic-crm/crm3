<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;

class MemberSupplementaryInsDocModel extends MhaActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\MemberSupplementaryInsDocModelTrait;

	public static function tableName()
	{
		return '{{%MHA_MemberSupplementaryInsDoc}}';
	}

	public function behaviors()
	{
		return [
			[
				'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
				'createdAtAttribute' => 'mbrsinsdocCreatedAt',
				'createdByAttribute' => 'mbrsinsdocCreatedBy',
				'updatedAtAttribute' => 'mbrsinsdocUpdatedAt',
				'updatedByAttribute' => 'mbrsinsdocUpdatedBy',
			],
		];
	}

}
