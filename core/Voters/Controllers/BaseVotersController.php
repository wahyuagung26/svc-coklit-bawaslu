<?php

namespace Core\Voters\Controllers;

use App\Controllers\BaseController;
use Core\Regions\Entities\VillagesEntity;
use Core\Regions\Models\VillagesModel;
use Core\StatusData\Entities\StatusDataEntity;
use Core\StatusData\Models\GetStatusModel;

class BaseVotersController extends BaseController
{
    protected function getStatusData($statusDataId): StatusDataEntity
    {
        $statusModel = new GetStatusModel();
        $status = $statusModel->getById($statusDataId);
        if (empty($status)) {
            return $this->successResponse(null, 'status data pemilih tidak ditemukan', HTTP_STATUS_NOT_FOUND);
        }

        return new StatusDataEntity($status);
    }

    protected function getVillage($villageId): VillagesEntity
    {
        $villageModel = new VillagesModel();
        $village = $villageModel->getById($villageId);
        if (empty($village)) {
            return $this->successResponse(null, 'desa / kelurahan tidak ditemukan', HTTP_STATUS_NOT_FOUND);
        }

        return new VillagesEntity($village);
    }
}
