<?php
/**
 * Database Connection - PDO Singleton
 *
 * Provides a secure, singleton PDO connection with:
 * - Proper error handling
 * - Prepared statements support
 * - Transaction support
 * - UTF8MB4 character set
 *
 * @version 1.0.0
 */

class Database
{
    private static ?PDO $instance = null;
    private static bool $connected = false;

    /**
     * Get database instance (singleton pattern)
     *
     * @throws PDOException
     * @return PDO
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            self::$instance = self::createConnection();
        }

        return self::$instance;
    }

    /**
     * Create new PDO connection
     *
     * @throws PDOException
     * @return PDO
     */
    private static function createConnection(): PDO
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            DB_HOST,
            DB_PORT,
            DB_NAME,
            DB_CHARSET
        );

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => true,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
        ];

        self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
        self::$connected = true;

        return self::$instance;
    }

    /**
     * Check if database is connected
     *
     * @return bool
     */
    public static function isConnected(): bool
    {
        return self::$connected && self::$instance !== null;
    }

    /**
     * Execute a query with optional prepared statements
     *
     * @param string $sql
     * @param array $params
     * @return PDOStatement
     * @throws PDOException
     */
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

    /**
     * Fetch single row
     *
     * @param string $sql
     * @param array $params
     * @return array|false
     */
    public static function fetch(string $sql, array $params = [])
    {
        $stmt = self::query($sql, $params);
        return $stmt->fetch();
    }

    /**
     * Fetch all rows
     *
     * @param string $sql
     * @param array $params
     * @return array
     */
    public static function fetchAll(string $sql, array $params = []): array
    {
        $stmt = self::query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Get last insert ID
     *
     * @return string
     */
    public static function lastInsertId(): string
    {
        return self::getInstance()->lastInsertId();
    }

    /**
     * Begin transaction
     *
     * @return bool
     */
    public static function beginTransaction(): bool
    {
        return self::getInstance()->beginTransaction();
    }

    /**
     * Commit transaction
     *
     * @return bool
     */
    public static function commit(): bool
    {
        return self::getInstance()->commit();
    }

    /**
     * Rollback transaction
     *
     * @return bool
     */
    public static function rollback(): bool
    {
        return self::getInstance()->rollBack();
    }

    /**
     * Insert row and return ID
     *
     * @param string $table
     * @param array $data
     * @return string
     * @throws PDOException
     */
    public static function insert(string $table, array $data): string
    {
        $keys = array_keys($data);
        $fields = implode(', ', $keys);
        $placeholders = ':' . implode(', :', $keys);

        $sql = "INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})";
        self::query($sql, $data);

        return self::lastInsertId();
    }

    /**
     * Update rows
     *
     * @param string $table
     * @param array $data
     * @param string $where
     * @param array $params
     * @return int
     * @throws PDOException
     */
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

    /**
     * Delete rows
     *
     * @param string $table
     * @param string $where
     * @param array $params
     * @return int
     * @throws PDOException
     */
    public static function delete(string $table, string $where, array $params = []): int
    {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        $stmt = self::query($sql, $params);
        return $stmt->rowCount();
    }

    /**
     * Count rows
     *
     * @param string $table
     * @param string $where
     * @param array $params
     * @return int
     */
    public static function count(string $table, string $where = '1=1', array $params = []): int
    {
        $sql = "SELECT COUNT(*) as cnt FROM {$table} WHERE {$where}";
        $result = self::fetch($sql, $params);
        return (int) ($result['cnt'] ?? 0);
    }

    /**
     * Get value from table
     *
     * @param string $table
     * @param string $field
     * @param string $where
     * @param array $params
     * @return mixed
     */
    public static function getValue(string $table, string $field, string $where = '1=1', array $params = [])
    {
        $sql = "SELECT {$field} FROM {$table} WHERE {$where}";
        $result = self::fetch($sql, $params);
        return $result[$field] ?? null;
    }
}

// Helper function for quick queries
function db(): PDO
{
    return Database::getInstance();
}