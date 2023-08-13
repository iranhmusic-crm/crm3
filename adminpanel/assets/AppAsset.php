<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
	public $basePath = '@webroot';
	public $baseUrl = '@web';

  public $depends = [
    'yii\web\YiiAsset',
    // 'yii\bootstrap5\BootstrapAsset',
    // 'app\assets\BootstrapAsset',
    // 'app\assets\FontAwesomeAsset'
    // 'simialbi\yii2\turbo\TurboAsset'
    \shopack\base\frontend\ShopackAssetBundle::class,
  ];

  public $css = [
		'css/site.css',
	];

  public $js = [
		// 'js/app.js',
	];

}
