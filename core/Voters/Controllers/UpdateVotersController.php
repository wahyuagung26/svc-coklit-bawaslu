<?php

namespace Core\Voters\Controllers;

use Core\Voters\Models\StatusVotersModel;
use Core\Voters\Models\ProfileVotersModel;

class UpdateVotersController extends BaseVotersController
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

    private $statusVoterRule = [
        'id' => [
            'label' => 'ID Pemilih',
            'rules' => 'required'
        ],
    ];

    public function profile($statusDataId)
    {
        $this->runPayloadValidation($this->profileVoterRule, $this->payload);
        $this->payload['m_districts_id'] = $this->payload['districts_id'];
        $this->payload['m_villages_id'] = $this->payload['villages_id'];

        $statusData = $this->getStatusData($statusDataId);

        $model = new ProfileVotersModel();
        $model->setActiveTable($statusData->active_table_source)->update($this->payload['id'], $this->payload);

        $voter = $model->getById($this->payload['id']);
        return $this->successResponse($voter);
    }

    public function statusVoter($statusDataId)
    {
        $this->runPayloadValidation($this->statusVoterRule, $this->payload);

        $statusData = $this->getStatusData($statusDataId);

        $model = new StatusVotersModel();
        $model->setActiveTable($statusData->active_table_source)->update($this->payload['id'], $this->payload);
        
        $voter = $model->getById($this->payload['id']);
        return $this->successResponse($voter);
    }
}
