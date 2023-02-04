<?php

namespace Core\Voters\Models;

use App\Models\CoreModel;
use App\Traits\ConvertEntityTrait;
use Core\Voters\Entities\VotersEntity;

class BaseVotersModel extends CoreModel
{
    use ConvertEntityTrait;

    protected $table = 'voters_pra_dps';
    protected $sourceTable = 'voters_original';
    protected $villageId = '';

    public function setActiveTable(string $tableName)
    {
        $this->table = $tableName;
        return $this;
    }

    public function setSourceTable(string $tableName)
    {
        $this->sourceTable = $tableName;
        return $this;
    }

    public function setVillageId($villageId)
    {
        $this->villageId = $villageId;
        return $this;
    }

    protected function getVoters() {
        return $this->select([
            "{$this->table}.id",
            "{$this->table}.voters_original_id",
            "{$this->table}.nik",
            "{$this->table}.nkk",
            "{$this->table}.name",
            "{$this->table}.place_of_birth",
            "{$this->table}.date_of_birth",
            "{$this->table}.gender",
            "{$this->table}.married_status",
            "{$this->table}.address",
            "{$this->table}.rt",
            "{$this->table}.rw",
            "{$this->table}.m_villages_id as village_id",
            "m_villages.village_name",
            "{$this->table}.m_districts_id as district_id",
            "m_districts.district_name",
            "{$this->table}.tps",
            "{$this->table}.tms",
            "{$this->table}.disabilities",
            "{$this->table}.is_coklit",
            "{$this->table}.is_ktp_el",
            "{$this->table}.is_new_voter",
            "{$this->table}.is_novice_voter",
            "{$this->table}.is_profile_updated",
            "{$this->table}.is_checked",
            "{$this->table}.is_deleted",
            "{$this->table}.is_new_data",
        ])->table($this->table)
        ->join("m_districts", "m_districts_id = m_districts.id", "left")
        ->join("m_villages", "m_villages_id = m_villages.id", "left");
    }

    public function getById($id)
    {
        $voter = $this->getVoters()->find($id);
        return $this->convertEntity(VotersEntity::class, $voter);
    }
}
