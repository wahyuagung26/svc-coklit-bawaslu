<?php

namespace Core\Voters\Controllers;

use Core\Voters\Models\CreateVotersModel;

class CreateVotersController extends BaseVotersController
{
    private $createRule = [
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

    public function create($statusDataId)
    {
        $this->runPayloadValidation($this->createRule, $this->payload);
        $this->payload['m_districts_id'] = $this->payload['districts_id'];
        $this->payload['m_villages_id'] = $this->payload['villages_id'];
        $this->payload['is_new_data'] = 1;

        $statusData = $this->getStatusData($statusDataId);

        $model = new CreateVotersModel();
        $model->setActiveTable($statusData->active_table_source)->insert($this->payload);

        $voter = $model->getById($model->getInsertId());
        return $this->successResponse($voter);
    }
}
