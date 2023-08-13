<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\classes;

use shopack\base\backend\rest\RestServerActiveRecord;

abstract class MhaActiveRecord extends RestServerActiveRecord
{
	public static function getDb()
	{
		return \iranhmusic\shopack\mha\backend\Module::getInstance()->db;
		// return Yii::$app->controller->module->db;
	}

}
