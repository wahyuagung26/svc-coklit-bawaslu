<?php

namespace Core\Users\Entities;

use Firebase\JWT\JWT;

class AuthEntity extends UsersEntity
{
    protected $attributes = [
        'id' => null,
        'name' => null,
        'username' => null,
        'phone_number' => null,
        'username' => null,
        'm_districts_id' => null,
        'district_name' => null,
        'm_villages_id' => null,
        'village_name' => null,
        'role' => null,
        'last_login' => null,
        'token' => null,
    ];

    public function setToken($token)
    {
        $iat = time(); // current timestamp value
        $exp = $iat + (3600 * 2); // 2 hours will expired

        $payload = array(
            "iat" => $iat,
            "exp" => $exp,
            "id" => $this->attributes['id'] ?? 0,
            "username" => $this->attributes['username'] ?? ''
        );

        $token = JWT::encode($payload, getenv('JWT_SECRET'), getenv('JWT_ALGO'));
        $this->attributes['token'] = $token;
    }

    public function setPassword($password)
    {
        $this->attributes['password'] = '';
    }
}
