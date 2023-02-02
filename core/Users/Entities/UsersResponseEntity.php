<?php

namespace Core\Users\Entities;

use App\Traits\BaseEntityTrait;
use CodeIgniter\Entity\Entity;

class UsersResponseEntity extends Entity
{
    use BaseEntityTrait;

    protected $attributes = [
        'id' => null,
        'name' => null,
        'phone_number' => null,
        'district_id' => null,
        'district_name' => null,
        'village_id' => null,
        'village_name' => null,
        'role' => null,
        'last_login' => null
    ];

    protected $casts = [
        'id' => 'int'
    ];
}
