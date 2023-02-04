<?php

namespace Core\Voters\Models;

class StatusVotersModel extends BaseVotersModel
{
    protected $allowedFields = [
        'disabilities',
        'tms',
        'is_coklit',
        'is_ktp_el',
        'is_new_voter',
        'is_novice_voter',
        'is_profile_updated',
        'is_checked',
    ];
}
