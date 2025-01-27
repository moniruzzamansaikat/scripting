<?php

namespace Src;

class Response
{
    // Send a success response with data
    public static function success($data, $statusCode = 200)
    {
        self::send($data, $statusCode);
    }

    // Send an error response with message
    public static function error($message, $statusCode = 400)
    {
        self::send(['error' => $message], $statusCode);
    }

    // Send a paginated response
    public static function paginated($data, $page, $limit, $total, $statusCode = 200)
    {
        $response = [
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'totalPages' => ceil($total / $limit),
            'data' => $data
        ];
        self::send($response, $statusCode);
    }

    // Generic function to send the response
    private static function send($data, $statusCode)
    {
        http_response_code($statusCode); // Set the HTTP status code
        header('Content-Type: application/json'); // Set the response format
        echo json_encode($data, JSON_PRETTY_PRINT); // Encode and send the response
    }
}
