<?php

namespace Core\Regions\Models;

use App\Exceptions\ValidationException;
use Exception;
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
}
