<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend;

use yii\base\BootstrapInterface;
use shopack\base\common\shop\ShopModuleTrait;
use iranhmusic\shopack\mha\backend\models\MembershipModel;
use iranhmusic\shopack\mha\backend\models\MemberMembershipModel;

class Module
	extends \shopack\base\common\base\BaseModule
	implements BootstrapInterface
{
	use ShopModuleTrait;

	//used for trust message channel
	public $servicePrivateKey;

	public function init()
	{
		if (empty($this->id))
			$this->id = 'mha';

		parent::init();

		$this->registerSaleable(MembershipModel::class, MemberMembershipModel::class);
	}

	public function bootstrap($app)
	{
		if ($app instanceof \yii\web\Application)
		{
			$rules = [
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/basic-definition'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/service'],
					'pluralize' => false,

					// 'tokens' => [
					// 	'{uuid}' => '<uuid:[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}>',
					// ],

					'patterns' => [
						// 'GET,HEAD'					=> 'index',
						// 'GET,HEAD {uuid}'		=> 'view',
						// 'POST'							=> 'create',
						// 'PUT,PATCH {uuid}'	=> 'update',
						// 'DELETE {uuid}'			=> 'delete',
						// '{uuid}'						=> 'options',
						// ''									=> 'options',
						'POST process-voucher-item' => 'process-voucher-item',
					],
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/member'],
					'pluralize' => false,
					'extraPatterns' => [
						'POST signup' => 'signup',
					],
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/specialty'],
					'pluralize' => false,
					'tokens' => [
						'{id}' => '<id:\\d[\\d,]*>',
						'{parentid}' => '<parentid:\\d[\\d,]*>',
					],
					'extraPatterns' => [
						'POST {parentid}' => 'create',
					],
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/member-specialty'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/master-insurer'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/master-insurer-type'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/supplementary-insurer'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/member-master-insurance'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/member-master-ins-doc'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/member-master-ins-doc-history'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/member-supplementary-ins-doc'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/member-supplementary-ins-doc-history'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/kanoon'],
					'pluralize' => false,

					'extraPatterns' => [
						'POST send-message' => 'send-message',
					],
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/member-kanoon'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/member-sponsorship'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/membership'],
					'pluralize' => false,

					'extraPatterns' => [
						'POST add-to-basket' => 'add-to-basket',
					],
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/member-membership'],
					'pluralize' => false,

					'extraPatterns' => [
						'GET renewal-info' => 'renewal-info',
					],
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/document'],
					'pluralize' => false,
					'extraPatterns' => [
						'GET member-document-types' => 'member-document-types',
					],
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/member-document'],
					'pluralize' => false,
				],
				[
					'class' => \yii\rest\UrlRule::class,
					// 'prefix' => 'v1',
					'controller' => [$this->id . '/basket'],
					'pluralize' => false,

					'tokens' => [
						'{uuid}' => '<uuid:[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}>',
					],

					'patterns' => [
						// 'GET,HEAD'					=> 'index',
						// 'GET,HEAD {uuid}'		=> 'view',
						'POST'							=> 'create',
						'PUT,PATCH {uuid}'	=> 'update',
						'DELETE {uuid}'			=> 'delete',
						'{uuid}'						=> 'options',
						''									=> 'options',
					],
				],
			];

			$app->urlManager->addRules($rules, false);

		} elseif ($app instanceof \yii\console\Application) {
			$this->controllerNamespace = 'iranhmusic\shopack\mha\backend\commands';
		}
	}

}
