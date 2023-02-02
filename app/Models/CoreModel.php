<?php

namespace App\Models;

use CodeIgniter\Model;

class CoreModel extends Model
{
    protected $allowCallbacks = true;

    protected $beforeInsert = ['setDefaultInsertData'];

    protected function setDefaultInsertData(array $data)
    {
        
        $data['data']['created_at'] = date('Y-m-d H:i:s');
        $data['data']['created_by'] = 1;
        $data['data']['is_deleted'] = 0;
        
        if (isset($data['data']['id'])) {
            unset($data['data']['id']);
        }

        return $data;
    }
}
