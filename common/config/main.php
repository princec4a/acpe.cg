<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    /*'modules' => [
        'notifications' => [
            'class' => 'machour\yii2\notifications\NotificationsModule',
            // Point this to your own Notification class
            // See the "Declaring your notifications" section below
            'notificationClass' => 'common\components\Notification',
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
    ],*/
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formatter' => [
            'locale' => 'fr-FR',
            'thousandSeparator' => '.',
            'currencyCode' => 'XAF',
        ],

        'reCaptcha' => [
            'class' => 'himiklab\yii2\recaptcha\ReCaptchaConfig',
            'siteKeyV2' => '6LdJxQsaAAAAAIbkNZ543VUUqPIKnhNd0MFH03KQ',
            'secretV2' => '6LdJxQsaAAAAAByjURRVvYszsoU1wJjDfXCcEY31',
            'siteKeyV3' => 'your siteKey v3',
            'secretV3' => 'your secret key v3',
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            /*'rules' => [
                //Notification rules
                'notifications/poll' => '/notifications/notifications/poll',
                'notifications/rnr' => '/notifications/notifications/rnr',
                'notifications/read' => '/notifications/notifications/read',
                'notifications/read-all' => '/notifications/notifications/read-all',
                'notifications/delete-all' => '/notifications/notifications/delete-all',
                'notifications/delete' => '/notifications/notifications/delete',
                'notifications/flash' => '/notifications/notifications/flash',
            ],*/
        ],
    ],
];
