<?php

namespace Core\Regions\Models;

use App\Models\CoreModel;
use App\Traits\ConvertEntityTrait;
use Core\Regions\Entities\DistrictsEntity;

class DistrictsModel extends CoreModel
{
    use ConvertEntityTrait;

    protected $table = 'm_districts';
    protected $returnType = 'array';
    protected $allowedFields = [];

    private $id = '';

    public function getById($id = null)
    {
        $id = $id ?? $this->id;
        return $this->convertEntity(DistrictsEntity::class, $this->find($id));
    }

    public function getAll()
    {
        $districts = $this->where('is_deleted', 0)->orderBy('district_name', 'ASC')->find();
        return $this->convertEntity(DistrictsEntity::class, $districts);
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
}
