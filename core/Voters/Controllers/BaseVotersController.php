<?php

namespace Core\Voters\Controllers;

use App\Controllers\BaseController;
use Core\Regions\Models\VillagesModel;
use Core\StatusData\Models\GetStatusModel;

class BaseVotersController extends BaseController
{
    protected function getStatusData($statusDataId)
    {
        $statusModel = new GetStatusModel();
        $status = $statusModel->getById($statusDataId);
        if (!$status) {
            return $this->errorResponse('status data pemilih tidak ditemukan', HTTP_STATUS_NOT_FOUND);
        }

        return $status;
    }

    protected function getVillage($villageId)
    {
        $villageModel = new VillagesModel();
        $village = $villageModel->getById($villageId);

        if (!$village) {
            return $this->errorResponse('desa / kelurahan tidak ditemukan', HTTP_STATUS_NOT_FOUND);
        }

        return $village;
    }
}
