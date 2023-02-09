<?php

namespace Core\Voters\Controllers;

use App\Exceptions\ValidationException;
use Core\Voters\Models\ProfileVotersModel;

class ProfileVotersController extends BaseVotersController
{
    private $payload;
    private $oldProfile;
    private $statusData;
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
            $this->statusData = $this->getStatusData($statusDataId);

            $profile = $this->getProfileFromPrevSource()->updateVoterProfile();

            $this->db->transCommit();

            return $this->successResponse($profile);
        } catch (ValidationException $th) {
            $this->db->transRollback();
            return $this->failedValidationResponse([], $th->getMessage(), $th->getCode());
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }

    private function getProfileFromPrevSource()
    {
        /**
         * @todo Menambah kolom baru prev_voters_id pada semua tabel voters sebagai relasi ke data sebelumnya
         */
        $voter = new ProfileVotersModel();
        $voterId = $this->payload['id'];
        $previousTableName = $this->statusData->prev_table_source;

        $this->oldProfile = $voter->setActiveTable($previousTableName)->getById($voterId);
        return $this;
    }

    private function updateVoterProfile()
    {
        $voter = new ProfileVotersModel();
        $voterOldProfile = $this->oldProfile->toArray();
        $activeTableName = $this->statusData->active_table_source;

        return $voter->setActiveTable($activeTableName)
                        ->setOldProfile($voterOldProfile)
                        ->setNewProfile($this->payload)
                        ->setStatusUpdatedProfile()
                        ->runUpdate()
                        ->getById($this->payload['id']);
    }
}
