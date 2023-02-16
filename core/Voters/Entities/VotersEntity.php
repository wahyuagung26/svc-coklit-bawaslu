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
        'tms' => null,
        'disabilities' => null,
        'is_coklit' => null,
        'is_ktp_el' => null,
        'is_new_voter' => null,
        'is_novice_voter' => null,
        'is_profile_updated' => null,
        'is_checked' => null,
        'is_deleted' => null,
        'is_new_data' => null,
        'voters_original_id' => null,
    ];

    protected $casts = [
        'id' => 'int',
        'tps' => 'int',
        'is_coklit' => 'bool',
        'is_ktp_el' => 'bool',
        'is_new_voter' => 'bool',
        'is_novice_voter' => 'bool',
        'is_profile_updated' => 'bool',
        'is_checked' => 'bool',
        'is_deleted' => 'bool',
        'is_new_data' => 'bool',
    ];

    public function setGender($gender)
    {
        $master = [1 => 'Laki-Laki', 2 => 'Perempuan'];
        $this->attributes['gender'] = $master[$gender] ?? '-';
    }

    public function setMarriedStatus($married)
    {
        $master = [1 => 'Belum Kawin', 2 => 'Sudah Kawin'];
        $this->attributes['married_status'] = $master[$married] ?? '-';
    }
}
