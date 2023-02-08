<?php

namespace Core\Voters\Controllers;

use Core\StatusData\Entities\StatusDataEntity;
use Core\Voters\Models\CoklitSummaryModel;
use Core\Voters\Models\GetVotersModel;

class GetVotersController extends BaseVotersController
{
    public function index($statusDataId)
    {
        $payload = $this->getPayload();
        $payload['page'] = $payload['page'] ?? 1;

        $statusData = $this->getStatusData($statusDataId);
        $voters = $this->getDataByStatus($payload, $statusData);

        return $this->paginationResponse($voters['data'] ?? [], $voters['meta'] ?? []);
    }

    public function coklitSummary($statusDataId, $villageId)
    {
        if ($villageId > 0) {
            $village = $this->getVillage($villageId);
        }

        $statusData = $this->getStatusData($statusDataId);

        $model = new CoklitSummaryModel();
        $model->setActiveTable($statusData->active_table_source)->setVillageId($village->id ?? 0);

        return $this->successResponse([
            'total_coklit' => $model->getTotalCoklit(),
            'total_uncoklit' => $model->getTotalUnCoklit()
        ]);
    }

    private function getDataByStatus(array $payload, StatusDataEntity $statusData)
    {
        $model = new GetVotersModel();
        return $model->setActiveTable($statusData->active_table_source)
                        ->getAll()
                        ->setFilter($payload)
                        ->pagination($payload['page']);
    }
}
