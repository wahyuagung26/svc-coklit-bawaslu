<?php

namespace Core\Regions\Controllers;

use Core\Regions\Models\DistrictsModel;
use Core\Regions\Models\VillagesModel;

class RegionsController extends BaseRegionsController
{
    public function getDistricts()
    {
        try {
            $model = new DistrictsModel();
            return $this->successResponse($model->getAll());
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), HTTP_STATUS_SERVER_ERROR);
        }
    }

    public function getVillages($districtsId)
    {
        try {
            $districtsId = $districtsId ?? 0;
            $model = new VillagesModel();
            return $this->successResponse($model->getByDistrictId($districtsId));
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), HTTP_STATUS_SERVER_ERROR);
        }
    }
}
