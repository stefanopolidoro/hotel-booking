<?php

declare(strict_types=1);

namespace Src\Core;

use PDO;
use PDOException;
use PDOStatement;

class Database
{
    private static ?self $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            DB_HOST, DB_NAME, DB_CHARSET
        );

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function query(string $sql, array $params = []): PDOStatement {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function lastInsertId(): string {
        return $this->pdo->lastInsertId();
    }
    
    public function beginTransaction(): bool { 
        return $this->pdo->beginTransaction(); 
    }
    
    public function commit(): bool { 
        return $this->pdo->commit(); 
    }
    public function rollBack(): bool{
        return $this->pdo->rollBack(); 
    }

    private function __clone() {}  
}