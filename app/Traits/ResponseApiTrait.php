<?php

namespace App\Traits;

trait ResponseApiTrait
{
    public function successResponse($data, string $message = '', int $statusCode = HTTP_STATUS_SUCCESS)
    {
        $this->response->setStatusCode($statusCode)->setJson([
            'message' => $message,
            'data' => $data
        ])->send();

        exit;
    }

    public function paginationResponse($data, array $metadata, $message = '', int $statusCode = HTTP_STATUS_SUCCESS)
    {
        $this->response->setStatusCode($statusCode)->setJson([
            'message' => $message,
            'data' => $data,
            'meta' => [
                'page' => $metadata['page'] ?? 1,
                'total_item' => $metadata['total_item'] ?? 0,
                'per_page' => $metadata['per_page'] ?? DEFAULT_PER_PAGE
            ]
        ])->send();

        exit;
    }

    public function failedValidationResponse(array $error, string $message = '', int $statusCode = HTTP_STATUS_FAILED_VALIDATION)
    {
        $this->response->setStatusCode($statusCode)->setJson([
            'message' => $message,
            'error' => $error
        ])->send();

        exit;
    }

    public function errorResponse($message = '', int $statusCode = HTTP_STATUS_SERVER_ERROR)
    {
        $this->response->setStatusCode($statusCode)->setJson([
            'message' => $message
        ])
        ->send();

        exit;
    }
}
