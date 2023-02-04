<?php

namespace Core\Voters\Entities;

use App\Traits\BaseEntityTrait;
use CodeIgniter\Entity\Entity;

class VotersResponseEntity extends Entity
{
    use BaseEntityTrait;

    protected $attributes = [
        'id' => null,
        'code' => null,
        'm_districts_id' => null,
        'm_villages_id' => null,
        'dp_id' => null,
        'nkk' => null,
        'nik' => null,
        'name' => null,
        'place_of_birth' => null,
        'date_of_birth' => null,
        'married_status' => null,
        'gender' => null,
        'address' => null,
        'rt' => null,
        'rw' => null,
        'disabilities' => null,
        'filters' => null,
        'm_data_status_id' => null,
        'tps' => null,
        'sort_data' => null,
    ];

    protected $casts = [
        'id' => 'int',
        'married_status' => 'int',
        'gender' => 'int',
        'tps' => 'int'
    ];
}
