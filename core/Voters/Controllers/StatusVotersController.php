<?php

namespace Core\Voters\Controllers;

use Core\Voters\Models\StatusVotersModel;

class StatusVotersController extends BaseVotersController
{
    private $statusVoterRule = [
        'id' => [
            'label' => 'ID Pemilih',
            'rules' => 'required'
        ],
    ];

    public function edit($statusDataId)
    {
        $this->runPayloadValidation($this->statusVoterRule, $this->payload);

        $statusData = $this->getStatusData($statusDataId);
        $voter = $this->update(StatusVotersModel::class, $statusData->active_table_source, $this->payload)
                        ->getById($this->payload['id']);

        return $this->successResponse($voter);
    }
}
