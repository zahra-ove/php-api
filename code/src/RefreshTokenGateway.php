<?php

namespace PHPApi\Code;

class RefreshTokenGateway
{
    private \PDO $conn;
    public function __construct(Database $database, private string $key)
    {
        $this->conn = $database->getConnection();
    }

    public function create(string $token, int $expiry): bool
    {
        $hashed_token = hash_hmac('sha256', $token, $this->key);

        $sql = 'INSERT INTO refresh_token (token_hash, expires_at) values (:token_hash, :expires_at)';
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, \PDO::PARAM_STR);
        $stmt->bindValue(':expires_at', $expiry, \PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete(string $token): int
    {
        $hashed_token = hash_hmac('sha256', $token, $this->key);

        $sql = 'DELETE FROM refresh_token
                WHERE token_hash = :token_hash';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':token_hash', $hashed_token, \PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function getByToken(string $token): array | false
    {
        $hashed_token = hash_hmac("sha256", $token, $this->key);

        $sql = 'SELECT * FROM refresh_token
                WHERE token_hash = :token_hash';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':token_hash', $hashed_token);

        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}