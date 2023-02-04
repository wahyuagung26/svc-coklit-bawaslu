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
        ->join("m_districts", "m_districts_id = m_districts.id", "left")
        ->join("m_villages", "m_villages_id = m_villages.id", "left")
        ->where("{$this->table}.is_deleted", 0);

        return $this;
    }

    public function setFilter($payload)
    {
        if (!empty($this->villageId)) {
            $this->where("m_villages.m_villages_id", $this->villageId);
        }

        if (isset($payload["villages_id"])) {
            $this->where("m_villages.id", $payload["villages_id"]);
        }

        if (isset($payload["districts_id"])) {
            $this->where("m_districts.id", $payload["districts_id"]);
        }

        if (isset($payload["nik"])) {
            $this->where("{$this->table}.nik", $payload["nik"]);
        }

        if (isset($payload["rt"])) {
            $this->where("{$this->table}.rt", $payload["rt"]);
        }

        if (isset($payload["rw"])) {
            $this->where("{$this->table}.rw", $payload["rw"]);
        }

        if (isset($payload["tps"])) {
            $this->where("{$this->table}.tps", $payload["tps"]);
        }

        if (isset($payload["married_status"])) {
            $this->where("{$this->table}.married_status", $payload["married_status"]);
        }

        if (isset($payload["is_coklit"])) {
            $this->where("{$this->table}.is_coklit", $payload["is_coklit"]);
        }

        if (isset($payload["is_new_voter"])) {
            $this->where("{$this->table}.is_new_voter", $payload["is_new_voter"]);
        }

        if (isset($payload["is_novice_voter"])) {
            $this->where("{$this->table}.is_novice_voter", $payload["is_novice_voter"]);
        }

        if (isset($payload["tms"])) {
            $this->where("{$this->table}.tms", $payload["tms"]);
        }

        if (isset($payload["disabilities"])) {
            $this->where("{$this->table}.disabilities", $payload["disabilities"]);
        }

        if (isset($payload["is_profile_updated"])) {
            $this->where("{$this->table}.is_profile_updated", $payload["is_profile_updated"]);
        }

        if (isset($payload["is_checked"])) {
            $this->where("{$this->table}.is_checked", $payload["is_checked"]);
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
                "page"  => $page,
                "per_page" => DEFAULT_PER_PAGE
            ]
        ];
    }
}
