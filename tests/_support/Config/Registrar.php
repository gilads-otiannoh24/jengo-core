<?php
namespace Tests\Support\Config;

use CodeIgniter\Config\BaseConfig;

class Registrar
{
    private static $dbConfig = [
        'SQLite3' => [
            'DSN' => '',
            'hostname' => 'localhost',
            'username' => '',
            'password' => '',
            'database' => 'database.db',
            'DBDriver' => 'SQLite3',
            'DBPrefix' => 'db_',
            'pConnect' => false,
            'DBDebug' => true,
            'charset' => 'utf8',
            'DBCollat' => 'utf8_general_ci',
            'swapPre' => '',
            'encrypt' => false,
            'compress' => false,
            'strictOn' => false,
            'failover' => [],
            'port' => 3306,
            'foreignKeys' => true,
        ],
    ];

    public static function Database()
    {
        $config = [];

        $config['tests'] = self::$dbConfig["SQLite3"];

        return $config;
    }

    public static function Modules()
    {
        return [
            'composerPackages' => [
                'exclude' => [
                    'pestphp/pest',
                    'pestphp/pest-plugin'
                ]
            ]
        ];
    }
}