<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;

class MemberSponsorshipModel extends MhaActiveRecord
{
	use \iranhmusic\shopack\mha\common\models\MemberSponsorshipModelTrait;

	public static function tableName()
	{
		return '{{%MHA_MemberSponsorship}}';
	}

	public function behaviors()
	{
		return [
			[
				'class' => \shopack\base\common\behaviors\RowDatesAttributesBehavior::class,
				'createdAtAttribute' => 'mbrspsCreatedAt',
				'createdByAttribute' => 'mbrspsCreatedBy',
				'updatedAtAttribute' => 'mbrspsUpdatedAt',
				'updatedByAttribute' => 'mbrspsUpdatedBy',
			],
		];
	}

}
