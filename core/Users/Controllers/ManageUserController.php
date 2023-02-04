<?php

namespace Core\Users\Controllers;

use App\Controllers\BaseController;
use Core\Users\Entities\UsersPayloadEntity;
use Core\Users\Models\GetUserModel;
use Core\Users\Models\ManageUserModel;

class ManageUserController extends BaseController
{
    private $user;

    public function __construct()
    {
        $this->user = new ManageUserModel();
    }

    private $createRule = [
        'name' => [
            'label' => 'Nama',
            'rules' => 'required|max_length[50]'
        ],
        'username' => [
            'label' => 'Username',
            'rules' => 'required|max_length[20]'
        ],
        'password' => [
            'label' => 'Password',
            'rules' => 'required'
        ],
        'role' => [
            'label' => 'Hak Akses',
            'rules' => 'required'
        ],
        'm_districts_id' => [
            'label' => 'Kecamatan',
            'rules' => 'required'
        ]
    ];

    private $updateRule = [
        'name' => [
            'label' => 'Nama',
            'rules' => 'required|max_length[50]'
        ],
        'username' => [
            'label' => 'Username',
            'rules' => 'required|max_length[20]'
        ],
        'role' => [
            'label' => 'Hak Akses',
            'rules' => 'required'
        ],
        'm_districts_id' => [
            'label' => 'Kecamatan',
            'rules' => 'required'
        ]
    ];

    public function create()
    {
        $this->runPayloadValidation($this->createRule, $this->payload);
        $this->checkIsUserAlreadyRegistered($this->payload['username'] ?? '');

        $user = new UsersPayloadEntity($this->payload);
        $model = $this->user->insert($user->toArray(true));

        return $this->successResponse($model);
    }

    public function update()
    {
        $this->runPayloadValidation($this->updateRule, $this->payload);
        $this->checkIsUserExists($this->payload['id']);
        $this->checkIsUserAlreadyRegistered($this->payload['username'], $this->payload['id']);

        $user = new UsersPayloadEntity($this->payload);
        $model = $this->user->update($this->payload['id'], $user->toArray(true));

        return $this->successResponse($model);
    }

    private function checkIsUserAlreadyRegistered($username, $exceptionId = null)
    {
        $model = new GetUserModel();
        $user = $model->getByUsername($username, $exceptionId);

        if (isset($user->id)) {
            return $this->errorResponse('username already registered', HTTP_STATUS_UNPROCESS);
        }

        return true;
    }

    private function checkIsUserExists($userId)
    {
        $model = new GetUserModel();
        $user = $model->getById($userId);

        if (!isset($user->id)) {
            return $this->errorResponse('user not found', HTTP_STATUS_UNPROCESS);
        }
    }
}
