<?php

namespace App\Traits;

trait ResponseApiTrait
{
    public function successResponse($data, string $message = '', int $statusCode = HTTP_STATUS_SUCCESS)
    {
        return $this->response->setStatusCode($statusCode)->setJson([
            'message' => $message,
            'data' => &$data
        ]);
    }

    public function paginationResponse($data, array $metadata, $message = '', int $statusCode = HTTP_STATUS_SUCCESS)
    {
        return $this->response->setStatusCode($statusCode)->setJson([
            'message' => $message,
            'data' => &$data,
            'meta' => [
                'page' => (int) $metadata['page'] ?? 1,
                'total_item' => (int) $metadata['total_item'] ?? 0,
                'per_page' => (int) $metadata['per_page'] ?? DEFAULT_PER_PAGE
            ]
        ]);
    }

    public function failedValidationResponse(array $error, string $message = '', int $statusCode = HTTP_STATUS_FAILED_VALIDATION)
    {
        $this->response->setStatusCode($statusCode)->setJson([
            'message' => $message,
            'errors' => &$error
        ])->send();

        exit;
    }

    public function errorResponse($message = '', int $statusCode = HTTP_STATUS_SERVER_ERROR)
    {
        $this->response->setStatusCode($statusCode)->setJson([
            'message' => $message,
            'errors' => $message
        ])
        ->send();

        exit;
    }
}
