<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\userpanel;

use Yii;
use yii\base\BootstrapInterface;
use shopack\base\common\shop\ShopModuleTrait;
use iranhmusic\shopack\mha\frontend\common\models\MembershipModel;
use iranhmusic\shopack\mha\frontend\common\models\MemberMembershipModel;
use iranhmusic\shopack\mha\frontend\common\controllers\BasketController;

class Module
	extends \shopack\base\common\base\BaseModule
	implements BootstrapInterface
{
	use ShopModuleTrait;

	public function init()
	{
		if (empty($this->id))
			$this->id = 'mha';

		parent::init();

		$this->registerSaleable(MembershipModel::class, MemberMembershipModel::class);
	}

	public function bootstrap($app)
	{
		if ($app instanceof \yii\web\Application) {
			$this->controllerMap['basket'] = BasketController::class;

			// $rules = [
			// ];

			// $app->urlManager->addRules($rules, false);

			$this->addDefaultRules($app);

		} elseif ($app instanceof \yii\console\Application) {
			$this->controllerNamespace = 'iranhmusic\shopack\mha\frontend\userpanel\commands';
		}
	}

}
