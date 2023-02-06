<?php

namespace Core\Users\Entities;

class UsersResponseEntity extends UsersEntity
{
    protected $attributes = [
        'id' => null,
        'name' => null,
        'username' => null,
        'phone_number' => null,
        'm_districts_id' => null,
        'district_name' => null,
        'm_villages_id' => null,
        'village_name' => null,
        'role' => null,
        'last_login' => null
    ];
}
