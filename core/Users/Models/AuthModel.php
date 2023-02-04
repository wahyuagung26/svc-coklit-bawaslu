<?php

namespace Core\Users\Models;

use Core\Users\Entities\AuthEntity;
use Core\Users\Models\GetUserModel;

class AuthModel extends GetUserModel
{
    public function login(string $username, string $password)
    {
        $query = $this->getUsers()->where('username', $username);
        $user = $query->first();
        if (empty($user)) {
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            return false;
        }

        return $this->convertEntity(AuthEntity::class, $user);
    }
}
