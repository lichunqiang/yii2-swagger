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
use yii\web\Response;

class SwaggerApiAction extends \yii\base\Action
{
    /**
     * @var string|array|Symfony\Finder\Finder
     */
    public $scanDir;
    /**
     * @var string api key, if setted will perform the authentication.
     */
    public $api_key;

    /**
     * @var string Query param to get api key.
     */
    public $apiKeyParam = 'api_key';

    /**
     * @var array
     */
    public $scanOptions = [];

    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (null !== $this->api_key
            && $this->api_key != Yii::$app->request->get($this->apiKeyParam)) {
            return ['errcode' => 404, 'errmsg' => 'Permission denied'];
        }

        $swagger = \Swagger\scan($this->scanDir, $this->scanOptions);

        return $swagger;
    }
}
