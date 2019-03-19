<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Headers:Origin, X-Requested-With, Content-Type, Accept,USER_ID,TOKEN");
header("Access-Control-Allow-Methods:HEAD, GET, POST, DELETE, PUT, OPTIONS");
// 处理跨域
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
$originPath = require(__DIR__ . '/../config/origin_config.php');
if (in_array($origin, $originPath['list'])) {
    header("Access-Control-Allow-Origin:" . $origin);
    header('Access-Control-Allow-Methods:' . $originPath['access']['methods']);
    header("Access-Control-Allow-Credentials:" . $originPath['access']['credentials']);
    header('Access-Control-Allow-Headers:' . $originPath['access']['headers']);
};

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
