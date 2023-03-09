<?php

namespace Core\Voters\Models;

use Core\Voters\Entities\VotersEntity;

class GetVotersModel extends BaseVotersModel
{
    protected $table = "voters_pra_dps";

    private $districtId;

    public function getAll()
    {
        $this->getVoters();
        return $this;
    }

    public function setFilter($payload)
    {
        if (isset($payload["district_id"]) && !empty($payload["district_id"])) {
            $this->where("{$this->table}.m_districts_id", $payload["district_id"]);
        }

        if (!empty($this->villageId)) {
            $this->where("m_villages.m_villages_id", $this->villageId);
        }

        if (isset($payload["village_id"]) && !empty($payload["village_id"])) {
            $this->where("{$this->table}.m_villages_id", $payload["village_id"]);
        }

        if (isset($payload["nik"]) && !empty($payload["nik"])) {
            if (is_numeric($payload["nik"])) {
                $this->where("{$this->table}.nik", $payload["nik"]);
            } else {
                $this->like("{$this->table}.name", $payload["nik"]);
            }
        }

        if (isset($payload["rt"]) && !empty($payload["rt"])) {
            $this->where("{$this->table}.rt", $payload["rt"]);
        }

        if (isset($payload["rw"]) && !empty($payload["rw"])) {
            $this->where("{$this->table}.rw", $payload["rw"]);
        }

        if (isset($payload["tps"]) && !empty($payload["tps"])) {
            $this->where("{$this->table}.tps", $payload["tps"]);
        }

        if (isset($payload["married_status"]) && !empty($payload["married_status"])) {
            $this->where("{$this->table}.married_status", $payload["married_status"]);
        }

        if (isset($payload["is_coklit"]) && !empty($payload["is_coklit"])) {
            $this->where("{$this->table}.is_coklit", $payload["is_coklit"]);
        }

        if (isset($payload["is_new_voter"]) && !empty($payload["is_new_voter"])) {
            $this->where("{$this->table}.is_new_voter", $payload["is_new_voter"]);
        }

        if (isset($payload["is_novice_voter"]) && !empty($payload["is_novice_voter"])) {
            $this->where("{$this->table}.is_novice_voter", $payload["is_novice_voter"]);
        }

        if (isset($payload["tms"]) && !empty($payload["tms"])) {
            $this->where("{$this->table}.tms", $payload["tms"]);
        }

        if (isset($payload["disabilities"]) && !empty($payload["disabilities"])) {
            $this->where("{$this->table}.disabilities", $payload["disabilities"]);
        }

        if (isset($payload["is_profile_updated"]) && !empty($payload["is_profile_updated"])) {
            $this->where("{$this->table}.is_profile_updated", $payload["is_profile_updated"]);
        }

        if (isset($payload["is_checked"]) && !empty($payload["is_checked"])) {
            $this->where("{$this->table}.is_checked", $payload["is_checked"]);
        }

        return $this;
    }

    public function pagination($page = 1, $perPage = DEFAULT_PER_PAGE)
    {
        $limit = $perPage;
        $offset = $perPage * ($page - 1);

        $total = $this->countAllResults(false);
        $voters = $this->where("{$this->table}.is_deleted", 0)
                        ->limit($limit, $offset)
                        ->find();
        $voters = $this->convertEntity(VotersEntity::class, $voters);
        return [
            "data"  => $voters ?? [],
            "meta"  => [
                "total_item" => $total ?? 0,
                "page"  => $page,
                "per_page" => $perPage
            ]
        ];
    }

    public function result()
    {
        return $this->orderBy("village_name ASC, district_name ASC, rt ASC, rw ASC, {$this->table}.name ASC")->find();
    }

    public function getTotalUnchecked()
    {
        $this->selectCount('id')
            ->table($this->table);

        if ($this->districtId > 0) {
            $this->where('m_districts_id', $this->districtId);
        }

        $this->where('m_villages_id', $this->villageId)
            ->where('is_checked', 0)
            ->where('is_deleted', 0);

        $total = $this->get()->getRowArray();
        return (int) $total['id'] ?? 0;
    }

    public function setDistrictId($districtId)
    {
        $this->districtId = $districtId;
        return $this;
    }
}
