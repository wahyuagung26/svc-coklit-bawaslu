<?php

namespace Core\Voters\Controllers;

use Core\Voters\Models\GenerateModel;

class GenerateController extends BaseVotersController
{
    public function run($statusDataId, $villageId)
    {
        $statusData = $this->getStatusData($statusDataId);
        $village = $this->getVillage($villageId);

        try {
            $model = new GenerateModel();
            $model->setActiveTable($statusData->active_table_source)
                    ->setSourceTable($statusData->prev_table_source)
                    ->setVillageId($village->id)
                    ->run();

            return $this->successResponse(null, "data {$statusData->name} desa {$village->village_name} berhasil dibuat");
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }
}
