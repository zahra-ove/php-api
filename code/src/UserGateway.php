<?php

namespace PHPApi\Code;

class UserGateway
{
    private \PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getByAPIKey(string $key): array | false
    {
        $sql = "SELECT * FROM users WHERE api_key = :api_key";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":api_key", $key);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getByUsername(string $username): array | false
    {
        $sql = "SELECT * FROM users
                WHERE username = :username";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":username", $username);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}