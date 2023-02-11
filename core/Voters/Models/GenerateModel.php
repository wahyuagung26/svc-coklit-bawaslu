<?php

namespace Core\Voters\Models;

use App\Exceptions\ValidationException;

class GenerateModel extends BaseVotersModel
{
    private $customWhere;

    protected $table = 'voters_pra_dps';

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

    public function nonTMSOnly()
    {
        $this->customWhere = " AND ({$this->sourceTable}.tms = 0 or {$this->sourceTable}.tms IS NULL)";
        return $this;
    }

    public function setDistrict($districtId) {
        $this->customWhere = " {$this->sourceTable}.m_districts_id = {$districtId} ";
        return $this;
    }

    private function runValidationMandatory()
    {
        if (empty($this->table)) {
            throw new ValidationException("tabel data pemilih yang baru tidak ditemukan", HTTP_STATUS_UNPROCESS);
        }

        if (empty($this->sourceTable)) {
            throw new ValidationException("tabel sumber data tidak ditemukan", HTTP_STATUS_UNPROCESS);
        }

        if (empty($this->villageId)) {
            throw new ValidationException("desa / kelurahan tidak ditemukan", HTTP_STATUS_UNPROCESS);
        }

        return $this;
    }

    private function startGenerate()
    {
        $castColumn = $this->castGeneratedColumn($this->generatedColumn);

        $queryInsert = "INSERT INTO {$this->table} ({$castColumn['inserted']})";
        if ($this->sourceTable == 'voters_original') {
            $querySourceData = "SELECT {$castColumn['selected']} FROM {$this->sourceTable} WHERE {$this->customWhere}";
        } else {
            $querySourceData = "SELECT {$castColumn['selected']} FROM {$this->sourceTable} WHERE {$this->sourceTable}.m_villages_id = {$this->villageId} AND {$this->sourceTable}.is_deleted != 1 {$this->customWhere}";
        }

        $this->db->transStart();
        $this->db->query($queryInsert . ' '. $querySourceData);
        $this->db->transComplete();

        if (!$this->db->transStatus()) {
            $this->db->transRollback();
            throw new ValidationException(
                "gagal generate data pemilih, ulangi beberapa saat lagi",
                HTTP_STATUS_SERVER_ERROR
            );
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
            throw new ValidationException("data telah tersedia", HTTP_STATUS_CONFLICT);
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
