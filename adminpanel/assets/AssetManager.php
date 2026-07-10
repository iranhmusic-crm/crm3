<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use Yii;

class AssetManager extends \yii\web\AssetManager
{
	public $linkAssets = true;
	// public $forceCopy = true;

	public function init()
	{
		// $hashCallback = function($path) : string {
		// 	$path = (is_file($path) ? dirname($path) : $path) . filemtime($path);
		// 	return sprintf('%x', crc32($path . Yii::getVersion() . '|' . $this->linkAssets));
		// };

		// $this->hashCallback = $hashCallback;

// die(Yii::getAlias(\yii\bootstrap5\BootstrapAsset::$sourcePath));
// die(realpath(Yii::getAlias('@bower/bootstrap')));

		$bower_bootstrap = realpath(Yii::getAlias('@vendor/twbs/bootstrap/dist/css'));
		$vendor_shopack_base_fe = realpath(Yii::getAlias('@vendor/shopack/yii2-base-frontend/src/common/assets'));

		$this->assetMap = [
			'bootstrap.css' => $this->hash($bower_bootstrap) . '/bootstrap.rtl.css',
			'bootstrap.min.css' => $this->hash($bower_bootstrap) . '/bootstrap.rtl.css',

			'bootstrap-dialog-bs4.css' => $this->hash($vendor_shopack_base_fe) . '/css/bootstrap-dialog-bs4.rtl.css',
			'bootstrap-dialog-bs4.min.css' => $this->hash($vendor_shopack_base_fe) . '/css/bootstrap-dialog-bs4.rtl.css',
		];

		parent::init();

 	}

}
