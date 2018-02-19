<?php

/*
 * This file is part of the light/yii2-swagger.
 *
 * (c) lichunqiang <light-li@hotmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace light\swagger;

use yii\web\AssetBundle;
use yii\web\View;

class SwaggerUIAsset extends AssetBundle
{
    public $sourcePath = '@bower/swagger-ui/dist';
    public $baseUrl = '@web';

    public $js = [
        'swagger-ui-bundle.js',
        'swagger-ui-standalone-preset.js',
    ];

    public $css = [
        'swagger-ui.css'
    ];

    public $depends = [];
}
