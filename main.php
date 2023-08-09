<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'gridview' =>  [
            'class' => '\kartik\grid\Module',
        ],
        'user' => [
            'class' => 'budyaga\users\Module',
            'userPhotoUrl' => 'http://localhost:90/acpe.cg/frontend/web/uploads/user/photo',
            //'userPhotoUrl' => 'http://acpe.ekreatic.com/backend/web/uploads/user/photo',
            'userPhotoPath' => '@frontend/web/uploads/user/photo',
            /*'customViews' => [
                'login' => '@app/views/site/login'
            ],*/
        ],
        'notifications' => [
            'class' => 'machour\yii2\notifications\NotificationsModule',
            // Point this to your own Notification class
            // See the "Declaring your notifications" section below
            'notificationClass' => 'frontend\components\Notification',
            // Allow to have notification with same (user_id, key, key_id)
            // Default to FALSE
            'allowDuplicate' => false,
            // Allow custom date formatting in database
            'dbDateFormat' => 'Y-m-d H:i:s',
            // This callable should return your logged in user Id
            'userId' => function () {
                return \Yii::$app->user->id;
            },
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
	    'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'i18n'=>[
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    //'basePath' => '@app/messages',
                    //'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ]
            ]
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                    'class' => 'Swift_SmtpTransport',
                'host' => 'localhost', // use for online
                //'host' => 'mail.ekreatic.com', //use for local
                'port' => '25', //on line tls
                //'port' => '465',  //for ssl
                //'port' => '587',  //for tls
                'username' => 'robot@ekreatic.com',
                'password' => 'nelci@069497849',
                'encryption' => 'tls',
                'streamOptions' => [
                    'ssl' => [
                        'allow_self_signed' => true,
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ]
            ],
        ],
        'urlManager' => [
            /*'enablePrettyUrl' => true,
            'showScriptName' => false,*/
            'rules' => [
                //Notification rules
                'notifications/poll' => '/notifications/notifications/poll',
                'notifications/rnr' => '/notifications/notifications/rnr',
                'notifications/read' => '/notifications/notifications/read',
                'notifications/read-all' => '/notifications/notifications/read-all',
                'notifications/delete-all' => '/notifications/notifications/delete-all',
                'notifications/delete' => '/notifications/notifications/delete',
                'notifications/flash' => '/notifications/notifications/flash',
            ],
        ],
    ],
    'as beforeRequest' => [
        'class' => 'frontend\components\CheckIfLoggedIn',
    ],
    'params' => $params,
];
