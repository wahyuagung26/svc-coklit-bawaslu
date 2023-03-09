<?php

namespace Core\StatusData\Models;

use App\Models\CoreModel;
use App\Traits\ConvertEntityTrait;
use Core\StatusData\Entities\ListStatusEntity;
use Core\StatusData\Entities\StatusDataEntity;

class GetStatusModel extends CoreModel
{
    use ConvertEntityTrait;

    protected $table = 'm_data_status';
    protected $returnType = 'array';

    protected $villageName = '';
    protected $districtName = '';

    public function getById(int $id): object
    {
        $status = $this->find($id);
        return $this->convertEntity(StatusDataEntity::class, $status);
    }

    public function getAll(): GetStatusModel
    {
        $this->select([
                    "{$this->table}.id",
                    "name",
                    "active_table_source",
                    "m_villages.id as village_id",
                    "m_villages.village_name",
                    "m_districts.id as district_id",
                    "m_districts.district_name",
                ])->table($this->table)
                ->join("m_villages", "{$this->table}.id = m_villages.last_m_data_status_id")
                ->join("m_districts", "m_villages.m_districts_id = m_districts.id");

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

    public function setVillageName(string $villageName): GetStatusModel
    {
        $this->villageName = $villageName;
        return $this;
    }

    public function setDistrictName(string $districtName): GetStatusModel
    {
        $this->districtName = $districtName;
        return $this;
    }

    private function setFilter(): GetStatusModel
    {
        if (!empty($this->villageName)) {
            $this->like('village_name', $this->villageName);
        }

        if (!empty($this->districtName)) {
            $this->like('district_name', $this->districtName);
        }

        return $this;
    }
}
