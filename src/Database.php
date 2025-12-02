<?php
/**
 * Wrapper pro PDO připojení k databázi
 */

class Database
{
    private static ?PDO $pdo = null;

    /**
     * Získá PDO instanci (singleton)
     */
    public static function getConnection(): PDO
    {
        if (self::$pdo === null) {
            $config = require __DIR__ . '/../config/database.php';
            
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
            
            self::$pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
        }
        
        return self::$pdo;
    }

    /**
     * Provede dotaz a vrátí všechny řádky
     */
    public static function query(string $sql, array $params = []): array
    {
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Provede dotaz a vrátí jeden řádek
     */
    public static function queryOne(string $sql, array $params = []): ?array
    {
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Provede INSERT/UPDATE/DELETE dotaz
     */
    public static function execute(string $sql, array $params = []): bool
    {
        $stmt = self::getConnection()->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Vrátí ID posledního vloženého záznamu
     */
    public static function lastInsertId(): string
    {
        return self::getConnection()->lastInsertId();
    }
}