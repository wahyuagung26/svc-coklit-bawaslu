<?php

namespace Core\StatusData\Models;

use App\Models\CoreModel;
use App\Traits\ConvertEntityTrait;
use Core\StatusData\Entities\ListStatusEntity;

class GetVillageStatusModel extends CoreModel
{
    use ConvertEntityTrait;

    protected $table = 'm_villages';
    protected $returnType = 'array';

    protected $villageId = '';
    protected $districtId = '';

    public function getAll(): GetVillageStatusModel
    {
        $this->select([
                    "m_data_status.name",
                    "m_data_status.active_table_source",
                    "{$this->table}.last_m_data_status_id",
                    "{$this->table}.id as village_id",
                    "{$this->table}.village_name",
                    "m_districts.id as district_id",
                    "m_districts.district_name",
                ])->table($this->table)
                ->join("m_districts", "m_villages.m_districts_id = m_districts.id")
                ->join("m_data_status", "m_data_status.id = m_villages.last_m_data_status_id");

        $this->setFilter();
        return $this;
    }

    public function pagination($page = 1, $perPage = DEFAULT_PER_PAGE): array
    {
        $limit = $perPage;
        $offset = $perPage * ($page - 1);

        $total = $this->countAllResults(false);
        $voters = $this->limit($limit, $offset)->find();
        $voters = $this->convertEntity(ListStatusEntity::class, $voters);
        return [
            "data"  => $voters ?? [],
            "meta"  => [
                "total_item" => $total ?? 0,
                "page"  => $page,
                "per_page" => $perPage
            ]
        ];
    }

    public function setVillageId(string $villageId): GetVillageStatusModel
    {
        $this->villageId = $villageId;
        return $this;
    }

    public function setDistrictId(string $districtId): GetVillageStatusModel
    {
        $this->districtId = $districtId;
        return $this;
    }

    private function setFilter(): GetVillageStatusModel
    {
        if (!empty($this->villageId)) {
            $this->like('m_villages.id', $this->villageId);
        }

        if (!empty($this->districtId)) {
            $this->like('m_villages.m_districts_id', $this->districtId);
        }

        return $this;
    }
}
