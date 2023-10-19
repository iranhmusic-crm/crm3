<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\userpanel;

use Yii;
use yii\base\BootstrapInterface;
// use shopack\base\common\shop\ShopModuleTrait;
// use iranhmusic\shopack\mha\frontend\common\models\MembershipModel;
// use iranhmusic\shopack\mha\frontend\common\models\MemberMembershipModel;
use iranhmusic\shopack\mha\frontend\common\controllers\BasketController;
// use iranhmusic\shopack\mha\frontend\userpanel\accounting\controllers\AccountingController;
use shopack\base\frontend\userpanel\accounting\AccountingModule;

class Module
	extends \shopack\base\common\base\BaseModule
	implements BootstrapInterface
{
	// use ShopModuleTrait;

	public function init()
	{
		if (empty($this->id))
			$this->id = 'mha';

		parent::init();

		$this->setModule('accounting', [
			'class' => AccountingModule::class,
			'basePath' => $this->getBasePath() . DIRECTORY_SEPARATOR . 'accounting',
		]);

		// $this->registerSaleable(MembershipModel::class, MemberMembershipModel::class);
	}

	public function bootstrap($app)
	{
		if ($app instanceof \yii\web\Application) {
			// $this->controllerMap['accounting'] = AccountingController::class;
			$this->controllerMap['basket'] = BasketController::class;

			// $rules = [
			// ];

			// $app->urlManager->addRules($rules, false);

			$this->addDefaultRules($app);

		} elseif ($app instanceof \yii\console\Application) {
			$this->controllerNamespace = 'iranhmusic\shopack\mha\frontend\userpanel\commands';
		}

		$accounting = $this->getModule('accounting');
		$accounting->bootstrap($app);
	}

}
