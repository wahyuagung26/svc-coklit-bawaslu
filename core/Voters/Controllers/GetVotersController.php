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
            $payload['per_page'] = $payload['per_page'] ?? 10;

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
            $model->setActiveTable($statusData->active_table_source)
                    ->setDistrictId($this->payload['district_id'] ?? 0)
                    ->setVillageId($village->id ?? 0);

            return $this->successResponse([
                'total_coklit' => $model->getTotalCoklit(),
                'total_uncoklit' => $model->getTotalUnCoklit()
            ]);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }

    public function getTotalUnChecked($statusDataId, $villageId)
    {
        try {
            $village = $this->getVillageById($villageId);
            $statusData = $this->getStatusData($statusDataId);

            $model = new GetVotersModel();
            $total = $model->setSourceTable($statusData->active_table_source)
                            ->setDistrictId($this->payload['district_id'] ?? 0)
                            ->setVillageId($village->id)
                            ->getTotalUnchecked();

            if ($statusData->id == 1) {
                $model = new CoklitSummaryModel();
                $uncoklit = $model->setActiveTable($statusData->active_table_source)
                            ->setDistrictId($this->payload['district_id'] ?? 0)
                            ->setVillageId($village->id ?? 0)
                            ->getTotalUnCoklit();
            }

            return $this->successResponse([
                'total_unchecked' => $total,
                'total_uncoklit' => $uncoklit ?? 0,
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
            $payload['village_id'] = $this->user('village_id');
        }

        if ($this->user('role') == 'admin kecamatan') {
            $payload['district_id'] = $this->user('district_id');
        }

        return $model->setActiveTable($tableName)
                        ->getAll()
                        ->setFilter($payload)
                        ->pagination($payload['page'], $payload['per_page'] ?? DEFAULT_PER_PAGE);
    }
}
