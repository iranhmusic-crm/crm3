<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\accounting\models;

use Yii;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;

class CouponModel extends MhaActiveRecord
{
	use \shopack\base\common\accounting\models\BaseCouponModelTrait;

	public static function tableName()
	{
		return '{{%MHA_Accounting_Coupon}}';
	}

}
