<?php

namespace Core\Voters\Controllers;

use Core\Voters\Entities\VotersEntity;
use Core\Voters\Models\ProfileVotersModel;

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

        $statusData = $this->getStatusData($statusDataId);

        /**
         * @todo Menambah kolom baru prev_voters_id pada semua tabel voters sebagai relasi ke data sebelumnya
         */
        $oldVoter = new ProfileVotersModel();
        $oldProfile = $oldVoter->setActiveTable($statusData->prev_table_source)
                                ->getById($this->payload['id']);

        $currentVoter = new ProfileVotersModel();
        $currentProfile = $currentVoter->setActiveTable($statusData->active_table_source)
                                        ->setStatusUpdatedProfile($oldProfile, new VotersEntity($this->payload))
                                        ->runUpdate($this->payload['id'], $this->payload)
                                        ->getById($this->payload['id']);

        return $this->successResponse($currentProfile);
    }
}
