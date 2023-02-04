<?php

namespace Core\Voters\Controllers;

use Core\StatusData\Entities\StatusDataEntity;
use Core\Voters\Models\GetVotersModel;
use Core\Voters\Models\VotersOriginalModel;

class GetVotersController extends BaseVotersController
{
    private $voters;
    private $originalVoters;

    public function __construct()
    {
        $this->voters = new GetVotersModel();
        $this->originalVoters = new VotersOriginalModel();
    }

    public function index($statusDataId)
    {
        $payload = $this->getPayload();
        $payload['page'] = $payload['page'] ?? 1;

        $statusData = $this->getStatusData($statusDataId);

        if ($statusData->id == STATUS_DATA_ORIGINAL) {
            $voters = $this->getOriginalData($payload);
        } else {
            $voters = $this->getDataByStatus($payload, $statusData);
        }

        return $this->paginationResponse($voters['data'] ?? [], $voters['meta'] ?? []);
    }

    private function getOriginalData(array $payload)
    {
        return $this->originalVoters->getAll()->setFilter($payload)->pagination($payload['page']);
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
