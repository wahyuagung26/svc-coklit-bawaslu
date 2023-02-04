<?php

namespace Core\Regions\Entities;

use App\Traits\BaseEntityTrait;
use CodeIgniter\Entity\Entity;

class VillagesEntity extends Entity
{
    use BaseEntityTrait;

    protected $attributes = [
        'id' => null,
        'districts_id' => null,
        'village_name' => null,
    ];

    protected $casts = [
        'id' => 'int',
        'districts_id' => 'int'
    ];
}
