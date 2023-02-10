<?php

namespace Core\Recaps\Entities;

use App\Traits\BaseEntityTrait;
use CodeIgniter\Entity\Entity;

class RecapEntities extends Entity
{
    use BaseEntityTrait;

    protected $attributes = [
        'm_data_status_id' => null,
        'status_data' => null,
        'm_villages_id' => null,
        'village_name' => null,
        'm_districts_id' => null,
        'district_name' => null,
        'tps' => null,
        'voter_m' => null,
        'voter_f' => null,
        'new_voter_m' => null,
        'new_voter_f' => null,
        'novice_voter_m' => null,
        'novice_voter_f' => null,
        'ktp_el_m' => null,
        'ktp_el_f' => null,
        'disabilities_m' => null,
        'disabilities_f' => null,
        'profile_updated_m' => null,
        'profile_updated_f' => null,
        'unknown_m' => null,
        'unknown_f' => null,
        'pass_away_m' => null,
        'pass_away_f' => null,
        'double_m' => null,
        'double_f' => null,
        'minor_m' => null,
        'minor_f' => null,
        'tni_m' => null,
        'tni_f' => null,
        'polri_m' => null,
        'polri_f' => null,
    ];

    protected $casts = [
        'm_data_status_id' => 'int',
        'm_villages_id' => 'int',
        'm_districts_id' => 'int',
        'tps' => 'int',
        'voter_m' => 'int',
        'voter_f' => 'int',
        'new_voter_m' => 'int',
        'new_voter_f' => 'int',
        'novice_voter_m' => 'int',
        'novice_voter_f' => 'int',
        'ktp_el_m' => 'int',
        'ktp_el_f' => 'int',
        'disabilities_m' => 'int',
        'disabilities_f' => 'int',
        'profile_updated_m' => 'int',
        'profile_updated_f' => 'int',
        'unknown_m' => 'int',
        'unknown_f' => 'int',
        'pass_away_m' => 'int',
        'pass_away_f' => 'int',
        'double_m' => 'int',
        'double_f' => 'int',
        'minor_m' => 'int',
        'minor_f' => 'int',
        'tni_m' => 'int',
        'tni_f' => 'int',
        'polri_m' => 'int',
        'polri_f' => 'int',
    ];

    protected $datamap = [
        // property_name => db_column_name
        'data_status_id' => 'm_data_status_id',
        'village_id' => 'm_villages_id',
        'district_id' => 'm_districts_id',
    ];
}
