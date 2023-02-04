<?php

namespace Core\Voters\Models;

class ProfileVotersModel extends BaseVotersModel
{
    protected $allowedFields = [
        'district_id',
        'district_name',
        'village_id',
        'village_name',
        'nkk',
        'nik',
        'name',
        'place_of_birth',
        'date_of_birth',
        'married_status',
        'gender',
        'address',
        'rt',
        'rw',
        'tps',
    ];
}
