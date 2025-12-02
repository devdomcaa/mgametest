<?php
/**
 * Konfigurace připojení k databázi
 */

return [
    'host' => 'db.dw322.endora.cz',
    'dbname' => 'mgametest_endora',
    'username' => 'mgametest_endora',
    'password' => 'l05Kceyh',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];