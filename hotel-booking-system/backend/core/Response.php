<?php

class Response
{
    public static function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function success($data = null, string $message = 'OK', int $status = 200): void
    {
        $payload = ['success' => true, 'message' => $message];
        if ($data !== null) {
            if (is_array($data) && array_is_list($data)) {
                $payload['data'] = $data;
            } else {
                $payload = array_merge($payload, is_array($data) ? $data : ['data' => $data]);
            }
        }
        self::json($payload, $status);
    }

    public static function error(string $message, int $status = 400, array $errors = []): void
    {
        $payload = ['success' => false, 'message' => $message];
        if ($errors) {
            $payload['errors'] = $errors;
        }
        self::json($payload, $status);
    }
}
