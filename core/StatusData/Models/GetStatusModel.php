<?php

namespace Core\StatusData\Models;

use App\Models\CoreModel;
use App\Traits\ConvertEntityTrait;
use Core\StatusData\Entities\StatusDataEntity;

class GetStatusModel extends CoreModel
{
    use ConvertEntityTrait;

    protected $table = 'm_data_status';
    protected $returnType = 'array';

    public function getById($id)
    {
        $status = $this->find($id);
        return $this->convertEntity(StatusDataEntity::class, $status);
    }
}
