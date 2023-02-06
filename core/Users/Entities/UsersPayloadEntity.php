<?php

namespace Core\Users\Entities;

class UsersPayloadEntity extends UsersEntity
{
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
}
