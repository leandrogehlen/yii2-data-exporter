<?php

return  [
    'id' => 'yii2-data-exporter-test',
    'basePath' => dirname(__DIR__),
    'vendorPath' => __DIR__ . '/../vendor',
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=yii2_exporter_test',
            'username' => 'root',
            'password' => ''
        ]
    ]
];
