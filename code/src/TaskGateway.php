<?php

namespace PHPApi\Code;

class TaskGateway
{
    private \PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM tasks ORDER BY name";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function get(string $id): array | false
    {
        $sql = "SELECT * FROM tasks WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create(array $data): string
    {
        $sql = "INSERT INTO tasks (name) VALUES (:name)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":name", $data["name"], \PDO::PARAM_STR);
        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    public function update(string $id, array $data): int
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
            . ' WHERE id = :id';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id);
        foreach($fields as $name => $value) {
            $stmt->bindValue(":$name", $value[0], $value[1]);
        }

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function delete(string $id): int
    {
        $sql = "DELETE FROM tasks
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();

        return $stmt->rowCount();
    }
}