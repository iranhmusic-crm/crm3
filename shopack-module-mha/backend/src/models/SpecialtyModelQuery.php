<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\models;

use creocoder\nestedsets\NestedSetsQueryBehavior;

class SpecialtyModelQuery extends \yii\db\ActiveQuery
{
	public function behaviors() {
		return [
			NestedSetsQueryBehavior::class,
		];
	}

}
