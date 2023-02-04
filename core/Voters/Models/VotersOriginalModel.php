<?php

namespace Core\Voters\Models;

use App\Models\CoreModel;
use App\Traits\ConvertEntityTrait;
use Core\Voters\Entities\VotersResponseEntity;

class VotersOriginalModel extends CoreModel
{
    use ConvertEntityTrait;

    protected $table = 'voters_original';
    protected $returnType = 'array';

    protected $allowedFields = [
        'code',
        'm_districts_id',
        'm_villages_id',
        'dp_id',
        'nkk',
        'nik',
        'name',
        'place_of_birth',
        'date_of_birth',
        'married_status',
        'gender',
        'address',
        'rt',
        'rw',
        'disabilities',
        'filters',
        'm_data_status_id',
        'tps',
        'sort_data',
    ];

    public function getAll()
    {
        $this->select([
            'voters_original.id original_id',
            'voters_original.nik original_nik',
            'voters_original.nkk original_nkk',
            'voters_original.name original_name',
            'voters_original.place_of_birth original_place_of_birth',
            'voters_original.date_of_birth original_date_of_birth',
            'voters_original.gender original_gender',
            'voters_original.married_status original_married_status',
            'voters_original.address original_address',
            'voters_original.rt original_rt',
            'voters_original.rw original_rw',
            'voters_original.m_villages_id original_village_id',
            'original_village.village_name original_village_name',
            'voters_original.m_districts_id original_district_id',
            'original_district.district_name original_district_name',
            'voters_original.tps original_tps',
            'voters.id id',
            'voters.nik nik',
            'voters.nkk nkk',
            'voters.name name',
            'voters.place_of_birth place_of_birth',
            'voters.date_of_birth date_of_birth',
            'voters.gender gender',
            'voters.married_status married_status',
            'voters.address address',
            'voters.rt rt',
            'voters.rw rw',
            'voters.m_villages_id village_id',
            'voters_village.village_name',
            'voters.m_districts_id district_id',
            'voters_district.district_name',
            'voters.tps tps',
        ])->table('voters_original')
        ->join('m_districts original_district', 'voters_original.m_districts_id = original_district.id', 'left')
        ->join('m_villages original_village', 'voters_original.m_villages_id = original_village.id', 'left');
        return $this;
    }

    public function setFilter($payload)
    {
        if (isset($payload['name'])) {
            $this->like('original_village.village_name', $payload['name']);
        }

        return $this;
    }

    public function pagination($page = 1)
    {
        $limit = DEFAULT_PER_PAGE;
        $offset = DEFAULT_PER_PAGE * ($page - 1);

        $total = $this->countAllResults(false);
        $voters = $this->limit($limit, $offset)->find();

        $data = $this->sync($voters);
        $voters = $this->convertEntity(VotersResponseEntity::class, $data);
        return [
            'data'  => $voters ?? [],
            'meta'  => [
                'total' => $total ?? 0,
                'page'  => 1,
                'per_page' => DEFAULT_PER_PAGE
            ]
        ];
    }

    protected function sync(&$data)
    {
        foreach ($data as $key => $val) {
            $data[$key]['id'] = $val['original_id'];
            $data[$key]['nik'] = $val['original_nik'];
            $data[$key]['nkk'] = $val['original_nkk'];
            $data[$key]['name'] = $val['original_name'];
            $data[$key]['place_of_birth'] = $val['original_place_of_birth'];
            $data[$key]['date_of_birth'] = $val['original_date_of_birth'];
            $data[$key]['gender'] = $val['original_gender'];
            $data[$key]['married_status'] = $val['original_married_status'];
            $data[$key]['address'] = $val['original_address'];
            $data[$key]['rt'] = $val['original_rt'];
            $data[$key]['rw'] = $val['original_rw'];
            $data[$key]['village_id'] = $val['original_village_id'];
            $data[$key]['village_name'] = $val['original_village_name'];
            $data[$key]['district_id'] = $val['original_district_id'];
            $data[$key]['district_name'] = $val['original_district_name'];
            $data[$key]['tps'] = $val['original_tps'];

            unset($data['original_id']);
            unset($data['original_nik']);
            unset($data['original_nkk']);
            unset($data['original_name']);
            unset($data['original_place_of_birth']);
            unset($data['original_date_of_birth']);
            unset($data['original_gender']);
            unset($data['original_married_status']);
            unset($data['original_address']);
            unset($data['original_rt']);
            unset($data['original_rw']);
            unset($data['original_village_id']);
            unset($data['original_village_name']);
            unset($data['original_district_id']);
            unset($data['original_district_name']);
            unset($data['original_tps']);
        }

        return $data;
    }
}
