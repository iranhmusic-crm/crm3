<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\common\models;

use Yii;
use yii\base\Model;
use yii\db\Expression;
use shopack\base\frontend\rest\RestClientActiveRecord;

class BasketModel extends RestClientActiveRecord
{
	use \shopack\base\common\models\BasketModelTrait;

	public static $resourceName = 'mha/basket';
  public static $primaryKey = null;

	public function isSoftDeleted()
  {
    return false;
  }

	//convert to json and sign it
	public function getPrevoucher()
	{

	}

}
