<?php

namespace Core\Regions\Controllers;

use App\Controllers\BaseController;
use Core\Regions\Models\VillagesModel;

class BaseRegionsController extends BaseController
{
    protected function getVillageById($villageId)
    {
        $model = new VillagesModel();
        $village = $model->getById($villageId);

        if (!$village) {
            return $this->errorResponse('desa / kelurahan tidak ditemukan', HTTP_STATUS_NOT_FOUND);
        }

        return $village;
    }
}
