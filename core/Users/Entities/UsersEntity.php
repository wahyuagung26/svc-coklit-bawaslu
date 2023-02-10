<?php

namespace Core\Users\Entities;

use App\Traits\BaseEntityTrait;
use CodeIgniter\Entity\Entity;

class UsersEntity extends Entity
{
    use BaseEntityTrait;

    protected $attributes = [
        'id' => null,
        'name' => null,
        'phone_number' => null,
        'username' => null,
        'password' => null,
        'role' => null,
        'last_m_data_status_id' => null,
        'm_districts_id' => null,
        'm_villages_id' => null,
        'district_name' => null,
        'village_name' => null,
        'last_login' => null,
        'is_deleted' => null,
        'updated_at' => null,
        'updated_by' => null,
        'created_at' => null,
        'created_by' => null
    ];

    protected $casts = [
        'id' => 'int',
        'last_m_data_status_id' => 'int',
        'm_districts_id' => 'int',
        'm_villages_id' => 'int'
    ];

    protected $datamap = [
        // property_name => db_column_name
        'district_id' => 'm_districts_id',
        'village_id' => 'm_villages_id',
        'last_data_status_id' => 'last_m_data_status_id',
    ];

    public function setPassword($password)
    {
        if (empty($password)) {
            return null;
        }

        $this->attributes['password'] = password_hash($password, PASSWORD_BCRYPT);
    }

    public function setIsDeleted($isDeleted)
    {
        $this->attributes['is_deleted'] = empty($isDeleted) ? 0 : $isDeleted;
    }
}
