<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'flushInterval' => 1,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                    'logFile' => '@runtime/logs/rates.log',
                    'categories' => ['rates'],
                    'exportInterval' => 1,
                    'maxLogFiles' => 10,
                    'logVars' => []
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                    'logFile' => '@runtime/logs/qiwi.log',
                    'categories' => ['qiwi'],
                    'exportInterval' => 1,
                    'maxLogFiles' => 10,
                    'logVars' => []
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                    'logFile' => '@runtime/logs/enter.log',
                    'categories' => ['enter'],
                    'exportInterval' => 1,
                    'maxLogFiles' => 10,
                    'logVars' => []
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                    'logFile' => '@runtime/logs/exmo.log',
                    'categories' => ['exmo'],
                    'exportInterval' => 1,
                    'maxLogFiles' => 10,
                    'logVars' => []
                ],
            ],
        ],
    ],
];
