<?php
include "v1/config/ini.php";
$DB_SERVER=$GLOBALS['DB_SERVER'];
$DB_NAME=$GLOBALS['DB_NAME'];
$DB_USER=$GLOBALS['DB_USER'];
$DB_PASS=$GLOBALS['DB_PASS'];

// sail php ./v1/vendor/bin/phinx migrate --configuration v1/phinx.php
// sail php ./v1/vendor/bin/phinx seed:run --configuration v1/phinx.php
return
    [
        'paths' => [
            'migrations' => '%%PHINX_CONFIG_DIR%%/database/migrations',
            'seeds' => '%%PHINX_CONFIG_DIR%%/database/seeds'
        ],
        'environments' => [
            'default_migration_table' => 'migrations',
            'default_environment' => 'development',
            'production' => [
                'adapter' => 'mysql',
                'host' => $DB_SERVER,
                'name' => $DB_NAME,
                'user' => $DB_USER,
                'pass' => $DB_PASS,
                'port' => '3306',
                'charset' => 'utf8',
            ],
            'development' => [
                'adapter' => 'mysql',
                'host' => $DB_SERVER,
                'name' => $DB_NAME,
                'user' => $DB_USER,
                'pass' => $DB_PASS,
                'port' => '3306',
                'charset' => 'utf8',
            ],
            'testing' => [
                'adapter' => 'mysql',
                'host' => 'localhost',
                'name' => 'testing_db',
                'user' => 'root',
                'pass' => '',
                'port' => '3306',
                'charset' => 'utf8',
            ]
        ],
        'version_order' => 'creation'
    ];