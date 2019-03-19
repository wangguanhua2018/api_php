<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$rule = require __DIR__ . '/rule.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'test' => [
            'class' => 'app\modules\test\Test',
        ],
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'R0CYtekKg_J20Dt5bvH5aOD2iEvlpzzd',
            // 接受请求的json数据
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                'text/json' => 'yii\web\JsonParser',
            ]
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            //'dsn' => 'mongodb://39.106.138.124:27017/blog',
            'dsn' => 'mongodb://blog_wang:blog_wang666@39.106.138.124:27017/blog',
            /*
            'options' => [
                "username" => "blog_wang",
                "password" => "blog_wang666"
            ]*/
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'format' => yii\web\Response::FORMAT_JSON, 
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                if ($response->data !== null) {
                    $response->data['success'] = $response->isSuccessful;
                    /*[
                        'success' => $response->isSuccessful,
                        'data' => isset($response->data['data']) ? $response->data['data'] : null,
                        'code' => isset($response->data['code']) ? $response->data['code'] : null,
                        'message' => isset($response->data['message']) ? $response->data['message'] : null,
                        'version' => isset($response->data['version']) ? $response->data['version'] : null,
                    ]; 
                    //$response->statusCode = 200;*/
                }
            },
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            //'traceLevel' => YII_DEBUG ? 3 : 0,
            'traceLevel' =>  0,
            'targets' => [
                [
                    //'class' => 'yii\log\FileTarget',
                    //'levels' => ['error', 'warning'],
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => $rule,
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs'=> ['*'],
        'generators' => [
                'mongoDbModel' => [
                'class' => 'yii\mongodb\gii\model\Generator'
            ],
        ]
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
