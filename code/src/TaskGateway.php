<?php

namespace PHPApi\Code;

class TaskGateway
{
    private \PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAllForUser(int $user_id): array
    {
        $sql = "SELECT * FROM tasks 
                WHERE user_id = :user_id
                ORDER BY name";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":user_id", $user_id, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getForUser(string $task_id, int $user_id): array | false
    {
        $sql = "SELECT * FROM tasks 
                WHERE id = :id
                AND user_id = :user_id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $task_id, \PDO::PARAM_INT);
        $stmt->bindValue(":user_id", $user_id, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function createForUser(array $data, int $user_id): string
    {
        $sql = "INSERT INTO tasks (name, user_id) VALUES (:name, :user_id)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":name", $data["name"], \PDO::PARAM_STR);
        $stmt->bindValue(":user_id", $user_id, \PDO::PARAM_INT);
        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    public function updateForUser(string $id, array $data, int $user_id): int
    {
        $fields = [];

        if( !empty($data['name']) ) {
            $fields['name'] = [$data['name'] , \PDO::PARAM_STR];
        }

        if(count($fields) === 0)
            return 0;


        $sets = array_map(fn($value) => "$value = :$value", array_keys($fields));
        $sql = 'UPDATE tasks'
            . ' SET ' . implode(', ', $sets)
            . ' WHERE id = :id'
            . ' AND user_id = :user_id';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, \PDO::PARAM_INT);
        $stmt->bindValue(":user_id", $user_id, \PDO::PARAM_INT);

        foreach($fields as $name => $value) {
            $stmt->bindValue(":$name", $value[0], $value[1]);
        }

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function deleteForUser(string $id, int $user_id): int
    {
        $sql = "DELETE FROM tasks
                WHERE id = :id
                AND user_id = :user_id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, \PDO::PARAM_INT);
        $stmt->bindValue(":user_id", $user_id, \PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }
}