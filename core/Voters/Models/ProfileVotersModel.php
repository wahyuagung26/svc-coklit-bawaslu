<?php

namespace Core\Voters\Models;

class ProfileVotersModel extends BaseVotersModel
{
    public function __construct()
    {
        parent::__construct();
    }
    protected $allowedFields = [
        'm_district_id',
        'm_village_id',
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
