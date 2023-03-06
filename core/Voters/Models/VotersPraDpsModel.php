<?php

namespace Core\Voters\Models;

class VotersPraDpsModel extends BaseVotersModel
{
    protected $table = 'voters_pra_dps';
    protected $allowedFields = [
        'code',
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
        'disabilities',
        'm_data_status_id',
        'tps',
    ];
}
