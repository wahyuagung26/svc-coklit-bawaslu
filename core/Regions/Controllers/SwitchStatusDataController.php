<?php

namespace Core\Regions\Controllers;

use App\Controllers\BaseController;
use Core\Regions\Models\VillagesModel;
use Core\StatusData\Models\GetStatusModel;

class SwitchStatusDataController extends BaseRegionsController
{
    public function update($villageId, $statusDataId)
    {
        try {
            $statusData = $this->getStatusData($statusDataId);
            $village = $this->getVillage($villageId);

            $newStatusDataId = $statusData->id + 1;
            $this->getStatusData($newStatusDataId);

            $model = new VillagesModel();
            $village = $model->setId($village->id)
                            ->setActiveStatusData($newStatusDataId)
                            ->getById();

            return $this->successResponse($village);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), HTTP_STATUS_SERVER_ERROR);
        }
    }

    private function getStatusData($statusDataId)
    {
        $statusModel = new GetStatusModel();
        $status = $statusModel->getById($statusDataId);
        if (!$status) {
            return $this->errorResponse('status data pemilih tidak ditemukan', HTTP_STATUS_NOT_FOUND);
        }

        return $status;
    }
}
