<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\accounting\models;

use Yii;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\frontend\common\rest\RestClientActiveRecord;

class UnitModel extends RestClientActiveRecord
{
	use \iranhmusic\shopack\mha\common\accounting\models\UnitModelTrait;

  public function isSoftDeleted()
	{
		return false;
	}

}
