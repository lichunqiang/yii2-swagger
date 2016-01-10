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
class SwaggerApiAction extends \yii\base\Action
{
    /**
     * @var string|array|Symfony\Finder\Finder The directory(s) or filename(s).
     * If you configrate the dirctory must be full path of the dirctory.
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
     * @var array The options passed to `Swagger`, Please refer the `Swagger\scan` function for more information.
     */
    public $scanOptions = [];
    /**
     * @var string The alias or full path of stable json file to store.
     * When the api is stable, please set the option to enable to store the scan data to json file.
     * Notice: Please make sure the directory is exists.
     */
    public $saveAs;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->saveAs) {
            $this->saveAs = Yii::getAlias($this->saveAs);
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (null !== $this->api_key
            && $this->api_key != Yii::$app->request->get($this->apiKeyParam)) {
            return ['errcode' => 404, 'errmsg' => 'Permission denied'];
        }

        $swagger = \Swagger\scan($this->scanDir, $this->scanOptions);

        if ($this->saveAs) {
            if (!file_exists($this->saveAs)) {
                $swagger->saveAs($this->saveAs);
            }
            return file_get_contents($this->saveAs);
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $swagger;
    }
}
