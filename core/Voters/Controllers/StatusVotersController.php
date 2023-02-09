<?php

namespace Core\Voters\Controllers;

use Core\Voters\Models\StatusVotersModel;

class StatusVotersController extends BaseVotersController
{
    private $rule = [
        'id' => [
            'label' => 'ID Pemilih',
            'rules' => 'required'
        ],
    ];

    public function edit($statusDataId)
    {
        try {
            $this->runPayloadValidation($this->rule, $this->payload);

            $statusData = $this->getStatusData($statusDataId);
            $tableName = $statusData->active_table_source;

            $voter = $this->update(StatusVotersModel::class, $tableName, $this->payload)->getById($this->payload['id']);
            return $this->successResponse($voter);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }
}
