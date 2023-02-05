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
        'districts_id' => [
            'label' => 'Kode Kecamatan',
            'rules' => 'required|max_length[8]'
        ],
        'villages_id' => [
            'label' => 'Kode Desa / Kelurahan',
            'rules' => 'required|max_length[12]'
        ],
        'nkk' => [
            'label' => 'NKK',
            'rules' => 'required|numeric|max_length[18]'
        ],
        'nik' => [
            'label' => 'NIK',
            'rules' => 'required|numeric|max_length[18]'
        ],
        'name' => [
            'label' => 'Nama',
            'rules' => 'required|max_length[50]'
        ],
        'place_of_birth' => [
            'label' => 'Tempat Lahir',
            'rules' => 'required|max_length[30]'
        ],
        'date_of_birth' => [
            'label' => 'Tanggal Lahir',
            'rules' => 'required'
        ],
        'married_status' => [
            'label' => 'Status Perkawinan',
            'rules' => 'required|max_length[1]'
        ],
        'gender' => [
            'label' => 'Jenis Kelamin',
            'rules' => 'required|max_length[1]'
        ],
        'address' => [
            'label' => 'Alamat',
            'rules' => 'required|max_length[100]'
        ],
        'rt' => [
            'label' => 'RT',
            'rules' => 'required|max_length[3]'
        ],
        'rw' => [
            'label' => 'RW',
            'rules' => 'required|max_length[3]'
        ],
        'tps' => [
            'label' => 'TPS',
            'rules' => 'required|max_length[2]'
        ]
    ];

    public function edit($statusDataId)
    {
        $this->runPayloadValidation($this->profileVoterRule, $this->payload);
        $this->payload['m_districts_id'] = $this->payload['districts_id'];
        $this->payload['m_villages_id'] = $this->payload['villages_id'];

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
                            ->setStatusUpdatedProfile($oldProfile, new VotersEntity($this->payload))
                            ->runUpdate($this->payload['id'], $this->payload)
                            ->getById($this->payload['id']);
    }
}
