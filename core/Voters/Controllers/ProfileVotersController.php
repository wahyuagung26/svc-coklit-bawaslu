<?php

namespace Core\Voters\Controllers;

use Core\Voters\Entities\VotersEntity;
use Core\Voters\Models\ProfileVotersModel;
use Core\Voters\Models\SaveRecapModel;

class ProfileVotersController extends BaseVotersController
{
    private $profileVoterRule = [
        'id' => [
            'label' => 'ID Pemilih',
            'rules' => 'required'
        ],
    ];

    public function edit($statusDataId)
    {
        $this->runPayloadValidation($this->profileVoterRule, $this->payload);

        if (isset($this->payload['district_id'])) {
            $this->payload['m_districts_id'] = $this->payload['district_id'];
        }

        if (isset($this->payload['village_id'])) {
            $this->payload['m_villages_id'] = $this->payload['village_id'];
        }

        try {
            $this->db->transStart();
            $statusData = $this->getStatusData($statusDataId);

            $this->runRecapCounter($statusData);

            $oldProfile = $this->getProfileFromPrevSource($statusData);
            $currentProfile = $this->updateVoterProfile($statusData, $oldProfile);

            $this->db->transComplete();

            return $this->successResponse($currentProfile);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), HTTP_STATUS_SERVER_ERROR);
        }
    }

    private function runRecapCounter($statusData)
    {
        $summaries = new SaveRecapModel();
        return $summaries->setStatusDataTable($statusData->active_table_source)->updateRecap($this->payload);
    }

    private function getProfileFromPrevSource($statusData)
    {
        /**
         * @todo Menambah kolom baru prev_voters_id pada semua tabel voters sebagai relasi ke data sebelumnya
         */
        $oldVoter = new ProfileVotersModel();
        return $oldVoter->setActiveTable($statusData->prev_table_source)->getById($this->payload['id']);
    }

    private function updateVoterProfile($statusData, $oldProfile)
    {
        $currentVoter = new ProfileVotersModel();
        return $currentVoter->setActiveTable($statusData->active_table_source)
                            ->setStatusUpdatedProfile($oldProfile, $this->payload)
                            ->runUpdate($this->payload['id'], $this->payload)
                            ->getById($this->payload['id']);
    }
}
