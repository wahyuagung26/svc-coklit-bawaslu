<?php

namespace Core\Voters\Models;

use App\Traits\ConvertEntityTrait;
use Core\Voters\Entities\VotersEntity;

class GetVotersModel extends BaseVotersModel
{
    use ConvertEntityTrait;

    public function __construct()
    {
        parent::__construct();
    }

    protected $table = "voters_pra_dps";
    protected $returnType = "array";

    public function getAll()
    {
        $this->select([
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
        ])->table($this->table)
        ->join("m_districts", "m_districts_id = m_districts.id")
        ->join("m_villages", "m_villages_id = m_villages.id", "left")
        ->where("{$this->table}.is_deleted", 0);

        return $this;
    }

    public function setFilter($payload)
    {
        if (isset($payload["village_id"])) {
            $this->like("m_villages.m_villages_id", $payload["village_id"]);
        }

        if (isset($payload["district_id"])) {
            $this->like("m_districts.m_districts_id", $payload["districts_id"]);
        }

        return $this;
    }

    public function pagination($page = 1)
    {
        $limit = DEFAULT_PER_PAGE;
        $offset = DEFAULT_PER_PAGE * ($page - 1);

        $total = $this->countAllResults(false);
        $voters = $this->limit($limit, $offset)->find();
        $voters = $this->convertEntity(VotersEntity::class, $voters);
        return [
            "data"  => $voters ?? [],
            "meta"  => [
                "total_item" => $total ?? 0,
                "page"  => 1,
                "per_page" => DEFAULT_PER_PAGE
            ]
        ];
    }
}
