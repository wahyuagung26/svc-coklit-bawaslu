<?php

namespace Core\Voters\Controllers;

use Core\StatusData\Entities\StatusDataEntity;
use Core\Voters\Models\CoklitSummaryModel;
use Core\Voters\Models\GetVotersModel;

class GetVotersController extends BaseVotersController
{
    public function index($statusDataId)
    {
        try {
            $payload = $this->getPayload();
            $payload['page'] = $payload['page'] ?? 1;

            $statusData = $this->getStatusData($statusDataId);
            $voters = $this->getByStatusData($payload, $statusData);

            return $this->paginationResponse($voters['data'] ?? [], $voters['meta'] ?? []);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }

    public function coklitSummary($statusDataId, $villageId)
    {
        try {
            if ($villageId > 0) {
                $village = $this->getVillageById($villageId);
            }

            $statusData = $this->getStatusData($statusDataId);

            $model = new CoklitSummaryModel();
            $model->setActiveTable($statusData->active_table_source)->setVillageId($village->id ?? 0);

            return $this->successResponse([
                'total_coklit' => $model->getTotalCoklit(),
                'total_uncoklit' => $model->getTotalUnCoklit()
            ]);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }

    private function getByStatusData(array $payload, StatusDataEntity $statusData)
    {
        $model = new GetVotersModel();
        $tableName = $statusData->active_table_source;

        if ($this->user('role') == 'admin desa') {
            $payload['villages_id'] = $this->user('village_id');
        }

        if ($this->user('role') == 'admin kecamatan') {
            $payload['districts_id'] = $this->user('district_id');
        }

        return $model->setActiveTable($tableName)
                        ->getAll()
                        ->setFilter($payload)
                        ->pagination($payload['page']);
    }
}
