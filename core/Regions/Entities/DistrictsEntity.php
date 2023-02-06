<?php

namespace Core\Regions\Entities;

use App\Traits\BaseEntityTrait;
use CodeIgniter\Entity\Entity;

class DistrictsEntity extends Entity
{
    use BaseEntityTrait;

    protected $attributes = [
        'id' => null,
        'district_name' => null,
    ];

    protected $casts = [
        'id' => 'int',
    ];
}
