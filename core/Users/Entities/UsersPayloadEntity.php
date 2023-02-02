<?php

namespace Core\Users\Entities;

use App\Traits\BaseEntityTrait;
use CodeIgniter\Entity\Entity;

class UsersPayloadEntity extends Entity
{
    use BaseEntityTrait;

    public static $attributesName;

    protected $attributes = [
        'id' => null,
        'name' => null,
        'phone_number' => null,
        'username' => null,
        'password' => null,
        'role' => null,
        'm_districts_id' => null,
        'm_villages_id' => null,
        'last_login' => null,
        'is_deleted' => null,
        'updated_at' => null,
        'updated_by' => null,
        'created_at' => null,
        'created_by' => null
    ];

    protected $casts = [
        'id' => 'int'
    ];

    public function setPassword($password)
    {
        if (empty($password)) {
            return null;
        }

        $this->attributes['password'] = password_hash($password, PASSWORD_BCRYPT);
    }

    public function setIsDeleted($isDeleted) {
        $this->attributes['is_deleted'] = empty($isDeleted) ? 0 : $isDeleted;
    }
}
