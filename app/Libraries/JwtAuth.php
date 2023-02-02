<?php

namespace App\Libraries;

use Core\Users\Models\GetUserModel;

class JwtAuth
{
    public static $userId;
    public static $user;

    public static function setUserId(string $userId)
    {
        JwtAuth::$userId = $userId;
    }

    public static function user()
    {
        $userId = JwtAuth::$userId ?? '';
        if (empty($userId)) {
            return null;
        }

        if (empty(JwtAuth::$user)) {
            $userModel = new GetUserModel();
            JwtAuth::$user = $userModel->getById($userId);
        }

        return JwtAuth::$user;
    }
}
