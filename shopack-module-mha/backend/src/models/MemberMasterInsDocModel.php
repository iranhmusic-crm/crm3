<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;

class MemberMasterInsDocModel extends MhaActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\MemberMasterInsDocModelTrait;

	public static function tableName()
	{
		return '{{%MHA_MemberMasterInsDoc}}';
	}

	public function behaviors()
	{
		return [
			[
				'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
				'createdAtAttribute' => 'mbrminsdocCreatedAt',
				'createdByAttribute' => 'mbrminsdocCreatedBy',
				'updatedAtAttribute' => 'mbrminsdocUpdatedAt',
				'updatedByAttribute' => 'mbrminsdocUpdatedBy',
			],
		];
	}

}
