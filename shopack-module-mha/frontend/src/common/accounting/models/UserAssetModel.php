<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\accounting\models;

use Yii;
use iranhmusic\shopack\mha\backend\classes\MhaActiveRecord;

class UserAssetModel extends MhaActiveRecord
{
	use \iranhmusic\shopack\mha\common\accounting\models\UserAssetModelTrait;

	public static function tableName()
	{
		return '{{%MHA_Accounting_UserAsset}}';
	}

}
