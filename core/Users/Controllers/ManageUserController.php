<?php

namespace Core\Users\Controllers;

use App\Controllers\BaseController;
use Core\Users\Entities\UsersPayloadEntity;
use Core\Users\Models\GetUserModel;
use Core\Users\Models\ManageUserModel;
use Throwable;

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
        'phone_number' => [
            'label' => 'Nomor Telepon',
            'rules' => 'numeric|max_length[18]'
        ],
        'role' => [
            'label' => 'Hak Akses',
            'rules' => 'required'
        ],
        'district_id' => [
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
        'district_id' => [
            'label' => 'Kecamatan',
            'rules' => 'required'
        ],
        'phone_number' => [
            'label' => 'Nomor Telepon',
            'rules' => 'numeric|max_length[18]'
        ],
    ];

    public function create()
    {
        try {
            $this->runPayloadValidation($this->createRule, $this->payload)
                ->checkIsUserAlreadyRegistered();

            $user = new UsersPayloadEntity($this->payload);
            $payload = $user->toArray(true);
            $payload['m_districts_id'] = $this->payload['district_id'] ?? null;
            $payload['m_villages_id'] = $this->payload['village_id'] ?? null;

            $user = $this->user->insert($payload)->getLastInsert();
            return $this->successResponse($user);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }

    public function update()
    {
        try {
            $this->runPayloadValidation($this->updateRule, $this->payload)
                ->checkIsUserExists()
                ->checkIsUserAlreadyRegistered($this->payload['id']);

            $user = new UsersPayloadEntity($this->payload);
            $payload = $user->toArray(true);
            $payload['m_districts_id'] = $this->payload['district_id'] ?? null;
            $payload['m_villages_id'] = $this->payload['village_id'] ?? null;

            $this->user->update($payload['id'], $payload);
            return $this->successResponse($this->user->getById($payload['id']));
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }

    public function delete($userId)
    {
        try {
            $this->checkIsUserExists($userId);

            $user = $this->user->delete($userId);
            return $this->successResponse($user);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }

    private function checkIsUserAlreadyRegistered($exceptionId = null)
    {
        $username = $this->payload['username'] ?? null;
        $model = new GetUserModel();
        $user = $model->getByUsername($username, $exceptionId);

        if (isset($user->id)) {
            return $this->errorResponse('username already registered', HTTP_STATUS_UNPROCESS);
        }

        return $this;
    }

    private function checkIsUserExists()
    {
        $userId = $this->payload['id'] ?? null;
        $model = new GetUserModel();
        $user = $model->getById($userId);

        if (!isset($user->id)) {
            return $this->errorResponse('user not found', HTTP_STATUS_UNPROCESS);
        }

        return $this;
    }
}
