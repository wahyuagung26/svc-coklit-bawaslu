<?php

namespace Core\Regions\Entities;

use App\Traits\BaseEntityTrait;
use CodeIgniter\Entity\Entity;

class VillagesEntity extends Entity
{
    use BaseEntityTrait;

    protected $attributes = [
        'id' => null,
        'm_districts_id' => null,
        'village_name' => null,
        'last_m_data_status_id' => null,
    ];

    protected $casts = [
        'id' => 'int',
        'm_districts_id' => 'int',
        'last_m_data_status_id' => 'int'
    ];

    protected $datamap = [
        // property_name => db_column_name
        'districts_id' => 'm_districts_id',
        'last_data_status_id' => 'last_m_data_status_id'
    ];
}
