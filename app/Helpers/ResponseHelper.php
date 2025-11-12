<?php

/**
 * File: helpers/ResponseHelper.php
 * Description: Helper untuk menangani HTTP responses
 */

class ResponseHelper
{
    /**
     * Send JSON response to client
     * @param array $data Data yang akan dikirim sebagai JSON
     */
    public static function json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Send success response
     * @param string $message Pesan sukses
     * @param array $data Data tambahan (optional)
     */
    public static function success($message, $data = [])
    {
        $response = [
            'success' => true,
            'message' => $message
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        self::json($response);
    }

    /**
     * Send error response
     * @param string $message Pesan error
     * @param int $statusCode HTTP status code (default: 400)
     */
    public static function error($message)
    {
        self::json([
            'success' => false,
            'message' => $message
        ]);
    }

    /**
     * Send validation error response
     * @param array $errors Array of validation errors
     */
    public static function validationError($errors)
    {
        self::json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $errors
        ]);
    }
}
