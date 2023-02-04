<?php

namespace Core\Regions\Models;

use App\Models\CoreModel;
use App\Traits\ConvertEntityTrait;
use Core\Regions\Entities\VillagesEntity;

class VillagesModel extends CoreModel
{
    use ConvertEntityTrait;

    protected $table = 'm_villages';
    protected $returnType = 'array';

    public function getById($id)
    {
        $village = $this->find($id);
        return $this->convertEntity(VillagesEntity::class, $village);
    }

    public function getByDistrictId($districtId)
    {
        $villages = $this->where('m_districts_id', $districtId);
        return $this->convertEntity(VillagesEntity::class, $villages);
    }
}
