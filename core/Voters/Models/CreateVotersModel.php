<?php

namespace Core\Voters\Models;

class CreateVotersModel extends BaseVotersModel
{
    protected $allowedFields = [
        'm_districts_id',
        'm_villages_id',
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
        'disabilities',
        'tms',
        'is_coklit',
        'is_ktp_el',
        'is_new_voter',
        'is_novice_voter',
        'is_profile_updated',
        'is_new_data',
        'is_checked'
    ];
}
