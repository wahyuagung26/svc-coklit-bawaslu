<?php

namespace Core\Users\Controllers;

use App\Controllers\BaseController;
use Core\Users\Models\GetUserModel;

class AuthController extends BaseController
{
    private $loginRule = [
        'username' => 'required|max_length[20]',
        'password' => 'required'
    ];

    public function login()
    {
        $this->runPayloadValidation($this->loginRule, $this->payload);

        $model = new GetUserModel();
        $user = $model->auth($this->payload['username'] ?? '', $this->payload['password'] ?? '');
        if (isset($user['id'])) {
            return $this->successResponse($user);
        }

        return $this->failedValidationResponse(['Username or password is wrong']);
    }

    public function profile()
    {
    }
}
