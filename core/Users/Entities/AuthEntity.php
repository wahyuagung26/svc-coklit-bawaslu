<?php

namespace Core\Users\Entities;

use Firebase\JWT\JWT;
use App\Traits\BaseEntityTrait;
use CodeIgniter\Entity\Entity;

class AuthEntity extends Entity
{
    use BaseEntityTrait;

    protected $attributes = [
        'id' => null,
        'name' => null,
        'phone_number' => null,
        'username' => null,
        'district_id' => null,
        'district_name' => null,
        'village_id' => null,
        'village_name' => null,
        'role' => null,
        'last_login' => null,
        'token' => null,
    ];

    protected $casts = [
        'id' => 'int'
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
}
