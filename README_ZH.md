yii2-swagger
============
[![version](https://img.shields.io/packagist/v/light/yii2-swagger.svg?style=flat-square)](https://packagist.org/packages/light/yii2-swagger)
[![Download](https://img.shields.io/packagist/dt/light/yii2-swagger.svg?style=flat-square)](https://packagist.org/packages/light/yii2-swagger)
[![Issues](https://img.shields.io/github/issues/lichunqiang/yii2-swagger.svg?style=flat-square)](https://github.com/lichunqiang/yii2-swagger/issues)

[swagger-php](https://github.com/zircote/swagger-php) intergation with yii2.

写在前面
--------

* 这玩意是干什么的呢？

没啥黑科技，只是集成了[swagger-ui](https://github.com/swagger-api/swagger-ui)然后配合[swagger-php](https://github.com/zircote/swagger-php)扫描你的代码目录来生成在Restful文档.


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist light/yii2-swagger "~1.0.0" --dev
```

or add

```
"light/yii2-swagger": "~1.0.0"
```

to the require section of your `composer.json` file.


Usage
-----

只要配置两个Action就可以了：

```
public function actions()
{
    return [
        //文档预览地址,配置好后可以直接访问:http://api.yourhost.com/site/doc
        'doc' => [
            'class' => 'light\swagger\SwaggerAction',
            'restUrl' => \yii\helpers\Url::to(['/site/api'], true),
        ],
        //看到上面配置的*restUrl*了么，没错, 它就是指向这个地址
        'api' => [
            'class' => 'light\swagger\SwaggerApiAction',
            //这里配置需要扫描的目录,不支持yii的alias,所以需要这里直接获取到真实地址
            'scanDir' => [
                Yii::getAlias('@api/modules/v1/swagger'),
                Yii::getAlias('@api/modules/v1/controllers'),
                Yii::getAlias('@api/modules/v1/models'),
                Yii::getAlias('@api/models'),
            ],
            //这个下面讲
            'api_key' => 'balbalbal',
        ],
    ];
}
```

`api_key` 是文档浏览key,文档放到线上，我们并不需要让每个人都能看到，所以可以通过设置这项来实现。配置后浏览文档时需要在右上角的`api_key`输入框中输入配置的值，才能正常访问文档.

> 默认情况下，没有配置的话，可以直接浏览文档


**靠！写了这么多我自己都乱了！怎么用？？**

别担心, **Talk is cheap, show me the code**.


上[Demo](https://github.com/lichunqiang/yii2-swagger-demo)


Caching
-------

```
public function actions()
{
    return [
        // ...
        'api' => [
            // ...
            'cache' => 'cache',
            'cacheKey' => 'api-swagger-cache', // default is 'api-swagger-cache'
        ],
    ];
}
```

#### Clear cache

Access clear cache url `YOUR_API_URL?clear-cache` or `YOUR_API_URL?api_key=YOUR_API_KEY&clear-cache`

Example: `curl 'http://localhost/v1/swagger/api?clear-cache'`

you will see: `Succeed clear swagger api cache.`

License
-------
![MIT](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)
