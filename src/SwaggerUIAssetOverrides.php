<?php

namespace light\swagger;

use yii\web\AssetBundle;

class SwaggerUIAssetOverrides extends AssetBundle
{
    public $sourcePath = '@vendor/light/yii2-swagger/src';

    public $js = [
    ];

    public $css = [
        'swagger-overrides.css',
    ];

    public function init()
    {
        parent::init();
    }

    public $depends = [
        SwaggerUIAsset::class,
    ];
}