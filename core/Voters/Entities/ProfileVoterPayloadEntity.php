<?php

namespace Core\Voters\Entities;

class ProfileVoterPayloadEntity extends VotersEntity
{
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
    ];
}
