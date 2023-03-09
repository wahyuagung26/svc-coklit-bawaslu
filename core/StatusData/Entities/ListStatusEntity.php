<?php

namespace Core\StatusData\Entities;

use App\Traits\BaseEntityTrait;
use CodeIgniter\Entity\Entity;

class ListStatusEntity extends Entity
{
    use BaseEntityTrait;

    protected $attributes = [
        'name' => null,
        'village_id' => null,
        'village_name' => null,
        'district_id' => null,
        'district_name' => null,
        'total_checked' => null,
        'total_unchecked' => null,
        'active_table_source' => null,
        'last_m_data_status_id' => null,
    ];

    protected $casts = [
        'id' => 'int',
        'total_checked' => 'int',
        'total_unchecked' => 'int'
    ];
}
