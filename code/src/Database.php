<?php

namespace PHPApi\Code;

class Database
{
    private null|\PDO $conn = null;
    public function __construct(
        private string $host,
        private string $database,
        private string $user,
        private string $password)
    {
    }

    public function getConnection(): \PDO
    {

        try {
            if($this->conn == null) {
                $dsn = "mysql:host={$this->host};dbname={$this->database};charset=utf8";
                $this->conn = new \PDO($dsn, 'zizi', '123456', [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_EMULATE_PREPARES => false,
                    \PDO::ATTR_STRINGIFY_FETCHES => false,

                ]);
            }

           return $this->conn;

        } catch (\PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}