<?php

namespace Core\Voters\Controllers;

use Core\Voters\Models\MassUpdateModel;

class MassUpdateController extends BaseVotersController
{
    private $validationRule = [
        'district_id' => [
            'label' => 'Kode Kecamatan',
            'rules' => 'required|max_length[8]'
        ],
        'village_id' => [
            'label' => 'Kode Desa / Kelurahan',
            'rules' => 'required|max_length[12]'
        ],
    ];

    public function coklit($statusDataId)
    {
        try {
            $this->runPayloadValidation($this->validationRule, $this->payload);
            $isCoklit = isset($this->payload['is_coklit']) && $this->payload['is_coklit'] ? '1' : '0';
            $this->payload['m_districts_id'] = $this->payload['district_id'];
            $this->payload['m_villages_id'] = $this->payload['village_id'];
            $this->payload['is_coklit'] = $isCoklit;

            $statusData = $this->getStatusData($statusDataId);
            $tableName = $statusData->active_table_source;

            $model = new MassUpdateModel();
            $model->setActiveTable($tableName)
                    ->setPayload($this->payload)
                    ->coklit();

            return $this->successResponse(null, 'Data coklit berhasil diupdate');
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }

    public function checklist($statusDataId)
    {
        try {
            $this->runPayloadValidation($this->validationRule, $this->payload);
            $isChecked = isset($this->payload['is_checked']) && $this->payload['is_checked'] ? '1' : '0';
            $this->payload['m_districts_id'] = $this->payload['district_id'];
            $this->payload['m_villages_id'] = $this->payload['village_id'];
            $this->payload['is_checked'] = $isChecked;

            $statusData = $this->getStatusData($statusDataId);
            $tableName = $statusData->active_table_source;

            $model = new MassUpdateModel();
            $model->setActiveTable($tableName)
                    ->setPayload($this->payload)
                    ->checklist();

            return $this->successResponse(null, 'Data check list berhasil diupdate');
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }
}
