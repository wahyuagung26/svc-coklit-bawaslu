<?php

namespace Core\Voters\Entities;

use App\Traits\BaseEntityTrait;
use CodeIgniter\Entity\Entity;

class VotersEntity extends Entity
{
    use BaseEntityTrait;

    protected $attributes = [
        'id' => null,
        'district_id' => null,
        'district_name' => null,
        'village_id' => null,
        'village_name' => null,
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
        'tps' => null,
        'disabilities' => null,
        'is_coklit' => null,
        'is_ktp_el' => null,
        'is_new_voter' => null,
        'is_novice_voter' => null,
        'is_profile_updated' => null,
        'is_checked' => null,
        'is_deleted' => null,
    ];

    protected $casts = [
        'id' => 'int',
        'married_status' => 'int',
        'gender' => 'int',
        'tps' => 'int',
        'is_coklit' => 'bool',
        'is_ktp_el' => 'bool',
        'is_new_voter' => 'bool',
        'is_novice_voter' => 'bool',
        'is_profile_updated' => 'bool',
        'is_checked' => 'bool',
        'is_deleted' => 'bool',
    ];
}