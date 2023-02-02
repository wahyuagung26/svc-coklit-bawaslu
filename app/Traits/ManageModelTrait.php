<?php

namespace App\Traits;

trait ManageModelTrait
{
    protected $beforeInsert = ['setDefaultInsert'];

    protected function setDefaultInsert(array &$data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = 'admin';

        if (isset($data['id'])) {
            unset($data['id']);
        }

        return $data;
    }
}
