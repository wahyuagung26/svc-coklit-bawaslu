<?php

namespace Core\Users\Controllers;

use App\Controllers\BaseController;
use Core\Users\Models\GetUserModel;

class GetUserController extends BaseController
{
    private $user;

    public function __construct()
    {
        $this->user = new GetUserModel();
    }

    public function index()
    {
        try {
            $payload = $this->getPayload();
            $payload['page'] = $payload['page'] ?? 1;

            $users = $this->user->getUsers()
                                ->setFilter($payload)
                                ->pagination($payload['page']);

            return $this->paginationResponse($users['data'], $users['meta']);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }

    public function getById($userId)
    {
        try {
            $user = $this->user->getById($userId);

            if (isset($user->id)) {
                return $this->successResponse($user);
            }

            return $this->successResponse(null, 'user not found', HTTP_STATUS_NOT_FOUND);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }
}
