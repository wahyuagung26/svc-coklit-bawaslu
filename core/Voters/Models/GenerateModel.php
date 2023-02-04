<?php

namespace Core\Voters\Models;

use Exception;

class GenerateModel extends BaseVotersModel
{
    protected $table = 'voters_pra_dps';
    protected $sourceTable = 'voters_original';

    protected $generatedColumn = '
        m_districts_id,
        m_villages_id,
        nkk,
        nik,
        name,
        place_of_birth,
        date_of_birth,
        married_status,
        gender,
        address,
        rt,
        rw,
        disabilities,
        m_data_status_id,
        tps
    ';

    public function setGeneratedColumn(array $column)
    {
        return $this->generatedColumn = join(',', $column);
        return $this;
    }

    public function run()
    {
        return $this->runValidationMandatory()
                    ->checkIsAlreadyGenerated()
                    ->startGenerate();
    }

    private function runValidationMandatory()
    {
        if (empty($this->table)) {
            throw new Exception("tabel data pemilih yang baru tidak ditemukan", HTTP_STATUS_UNPROCESS);
        }

        if (empty($this->sourceTable)) {
            throw new Exception("tabel sumber data tidak ditemukan", HTTP_STATUS_UNPROCESS);
        }

        if (empty($this->villageId)) {
            throw new Exception("desa / kelurahan tidak ditemukan", HTTP_STATUS_UNPROCESS);
        }

        return $this;
    }

    private function startGenerate()
    {
        $queryInsert = "INSERT INTO {$this->table} (
            {$this->generatedColumn}
        )";

        $querySourceData = "SELECT
            {$this->generatedColumn}
        from
            {$this->sourceTable};";

        $this->db->transStart();
        $this->db->query($queryInsert . ' '. $querySourceData);
        $this->db->transComplete();

        if (!$this->db->transStatus()) {
            $this->db->transRollback();
            throw new Exception("gagal generate data pemilih, ulangi beberapa saat lagi", HTTP_STATUS_SERVER_ERROR);
        }

        return $this->db->transCommit();
    }

    private function checkIsAlreadyGenerated()
    {
        $total =$this->db->table($this->table)
                    ->selectCount('id')
                    ->where('m_villages_id', $this->villageId)
                    ->get()
                    ->getRowArray();

        if ($total['id'] > 0) {
            throw new Exception("data telah tersedia", HTTP_STATUS_CONFLICT);
        }

        return $this;
    }
}
