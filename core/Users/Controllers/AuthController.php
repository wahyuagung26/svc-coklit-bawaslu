<?php

namespace Core\Users\Controllers;

use App\Controllers\BaseController;
use Core\Users\Models\AuthModel;
use Core\Users\Models\GetUserModel;

class AuthController extends BaseController
{
    private $loginRule = [
        'username' => 'required|max_length[20]',
        'password' => 'required'
    ];

    public function login()
    {
        try {
            $this->runPayloadValidation($this->loginRule, $this->payload);

            $model = new AuthModel();
            $user = $model->login($this->payload['username'] ?? '', $this->payload['password'] ?? '');

            if (isset($user->id)) {
                $user->last_login = date('Y-m-d H:i:s');
                $model->save($user);

                return $this->successResponse($user);
            }

            return $this->failedValidationResponse(['Username or password is wrong']);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }

    public function profile()
    {
        try {
            $model = new GetUserModel();
            $user = $model->getById($this->user('id') ?? 0);

            if (isset($user->id)) {
                return $this->successResponse($user);
            }

            return $this->successResponse(null, 'user not found', HTTP_STATUS_NOT_FOUND);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }
}
