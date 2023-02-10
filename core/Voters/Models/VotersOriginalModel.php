<?php

namespace Core\Voters\Models;

class VotersOriginalModel extends BaseVotersModel
{
    protected $table = 'voters_original';
    protected $allowedFields = [
        'code',
        'm_districts_id',
        'm_villages_id',
        'dp_id',
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
        'filters',
        'm_data_status_id',
        'tps',
        'sort_data',
    ];
}
