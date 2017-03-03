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
        'lib/object-assign-pollyfill.js',
        'lib/jquery-1.8.0.min.js',
        'lib/jquery.slideto.min.js',
        'lib/jquery.wiggle.min.js',
        'lib/jquery.ba-bbq.min.js',
        'lib/handlebars-4.0.5.js',
        'lib/lodash.min.js',
        'lib/backbone-min.js',
        'swagger-ui.js',
        'lib/highlight.9.1.0.pack.js',
        'lib/highlight.9.1.0.pack_extended.js',
        'lib/jsoneditor.min.js',
        'lib/marked.js',
        'lib/swagger-oauth.js',
    ];
    
    public $jsOptions = [
        'position' => View::POS_HEAD,
    ];
    
    public $css = [
        [
            'css/typography.css',
            'media' => 'screen',
        ],
        [
            'css/reset.css',
            'media' => 'screen',
        ],
        [
            'css/screen.css',
            'media' => 'screen',
        ],
        //the setting will be overload, maybe the yii's issue.
        //[
        //    'css/reset.css',
        //    'media' => 'print',
        //],
        //[
        //    'css/print.css',
        //    'media' => 'print',
        //],
    ];
}
