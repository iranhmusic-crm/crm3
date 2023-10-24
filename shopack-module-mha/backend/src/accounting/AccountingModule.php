<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\accounting;

class AccountingModule extends \shopack\base\backend\accounting\AccountingModule
{
	public function bootstrap($app)
	{
		parent::bootstrap($app);

		$parentID = $this->module->id;
		$thisID = $parentID . '/' . $this->id;

		if ($app instanceof \yii\web\Application) {
			$rules = [
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$thisID . '/membership'],
					'pluralize' => false,

					'patterns' => [
						'GET renewal-info' => 'renewal-info',
						'POST add-to-basket' => 'add-to-basket',
					],
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$thisID . '/membership-card'],
					'pluralize' => false,

					'patterns' => [
						'GET renewal-info' => 'renewal-info',
						'POST add-to-basket' => 'add-to-basket',
					],
				],
			];

			$app->urlManager->addRules($rules, false);
		}

	}

}
