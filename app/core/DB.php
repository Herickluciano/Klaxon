<?php
/**
 * Fournit une instance PDO unique dans l’appli
 */
class DB
{
    /** @var PDO|null */
    private static $pdo = null;

    /**
     * Retourne la connexion PDO
     *
     * @throws PDOException
     */
    public static function pdo(): PDO
    {
        if (self::$pdo === null) {
            $cfg = require __DIR__ . '/config.php';
            self::$pdo = new PDO(
                $cfg['dsn'],
                $cfg['user'],
                $cfg['password'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        }
        return self::$pdo;
    }
}
