<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\frontend\adminpanel;

use Yii;
use yii\base\BootstrapInterface;
use shopack\base\frontend\adminpanel\accounting\AccountingModule;
use iranhmusic\shopack\mha\frontend\common\controllers\BasketController;

class Module
	extends \shopack\base\common\base\BaseModule
	implements BootstrapInterface
{
	public function init()
	{
		if (empty($this->id))
			$this->id = 'mha';

		parent::init();

		$this->setModule('accounting', [
			'class' => AccountingModule::class,
			'basePath' => $this->getBasePath() . DIRECTORY_SEPARATOR . 'accounting',
		]);
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
			$this->controllerNamespace = 'iranhmusic\shopack\mha\frontend\adminpanel\commands';
		}

		$accounting = $this->getModule('accounting');
		$accounting->bootstrap($app);
	}

}
