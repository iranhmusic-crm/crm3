<?php

// $db = require __DIR__ . '/db.php';
$params = array_replace_recursive(
	require(__DIR__ . '/params.php'),
	require(__DIR__ . '/params-local.php')
);
$modules = array_replace_recursive(
	require(__DIR__ . '/modules.php'),
	require(__DIR__ . '/modules-local.php')
);
$webLocal = require(__DIR__ . '/web-local.php');

use \yii\web\Request;
$baseUrl = str_replace('/web', '', (new Request)->getBaseUrl());
$baseUrl = rtrim($baseUrl, '/') . '/';

$config = [
	'isJustForMe' => true,
	'id' => 'userpanel',
	'name' => 'خانه موسیقی من',
	'language' => 'fa_IR',
  'basePath' => dirname(__DIR__),
	'homeUrl' => $baseUrl,
  'aliases' => [
    '@bower' => '@vendor/bower-asset',
    '@npm'   => '@vendor/npm-asset',
  ],
	'bootstrap' => array_merge([
		'log',
		'gridview',
	], $modules['bootstrap']),
	'modules' => array_merge([
		'gridview' =>  [
			'class' => '\kartik\grid\Module'
			// enter optional module parameters below - only if you need to
			// use your own export download action or custom translation
			// message source
			// 'downloadAction' => 'gridview/export/download',
			// 'i18n' => []
		],
		'treemanager' =>  [
			'class' => '\kartik\tree\Module',
			'unsetAjaxBundles' => [
				'yii\web\YiiAsset',
				'yii\web\JqueryAsset',
				'yii\widgets\ActiveFormAsset',
				'yii\validators\ValidationAsset'
			],
			'treeStructure' => [
				'treeAttribute' => '{:entity}Root',
				'leftAttribute' => '{:entity}Left',
				'rightAttribute' => '{:entity}Right',
				'depthAttribute' => '{:entity}Level',
			],
			'dataStructure' => [
				'keyAttribute' => '{:entity}ID',
				'nameAttribute' => '{:entity}Name',
				'iconAttribute' => '{:entity}Image',
				'iconTypeAttribute' => 'icon_type'
			],
			'normalizeAttributeFunction' => function($attribute, $treeClass, $module) {
				if (property_exists($treeClass, 'entity'))
					return str_replace('{:entity}', $treeClass::entity, $attribute);
				return $attribute;
			}
		],
	], $modules['modules']),
  'components' => [
    'request' => [
			'class' => \shopack\base\common\web\Request::class,
      // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
			'cookieValidationKey' => 'must be define in local file',
			'baseUrl' => $baseUrl,
    ],
    'cache' => [
      'class' => 'yii\caching\FileCache',
    ],
    'user' => [
			'class' => \shopack\aaa\frontend\common\components\User::class,
    ],
    'errorHandler' => [
      'errorAction' => 'site/error',
    ],
    'mailer' => [
      'class' => \yii\symfonymailer\Mailer::class,
      'viewPath' => '@app/mail',
      // send all mails to a file by default.
      'useFileTransport' => false,
    ],
    'log' => [
      'traceLevel' => YII_DEBUG ? 999 : 0,
      'targets' => [
        [
          'class' => 'yii\log\FileTarget',
          'levels' => ['error', 'warning'],
        ],
      ],
    ],
		'formatter' => [
			'class' => \shopack\base\common\components\Formatter::class,
		],
		'i18n' => [
			'class' => \shopack\base\common\components\I18N::class,
			'translations' => [
				'app' => [
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => "@app/messages",
					'sourceLanguage' => 'en_US',
					'fileMap' => [
						'app' => 'app.php',
					],
				],
				// 'userpanel' => [
				// 	'class' => 'yii\i18n\PhpMessageSource',
				// 	'basePath' => "@app/messages",
				// 	'sourceLanguage' => 'en_US',
				// 	'fileMap' => [
				// 		'userpanel' => 'userpanel.php',
				// 	],
				// ],
				// 'yii' => [
				// 	'class' => 'yii\i18n\PhpMessageSource',
				// 	'basePath' => "@app/messages",
				// 	'sourceLanguage' => 'en_US',
				// 	'fileMap' => [
				// 		'yii' => 'yii.php',
				// 	],
				// ],
				'kvgrid' => [
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => "@app/messages",
					'sourceLanguage' => 'en_US',
					'fileMap' => [
						'kvgrid' => 'kvgrid.php',
					],
				],
			],
		],
		// 'db' => $db,
		'urlManager' => [
			'cache' => (YII_DEBUG ? false : 'cache'),
			'enablePrettyUrl' => true,
			'enableStrictParsing' => true,
			'showScriptName' => false,
			'baseUrl' => $baseUrl,
			'rules' => [
				'<controller:\w+>/<id:\d+>' => '<controller>',
				'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
				'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
				'<controller:\w+>' => '<controller>',
				// '' => '/site/index',
				'' => '/mha', ///default/index',
			],
		],
		'view' => [
			'class' => \shopack\base\frontend\web\View::class,
			// 'driver' => 'pjax', //Because the token is sent in the cookie, there is no need
		],
		'assetManager' => [
			// 'class' => 'yii\web\AssetManager',
			'class' => \app\assets\AssetManager::class,
		],
		'jwt' => [
			'class' => \bizley\jwt\Jwt::class,
			// 'signer' => \bizley\jwt\Jwt::HS512,
			// 'signingKey' => 'fDcXlBvkO9ND9UvhszmW4elXl2EehtpM',
			// 'ttl' => 24 * 3600, //24 hours
		],
		'member' => [
			'class' => \iranhmusic\shopack\mha\frontend\common\components\MemberManager::class,
		],
  ],
  'params' => $params,
];

if (YII_ENV_DEV) {
  // configuration adjustments for 'dev' environment
  $config['bootstrap'][] = 'debug';
  $config['modules']['debug'] = [
    'class' => 'yii\debug\Module',
    // uncomment the following to add your IP if you are not connecting from localhost.
    //'allowedIPs' => ['127.0.0.1', '::1'],
  ];

  /*
  $config['bootstrap'][] = 'gii';
  $config['modules']['gii'] = [
    'class' => 'yii\gii\Module',
    // uncomment the following to add your IP if you are not connecting from localhost.
    //'allowedIPs' => ['127.0.0.1', '::1'],
  ];
  */
}

return array_replace_recursive($config, $webLocal);
