<?php

namespace Core\Voters\Models;

use Exception;

class GenerateModel extends BaseVotersModel
{
    protected $generatedColumn = [
        'voters_original_id' => 'id as voters_original_id',
        'm_districts_id' => 'm_districts_id',
        'm_villages_id' => 'm_villages_id',
        'nkk' => 'nkk',
        'nik' => 'nik',
        'name' => 'name',
        'place_of_birth' => 'place_of_birth',
        'date_of_birth' => 'date_of_birth',
        'married_status' => 'married_status',
        'gender' => 'gender',
        'address' => 'address',
        'rt' => 'rt',
        'rw' => 'rw',
        'disabilities' => 'disabilities',
        'tps' => 'tps',
    ];

    public function setGeneratedColumn(array $column)
    {
        $this->generatedColumn = $column;
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
        $castColumn = $this->castGeneratedColumn($this->generatedColumn);

        $queryInsert = "INSERT INTO {$this->table} ({$castColumn['inserted']})";
        $querySourceData = "SELECT {$castColumn['selected']} from {$this->sourceTable};";

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

    private function castGeneratedColumn(array $generatedColumn)
    {
        if (isset($generatedColumn[0])) {
            $inserted = $selected = $generatedColumn;
        } else {
            foreach ($generatedColumn as $insertedColumn => $selectedColumn) {
                $inserted[] = $insertedColumn;
                $selected[] = $selectedColumn;
            }
        }

        return [
            'selected' => join(',', $selected),
            'inserted' => join(',', $inserted)
        ];
    }
}
