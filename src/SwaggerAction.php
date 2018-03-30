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
use yii\helpers\Json;
use yii\web\AssetBundle;
use yii\web\JsExpression;
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
     * @see https://github.com/swagger-api/swagger-ui/blob/master/docs/usage/configuration.md
     */
    public $restUrl;
    /**
     * @var array The OAuth configuration.
     */
    public $oauthConfiguration = [];
    /**
     * @var string The customer asset bundle.
     * @since 2.0.0
     */
    public $additionalAsset;
    /**
     * @var string
     * @since 2.0.0
     */
    public $title = 'Swagger-ui';
    /**
     * @var array The swagger-ui component configurations.
     * @see https://github.com/swagger-api/swagger-ui/blob/master/docs/usage/configuration.md
     * @since 2.0.0
     */
    public $configurations = [];
    /**
     * @var array Default swagger-ui configurations.
     * @since 2.0.0
     */
    protected $defaultConfigurations = [
        'dom_id' => '#swagger-ui',
        'deepLinking' => true,
        'presets' => [
            'SwaggerUIBundle.presets.apis',
            'SwaggerUIStandalonePreset',
        ],
        'plugins' => [
            'SwaggerUIBundle.plugins.DownloadUrl',
            'SwaggerUIBundle.plugins.Topbar',
        ],
        'layout' => 'StandaloneLayout',
        'validatorUrl' => null,
    ];
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        Yii::$app->getResponse()->format = Response::FORMAT_HTML;
        
        $this->controller->layout = false;
        
        $view = $this->controller->getView();
        
        return $view->renderFile(__DIR__ . '/index.php', [
            'configurations' => $this->prepareConfiguration(),
            'oauthConfiguration' => $this->oauthConfiguration,
            'title' => $this->title,
        ], $this->controller);
    }
    
    /**
     * @return string
     */
    protected function prepareConfiguration()
    {
        $configurations = array_merge($this->defaultConfigurations, $this->configurations);
        
        if ($this->restUrl) {
            $configurations[is_array($this->restUrl) ? 'urls' : 'url'] = $this->restUrl;
        }
        
        if (isset($configurations['plugins'])) {
            $configurations['plugins'] = array_map(
                [$this, 'convertJsExpression'],
                (array)$configurations['plugins']
            );
        }
    
        if (isset($configurations['presets'])) {
            $configurations['presets'] = array_map(
                [$this, 'convertJsExpression'],
                (array)$configurations['presets']
            );
        }
        
        return Json::encode($configurations);
    }
    
    /**
     * @param string $str
     *
     * @return JsExpression
     */
    protected function convertJsExpression($str)
    {
        return new JsExpression($str);
    }
    
    /**
     * @inheritdoc
     */
    protected function beforeRun()
    {
        if ($this->additionalAsset != null) {
            $additionalAsset = $this->additionalAsset;
            if (class_exists($additionalAsset)) {
                $additionalAsset::register($this->controller->getView());
            } else {
                throw new InvalidArgumentException('Not valid class');
            }
        }
        
        return parent::beforeRun();
    }
}
