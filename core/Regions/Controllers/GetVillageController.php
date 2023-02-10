<?php

namespace Core\Regions\Controllers;

use Core\Regions\Models\VillagesModel;

class GetVillageController extends RegionsController
{
    public function getVillageById($villageId)
    {
        $model = new VillagesModel();
        $village = $model->getById($villageId);

        if (!$village) {
            return $this->errorResponse('desa / kelurahan tidak ditemukan', HTTP_STATUS_NOT_FOUND);
        }

        return $this->successResponse($village);
    }
}
