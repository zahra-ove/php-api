<?php

namespace PHPApi\Code;

class TaskController
{
    public function __construct(private readonly TaskGateway $taskGateway,
                                private readonly int $user_id)
    {
    }

    public function processRequest(string $method, ?string $id): string
    {
        if($id === null) {
            if ($method === 'GET') {

                return  json_encode($this->taskGateway->getAllForUser($this->user_id));
            } elseif ($method === 'POST') {

                $data = (array) json_decode(file_get_contents("php://input"));

                if( !empty($errors = $this->validateTask($data)) ) {
                    return $this->respondUnprocessableEntity($errors);
                }

                $id = $this->taskGateway->createForUser($data, $this->user_id);

                return $this->respondCreated($id);
            } else {

                $this->responseMethodNotAllowed('GET, POST');
            }

        } else {


            switch($method) {
                case "GET":
                    $task = $this->taskGateway->getForUser($id, $this->user_id);
                    if($task === false) {
                        return $this->respondNotFound($id);
                    }
                    return json_encode($task);

                case "PATCH":
                    $data = (array) json_decode(file_get_contents("php://input"));
                    if( !empty($errors = $this->validateTask($data, false)) ) {
                        return $this->respondUnprocessableEntity($errors);
                    }
                    $rows = $this->taskGateway->updateForUser($id, $data, $this->user_id);
                    return json_encode(["message"=> "Task Updated", "rows" => $rows]);

                case "DELETE":
                    $rows = $this->taskGateway->deleteForUser($id, $this->user_id);
                    return json_encode(["message" => "Task Deleted", "rows" => $rows]);

                default:
                    $this->responseMethodNotAllowed('GET, POST');
            }
        }
    }

    private function responseMethodNotAllowed(string $allowed_methods): void
    {
        http_response_code(405);
        header("Allow: {$allowed_methods}");
    }

    private function respondNotFound(string $id): string
    {
        http_response_code(404);
        return json_encode(['message' => "Task with ID $id not found."]);
    }

    private function respondCreated(string $id): string
    {
        http_response_code(201);
        return json_encode(["messsage" => "Task created.", "id" => $id]);
    }

    private function respondUnprocessableEntity(array $errors): string
    {
        http_response_code(422);
        return json_encode(["errors" => $errors]);
    }

    private function validateTask(array $data, $is_new = true): array
    {
        $errors = [];
        if( $is_new && ! isset($data['name']) ) {
            $errors[] = 'name field is required';
        }

        return $errors;
    }
}