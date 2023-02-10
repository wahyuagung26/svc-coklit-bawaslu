<?php

namespace App\Traits;

trait ResponseApiTrait
{
    public function successResponse(
        $data,
        string $message = '',
        int $statusCode = HTTP_STATUS_SUCCESS
    ) {
        return $this->response->setStatusCode($this->setStatusCode($statusCode))->setJson([
            'message' => $message,
            'data' => &$data
        ]);
    }

    public function paginationResponse(
        $data,
        array $metadata,
        string $message = '',
        int $statusCode = HTTP_STATUS_SUCCESS
    ) {
        return $this->response->setStatusCode($this->setStatusCode($statusCode))->setJson([
            'message' => $message,
            'data' => &$data,
            'meta' => [
                'page' => (int) $metadata['page'] ?? 1,
                'total_item' => (int) $metadata['total_item'] ?? 0,
                'per_page' => (int) $metadata['per_page'] ?? DEFAULT_PER_PAGE
            ]
        ]);
    }

    public function failedValidationResponse(
        array $error,
        string $message = '',
        int $statusCode = HTTP_STATUS_FAILED_VALIDATION
    ) {
        $this->response->setStatusCode($this->setStatusCode($statusCode))->setJson([
            'message' => $message,
            'errors' => &$error
        ])->send();

        exit;
    }

    public function errorResponse(
        string $message = '',
        int $statusCode = HTTP_STATUS_SERVER_ERROR
    ) {
        $this->response->setStatusCode($this->setStatusCode($statusCode))->setJson([
            'message' => $message,
            'errors' => $message
        ])
        ->send();

        exit;
    }

    private function setStatusCode($statusCode)
    {
        if (in_array($statusCode, [
            HTTP_STATUS_SUCCESS,
            HTTP_STATUS_CONFLICT,
            HTTP_STATUS_NOT_FOUND,
            HTTP_STATUS_UNPROCESS,
            HTTP_STATUS_UNAUTHORIZE,
            HTTP_STATUS_SERVER_ERROR,
            HTTP_STATUS_FORBIDDEN_ACCESS,
            HTTP_STATUS_FAILED_VALIDATION
        ])) {
            return $statusCode;
        }

        return HTTP_STATUS_SERVER_ERROR;
    }
}
