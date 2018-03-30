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
use yii\caching\CacheInterface;
use yii\di\Instance;
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
     * @deprecated
     */
    public $api_key;
    /**
     * @var string The `apiKey` name specified.
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
    public $cache = 'cache';
    /**
     * @var bool If enable caching the scan result.
     * @since 2.0.0
     */
    public $enableCache = false;
    /**
     * @var string Cache key
     * [[cache]] must not be null
     */
    public $cacheKey = 'api-swagger-cache';
    
    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        $this->cache = Instance::ensure($this->cache, CacheInterface::class);
    
        $this->initCors();
    }
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $this->clearCache();
        
        if ($this->enableCache) {
            if (($swagger = $this->cache->get($this->cacheKey)) === false) {
                $swagger = $this->getSwagger();
                $this->cache->set($this->cacheKey, $swagger);
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
        
        $headers->set('Access-Control-Allow-Headers', implode(', ', [
            'Content-Type',
            $this->apiKeyParam,
            'Authorization',
        ]));
        $headers->set('Access-Control-Allow-Methods', 'GET, POST, DELETE, PUT');
        $headers->set('Access-Control-Allow-Origin', '*');
    }
    
    /**s
     *
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\ExitException
     */
    protected function clearCache()
    {
        $clearCache = Yii::$app->getRequest()->get('clear-cache', false);
        if ($clearCache !== false) {
            $this->cache->delete($this->cacheKey);
            
            Yii::$app->response->content = 'Succeed clear swagger api cache.';
            Yii::$app->end();
        }
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
