<?php

namespace Core\Voters\Models;

class VotersPraDpsModel extends BaseVotersModel
{
    protected $table = 'voters_pra_dps';
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
        'disabilities',
        'tps',
    ];

    public function softDeleteAll($districtId)
    {
        $this->db->query('update voters_pra_dps set is_deleted = 0 where m_districts_id = "'.$districtId.'"');
        return true;
    }
}
