<?php

namespace Core\Voters\Models;

use App\Exceptions\ValidationException;

class MassUpdateModel extends BaseVotersModel
{
    private $payload;

    protected $allowedFields = [
        'is_coklit',
        'is_checked',
        'updated_at',
        'updated_by',
    ];

    public function coklit()
    {
        try {
            $query = "
                UPDATE {$this->table} SET is_coklit='{$this->payload['is_coklit']}'
                WHERE m_villages_id='{$this->payload['m_villages_id']}' and is_deleted=0
            ";

            $this->db->transStart();
            $this->db->query($query);
            $this->db->transCommit();
        } catch (\Throwable $th) {
            $this->db->transRollback();
            throw new ValidationException($th->getMessage(), 500);
        }
    }

    public function checklist()
    {
        try {
            $query = "
                UPDATE {$this->table} SET is_checked='{$this->payload['is_checked']}'
                WHERE m_villages_id='{$this->payload['m_villages_id']}' and is_deleted=0
            ";

            $this->db->transStart();
            $this->db->query($query);
            $this->db->transCommit();
        } catch (\Throwable $th) {
            $this->db->transRollback();
            throw new ValidationException($th->getMessage(), 500);
        }
    }

    public function setPayload($payload)
    {
        $this->payload = $payload;
        return $this;
    }
}
