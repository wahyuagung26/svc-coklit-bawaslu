<?php

namespace App\Models;

use App\Libraries\JwtAuth;
use CodeIgniter\Model;

class CoreModel extends Model
{
    protected $allowCallbacks = true;

    protected $beforeInsert = ['setDefaultInsertData'];
    protected $beforeUpdate = ['setDefaultUpdateData'];

    protected function user($key = null)
    {
        return JwtAuth::user($key);
    }

    protected function setDefaultInsertData(array $data)
    {
        $data['data']['created_at'] = date('Y-m-d H:i:s');
        $data['data']['created_by'] = JwtAuth::user('id') ?? 1;

        if (isset($data['data']['id'])) {
            unset($data['data']['id']);
        }

        return $data;
    }


    protected function setDefaultUpdateData(array $data)
    {
        $data['data']['updated_at'] = date('Y-m-d H:i:s');
        $data['data']['updated_by'] = JwtAuth::user('id') ?? 1;

        return $data;
    }
}
