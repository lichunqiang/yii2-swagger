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

use Yii;
use yii\base\Action;
use yii\base\InvalidArgumentException;
use yii\web\Response;

/**
 * The document display action.
 *
 * To use url property
 * ~~~
 * public function actions()
 * {
 *     return [
 *         'doc' => [
 *             'class' => 'light\swagger\SwaggerAction',
 *             'restUrl' => Url::to(['site/api'], true)
 *             'additionalAsset' => 'app\modules\api\assets\SwaggerUIAssetOverrides',
 *         ]
 *     ];
 * }
 *
 * To use urls property
 *
 * public function actions()
 * {
 *     return [
 *         'doc' => [
 *             'class' => 'light\swagger\SwaggerAction',
 *             'restUrl' => [
 *                 [
 *                     'name' => 'API V1',
 *                     'url' => Url::to(['/site/api-v1'], true),
 *                 ],
 *                 [
 *                     'name' => 'API V2',
 *                     'url' => Url::to(['/site/api-v2'], true),
 *                 ],
 *             ],
 *             'additionalAsset' => 'app\modules\api\assets\SwaggerUIAssetOverrides',
 *         ]
 *     ];
 * }
 * ~~~
 */
class SwaggerAction extends Action
{
    /**
     * @var string|array The rest url configuration.
     * Check documentation for more information.
     * @doc https://github.com/swagger-api/swagger-ui/blob/master/docs/usage/configuration.md
     */
    public $restUrl;
    /**
     * @var array The OAuth configration
     */
    public $oauthConfiguration = [];

    public $additionalAsset;

    public function run()
    {
        Yii::$app->getResponse()->format = Response::FORMAT_HTML;

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

    protected function beforeRun()
    {
        if ($this->additionalAsset != null) {
            $additionalAsset = $this->additionalAsset;
            if (class_exists($additionalAsset)) {
                $additionalAsset::register($this->controller->view);
            } else {
                throw new InvalidArgumentException("Not valid class");
            }
        }

        return parent::beforeRun();
    }
}
