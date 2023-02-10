<?php

namespace Core\Recaps\Controllers;

use Core\Recaps\Models\GetRecapModel;
use Core\Voters\Controllers\BaseVotersController;

class GetRecapController extends BaseVotersController
{
    public function index($statusDataId)
    {
        try {
            $villageId = $this->payload['village_id'] ?? null;
            $districtId = $this->payload['district_id'] ?? null;

            $statusData = $this->getStatusData($statusDataId);

            $model = new GetRecapModel();
            $model->setStatusDataId($statusData->id)
                    ->setVillageId($villageId)
                    ->setDistrictId($districtId);

            $recap = $model->getAll();
            return $this->successResponse($recap);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }
}
