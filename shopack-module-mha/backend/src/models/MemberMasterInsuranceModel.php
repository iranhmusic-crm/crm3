<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;

class MemberMasterInsuranceModel extends MhaActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\MemberMasterInsuranceModelTrait;

	public static function tableName()
	{
		return '{{%MHA_MemberMasterInsuranceHistory}}';
	}

	public function behaviors()
	{
		return [
			[
				'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
				'createdAtAttribute' => 'mbrminshstCreatedAt',
				'createdByAttribute' => 'mbrminshstCreatedBy',
				'updatedAtAttribute' => 'mbrminshstUpdatedAt',
				'updatedByAttribute' => 'mbrminshstUpdatedBy',
			],
		];
	}

}
