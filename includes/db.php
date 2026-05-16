<?php
/**
 * Database Connection - PDO Singleton (PostgreSQL)
 *
 * @version 2.0
 */

class Database
{
    private static ?PDO $instance = null;
    private static bool $connected = false;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            self::$instance = self::createConnection();
        }

        return self::$instance;
    }

    private static function createConnection(): PDO
    {
        $dsn = sprintf(
            'pgsql:host=%s;port=%d;dbname=%s',
            DB_HOST,
            DB_PORT,
            DB_NAME
        );

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => true,
        ];

        self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
        self::$connected = true;

        return self::$instance;
    }

    public static function isConnected(): bool
    {
        return self::$connected && self::$instance !== null;
    }

    public static function query(string $sql, array $params = []): PDOStatement
    {
        $pdo = self::getInstance();

        if (empty($params)) {
            return $pdo->query($sql);
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    public static function fetch(string $sql, array $params = [])
    {
        $stmt = self::query($sql, $params);
        return $stmt->fetch();
    }

    public static function fetchAll(string $sql, array $params = []): array
    {
        $stmt = self::query($sql, $params);
        return $stmt->fetchAll();
    }

    public static function lastInsertId(): string
    {
        return self::getInstance()->lastInsertId();
    }

    public static function beginTransaction(): bool
    {
        return self::getInstance()->beginTransaction();
    }

    public static function commit(): bool
    {
        return self::getInstance()->commit();
    }

    public static function rollback(): bool
    {
        return self::getInstance()->rollBack();
    }

    public static function insert(string $table, array $data): string
    {
        $keys = array_keys($data);
        $fields = implode(', ', $keys);
        $placeholders = ':' . implode(', :', $keys);

        $sql = "INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})";
        self::query($sql, $data);

        return self::lastInsertId();
    }

    public static function update(string $table, array $data, string $where, array $params = []): int
    {
        $set = [];
        foreach (array_keys($data) as $key) {
            $set[] = "{$key} = :{$key}";
        }

        $sql = "UPDATE {$table} SET " . implode(', ', $set) . " WHERE {$where}";
        $params = array_merge($data, $params);

        $stmt = self::query($sql, $params);
        return $stmt->rowCount();
    }

    public static function delete(string $table, string $where, array $params = []): int
    {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        $stmt = self::query($sql, $params);
        return $stmt->rowCount();
    }

    public static function count(string $table, string $where = '1=1', array $params = []): int
    {
        $sql = "SELECT COUNT(*) as cnt FROM {$table} WHERE {$where}";
        $result = self::fetch($sql, $params);
        return (int) ($result['cnt'] ?? 0);
    }

    public static function getValue(string $table, string $field, string $where = '1=1', array $params = [])
    {
        $sql = "SELECT {$field} FROM {$table} WHERE {$where}";
        $result = self::fetch($sql, $params);
        return $result[$field] ?? null;
    }
}

function db(): PDO
{
    return Database::getInstance();
}