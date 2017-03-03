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
use yii\caching\Cache;
use yii\web\Response;

/**
 * The api data output action.
 *
 * ~~~
 * public function actions()
 * {
 *     return [
 *         'api' => [
 *             'class' => 'light\swagger\SwaggerApiAction',
 *             'scanDir' => [
 *                 Yii::getAlias('@api/modules/v1/swagger'),
 *                 Yii::getAlias('@api/modules/v1/controllers'),
 *                 ...
 *             ]
 *         ]
 *     ];
 * }
 * ~~~
 */
class SwaggerApiAction extends Action
{
    /**
     * @var string|array|\Symfony\Component\Finder\Finder The directory(s) or filename(s).
     * If you configured the directory must be full path of the directory.
     */
    public $scanDir;
    /**
     * @var string api key, if configured will perform the authentication.
     */
    public $api_key;
    /**
     * @var string Query param to get api key.
     */
    public $apiKeyParam = 'api_key';
    /**
     * @var array The options passed to `Swagger`, Please refer the `Swagger\scan` function for more information.
     */
    public $scanOptions = [];
    /**
     * @var Cache|string|null the cache object or the ID of the cache application component that is used to store
     * Cache the \Swagger\Scan
     */
    public $cache = null;
    /**
     * @var string Cache key
     * [[cache]] must not be null
     */
    public $cacheKey = 'api-swagger-cache';
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->initCors();
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $headers = Yii::$app->getRequest()->getHeaders();
        $requestApiKey = $headers->get($this->apiKeyParam, Yii::$app->getRequest()->get($this->apiKeyParam));
        
        if (null !== $this->api_key
            && $this->api_key != $requestApiKey
        ) {
            return $this->getNeedAuthResponse();
        }
        
        $this->clearCache();
        
        if ($this->cache !== null) {
            $cache = $this->getCache();
            if (($swagger = $cache->get($this->cacheKey)) === false) {
                $swagger = $this->getSwagger();
                $cache->set($this->cacheKey, $swagger);
            }
        } else {
            $swagger = $this->getSwagger();
        }
        
        return $swagger;
    }
    
    /**
     * Init cors.
     */
    protected function initCors()
    {
        $headers = Yii::$app->getResponse()->getHeaders();
        
        $headers->set('Access-Control-Allow-Headers', 'Content-Type, api_key, Authorization');
        $headers->set('Access-Control-Allow-Methods', 'GET, POST, DELETE, PUT');
        $headers->set('Access-Control-Allow-Origin', '*');
        $headers->set('Allow', 'OPTIONS,HEAD,GET');
    }
    
    /**
     * @return array
     */
    protected function getNeedAuthResponse()
    {
        return [
            'securityDefinitions' => [
                'api_key' => ['in' => 'header', 'type' => 'apiKey', 'name' => 'api_key'],
            ],
            'swagger' => '2.0',
            'schemes' => ['http'],
            'info' => [
                'title' => 'Please take authentication firstly.',
            ],
        ];
    }
    
    protected function clearCache()
    {
        $clearCache = Yii::$app->getRequest()->get('clear-cache', false);
        if ($clearCache !== false) {
            $this->getCache()->delete($this->cacheKey);
            
            Yii::$app->response->content = 'Succeed clear swagger api cache.';
            Yii::$app->end();
        }
    }
    
    /**
     * @return Cache
     * @throws \yii\base\InvalidConfigException
     */
    protected function getCache()
    {
        return is_string($this->cache) ? Yii::$app->get($this->cache, false) : $this->cache;
    }
    
    /**
     * Get swagger object
     *
     * @return \Swagger\Annotations\Swagger
     */
    protected function getSwagger()
    {
        return \Swagger\scan($this->scanDir, $this->scanOptions);
    }
}
