<?php

namespace Core\StatusData\Entities;

use App\Traits\BaseEntityTrait;
use CodeIgniter\Entity\Entity;

class StatusDataEntity extends Entity
{
    use BaseEntityTrait;

    protected $attributes = [
        'id' => null,
        'name' => null,
        'active_table_source' => null,
        'prev_table_source' => null,
    ];

    protected $casts = [
        'id' => 'int'
    ];
}
