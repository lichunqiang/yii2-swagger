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

use yii\base\Action;

/**
 * The document display action.
 *
 * ~~~
 * public function actions()
 * {
 *     return [
 *         'doc' => [
 *             'class' => 'light\swagger\SwaggerAction',
 *             'restUrl' => Url::to(['site/api'], true)
 *         ]
 *     ];
 * }
 * ~~~
 */
class SwaggerAction extends Action
{
    /**
     * @var string The rest url configuration.
     */
    public $restUrl;
    /**
     * @var array The OAuth configration
     */
    public $oauthConfiguration = [];
    
    public function run()
    {
        $this->controller->layout = false;
        
        $view = $this->controller->getView();
        
        if (empty($this->oauthConfiguration)) {
            $this->oauthConfiguration = [
                'clientId' => 'your-client-id',
                'clientSecret' => 'your-client-secret-if-required',
                'realm' => 'your-realms',
                'appName' => 'your-app-name',
                'scopeSeparator' => ' ',
                'additionalQueryStringParams' => [],
            ];
        }
        
        return $view->renderFile(__DIR__ . '/index.php', [
            'rest_url' => $this->restUrl,
            'oauthConfig' => $this->oauthConfiguration,
        ], $this->controller);
    }
}
