<?php

namespace Core\Recaps\Models;

use App\Traits\ConvertEntityTrait;
use Core\Recaps\Entities\RecapEntities;

class GetRecapModel extends BaseRecapModel
{
    use ConvertEntityTrait;

    protected $table = 'voters_summaries';
    protected $returnType = 'array';

    protected $districtId;
    protected $villageId;
    protected $statusDataId;

    public function getAll()
    {
        $this->select([
            'm_data_status_id',
            'm_data_status.name status_data',
            'm_villages_id',
            'm_villages.village_name',
            'm_villages.m_districts_id',
            'm_districts.district_name',
            'tps',
            'voter_m',
            'voter_f',
            'new_voter_m',
            'new_voter_f',
            'novice_voter_m',
            'novice_voter_f',
            'ktp_el_m',
            'ktp_el_f',
            'disabilities_m',
            'disabilities_f',
            'profile_updated_m',
            'profile_updated_f',
            'unknown_m',
            'unknown_f',
            'pass_away_m',
            'pass_away_f',
            'double_m',
            'double_f',
            'minor_m',
            'minor_f',
            'tni_m',
            'tni_f',
            'polri_m',
            'polri_f'
        ])->table('voters_summaries')
        ->join('m_data_status', 'voters_summaries.m_data_status_id = m_data_status.id')
        ->join('m_villages', 'voters_summaries.m_villages_id = m_villages.id')
        ->join('m_districts', 'm_villages.m_districts_id = m_districts.id')
        ->orderBy('m_districts.district_name ASC, m_villages.village_name ASC, voters_summaries.tps ASC');

        if (!empty($this->statusDataId)) {
            $this->where('voters_summaries.m_data_status_id', $this->statusDataId);
        }

        if (!empty($this->villageId)) {
            $this->where('voters_summaries.m_villages_id', $this->villageId);
        }

        if (!empty($this->districtId)) {
            $this->where('m_villages.m_districts_id', $this->districtId);
        }

        $recaps = $this->find();
        return $this->convertEntity(RecapEntities::class, $recaps);
    }

    public function setDistrictId($districtId)
    {
        $this->districtId = $districtId;
        return $this;
    }

    public function setVillageId($villageId)
    {
        $this->villageId = $villageId;
        return $this;
    }

    public function setStatusDataId($statusDataId)
    {
        $this->statusDataId = $statusDataId;
        return $this;
    }
}
