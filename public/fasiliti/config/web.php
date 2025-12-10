<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'MyFasiliti',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'timeZone' => 'Asia/Kuala_Lumpur',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'gridview' => [
            'class' => 'kartik\grid\Module',
            'bsVersion' => '5.x',
        ],
    ],

    'components' => [
        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => [
                    'class' => 'yii\bootstrap5\BootstrapAsset',
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'class' => 'yii\bootstrap5\BootstrapPluginAsset',
                ],
                'kartik\base\KrajeeBootstrapAsset' => [
                    'css' => [],
                ],
            ],
        ],

        'view' => [
            'theme' => [
                'pathMap' => [
                   '@app/views' => '@vendor/dmstr/yii2-adminlte-asset/example-views/yiisoft/yii2-basic-app'
                ],
            ],
       ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'tempahSekarang',
        ],
        
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        [
            'class' => 'yii\captcha\CaptchaAction',
            'transparent' => true, // Ensure the background is transparent
        ],
        
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        // 'modules' => [
        //     'debug' => [
        //         'class' => 'yii\debug\Module',
        //         'allowedIPs' => ['*'], // Allow all IPs
        //     ],
        // ],

        // 'mailer' => [
        //     'class' => \yii\symfonymailer\Mailer::class,
        //     'useFileTransport' => false, // Set to false to actually send emails
        //     'transport' => [
        //             'scheme' => 'smtp', 
        //             'host' => 'smtp.gmail.com', 
        //             'username' => 'amira.sistemfasiliti@ciast.edu.my',//fasiliti@ciast.edu.my
        //             'password' => 'sluy edtr ivxm fclf',  //ukwf zrdo kpgj agri
        //             'port' => 587, 
        //             'encryption' => 'tls', 
        //             'options' => [
        //                 'verify_peer' => 0,
        //             ],
            
        //     ],
        //     // 'logger' => function () {
        //     //     return \Yii::$app->log->logger;
        //     // },
        //     // 'on afterSend' => function ($event) {
        //     //     if (!$event->isSuccessful) {
        //     //         \Yii::error('Email failed to send: ' . print_r($event->sender->getDebug(), true));
        //     //     }
        //     // },
           
        // ],
        // 'mailer2' => [
        //     'class' => '\yii\swiftmailer\Mailer',
        //     'useFileTransport' => false,
        //     'transport' => [
        //         'class' => 'Swift_SmtpTransport',
        //         'host' => 'smtp.gmail.com',
        //         'username' => 'fasiliti@ciast.edu.my',
        //         'password' => 'ukwf zrdo kpgj agri',
        //         'port' => '587',
        //         'encryption' => 'tls',
        //     ],
        //     'enableSwiftMailerLogging' => true, // Enable logging
        // ],

        'mailer2' => [
            'class' => 'yii\symfonymailer\Mailer',
            // 'viewPath' => '@common/mail',
            'useFileTransport' => false, // true kalau nak test tanpa hantar email sebenar
            'transport' => [
                'scheme' => 'smtp',
                'host' => 'smtp.gmail.com', // Tukar ikut setting email provider
                'username' => 'fasiliti@ciast.edu.my',
                'password' => 'ukwf zrdo kpgj agri',
                'port' => 587, // Biasanya 587 untuk SMTP dengan TLS
                'options' => [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true,
                    ],
                ],
            ],
        ],

        // 'mailer2' => [
        //     'class' => 'yii\symfonymailer\Mailer',
        //     // 'viewPath' => '@common/mail',
        //     'useFileTransport' => false, // true kalau nak test tanpa hantar email sebenar
        //     'transport' => [
        //         'scheme' => 'tls',
        //         'host' => 'smtp.gmail.com', // Tukar ikut setting email provider
        //         'username' => 'fasiliti@ciast.edu.my',
        //         'password' => 'ukwf zrdo kpgj agri',
        //         'port' => 587, // Biasanya 587 untuk SMTP dengan TLS
        //         'options' => [],
        //     ],
        // ],

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    // 'categories' => ['debug'], // pastikan kategori debug di-enable
                    'logFile' => '@runtime/logs/app.log', // <-- ini penting
                    'levels' => ['error', 'warning', 'info'],
                ],
            ],
        ],

        // 'user' => [
        //     'identityClass' => 'app\models\User',
        //     'enableAutoLogin' => true,
        //     'identityCookie' => ['name' => '_identity', 'httpOnly' => true],
        //     'authTimeout' => 86400, // auto logout selepas 1 hari (dlm saat)
        // ],
        // 'session' => [
        //     'timeout' => 86400, // session user tamat selepas 1 hari tak aktif
        // ],

        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                // Define your URL rules here
                'login' => 'site/login',
                // Other rules can be added here
                'laporan/tempahan-bulanan/<month:\d+>/<year:\d+>' => 'laporan/laporan-tempahan-bulanan',
                'laporan/tahunan/<year:\d+>' => 'laporan/laporan-tahunan',
                'laporan/status-fasiliti/<startDate:\d{4}-\d{2}-\d{2}>/<endDate:\d{4}-\d{2}-\d{2}>' => 'laporan/laporan-status-fasiliti',
                'laporan/penghuni-asrama-bulanan/<month:\d+>/<year:\d+>' => 'laporan/laporan-penghuni-asrama-bulanan',
                'statistik/tempahan-fasiliti' => 'laporan/statistik-tempahan-fasiliti',
                'statistik/penghuni-asrama' => 'laporan/statistik-penghuni-asrama',
            ],
            'hostInfo' => 'https://fasiliti.ciast.gov.my', // Correct host information
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
        'allowedIPs' => ['127.0.0.1', '10.41.48.35', '10.37.12.2', '183.171.86.79','::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '10.41.48.35', 'fasiliti.ciast.gov.my', '*'],
    ];
}

return $config;
