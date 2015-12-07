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

    public $js = [
        'lib/jquery-1.8.0.min.js',
        'lib/jquery.slideto.min.js',

        'lib/jquery.wiggle.min.js',
        'lib/jquery.ba-bbq.min.js',
        'lib/handlebars-2.0.0.js',
        'lib/underscore-min.js',
        'lib/backbone-min.js',
        'swagger-ui.js',
        'lib/highlight.7.3.pack.js',
        'lib/marked.js',
        'lib/swagger-oauth.js',
        'lang/translator.js',
        'lang/zh-cn.js',
    ];

    public $jsOptions = [
        'position' => View::POS_HEAD,
    ];

    public $css = [
        'css/typography.css',
        'css/reset.css',
        'css/screen.css',
    ];
}
