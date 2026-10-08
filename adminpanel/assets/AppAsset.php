<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use Yii;
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
        \shopack\base\frontend\common\ShopackAssetBundle::class,
    ];

    // public $css = [
    //     'css/site.css',
    // ];

    // public $js = [
    //     // 'js/app.js',
    // ];

    public function init()
    {
        if (Yii::$app->layout == "curved-405") {
            $this->css[] = 'css/curved-405.css';
        } else {
            $this->css[] = 'css/site.css';
        }

        parent::init();
    }
}
