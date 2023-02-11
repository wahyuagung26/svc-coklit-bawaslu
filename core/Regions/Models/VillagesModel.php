<?php

namespace Core\Regions\Models;

use App\Exceptions\ValidationException;
use App\Models\CoreModel;
use App\Traits\ConvertEntityTrait;
use Core\Regions\Entities\VillagesEntity;

class VillagesModel extends CoreModel
{
    use ConvertEntityTrait;

    protected $table = 'm_villages';
    protected $returnType = 'array';
    protected $allowedFields = ['last_m_data_status_id'];

    private $id = '';

    public function getById($id = null)
    {
        $id = $id ?? $this->id;
        return $this->convertEntity(VillagesEntity::class, $this->find($id));
    }

    public function getByDistrictId($districtId)
    {
        $villages = $this->where('m_districts_id', $districtId)
                        ->where('is_deleted', 0)
                        ->orderBy('village_name', 'ASC')
                        ->find();

        return $this->convertEntity(VillagesEntity::class, $villages);
    }

    public function setActiveStatusData($statusDataId)
    {
        if (empty($this->id)) {
            throw new ValidationException("Id is required", 500);
        }

        $this->update($this->id, ['last_m_data_status_id' => $statusDataId]);
        return $this;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getAll()
    {
        return $this->select([
                    'm_villages.id village_id',
                    'm_villages.village_name',
                    'm_districts.id district_id',
                    'm_districts.district_name'
                ])->table('m_villages')
                    ->join('m_districts', 'm_villages.m_districts_id = m_districts.id')
                    ->find();
    }
}
