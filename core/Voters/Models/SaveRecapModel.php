<?php

namespace Core\Voters\Models;

use App\Models\CoreModel;
use CodeIgniter\Database\RawSql;

class SaveRecapModel extends CoreModel
{
    protected $table = 'voters_summaries';
    protected $statusDataTable = '';
    protected $allowedFields = [
        'type',
        'm_villages_id',
        'tps',
        'voters_pra_dps_m',
        'voters_pra_dps_f',
        'voters_dps_m',
        'voters_dps_f',
        'voters_dpshp1_m',
        'voters_dpshp1_f',
        'voters_dpshp2_m',
        'voters_dpshp2_f',
        'voters_dpshp3_m',
        'voters_dpshp3_f',
        'voters_dpshp4_m',
        'voters_dpshp4_f',
    ];

    protected $triggerUpdatedField = [
        'm_villages_id' => null,
        'gender' => null,
        'tps' => STATUS_SUMMARY_TPS,
        'disabilities' => STATUS_SUMMARY_DISABILITIES,
        'is_ktp_el' => STATUS_SUMMARY_IS_KTP_EL,
        'is_new_voter' => STATUS_SUMMARY_IS_NEW_VOTER,
        'is_novice_voter' => STATUS_SUMMARY_IS_NOVICE_VOTER,
        'is_profile_updated' => STATUS_SUMMARY_IS_PROFILE_UPDATED,
        'tms' => [
            STATUS_TMS_UNKNOWN => STATUS_SUMMARY_UNKNOWN,
            STATUS_TMS_PASS_AWAY => STATUS_SUMMARY_PASS_AWAY,
            STATUS_TMS_DOUBLE => STATUS_SUMMARY_DOUBLE,
            STATUS_TMS_MINORS => STATUS_SUMMARY_MINORS,
            STATUS_TMS_TNI => STATUS_SUMMARY_TNI,
            STATUS_TMS_POLRI => STATUS_SUMMARY_POLRI
        ]
    ];

    public function setStatusDataTable(string $table)
    {
        $this->statusDataTable = $table;
        return $this;
    }

    public function updateRecap($payload)
    {
        $originalVoter = $this->getOriginalVoter($payload['id']);
        return $this->run($originalVoter, $payload);
    }

    private function run($originalVoter, $payload)
    {
        foreach ($payload as $columnName => $value) {
            $typeSummary = $this->triggerUpdatedField[$columnName] ?? STATUS_SUMMARY_TPS;

            if (!isset($this->triggerUpdatedField[$columnName])) {
                continue;
            }

            $this->increaseTotalSummary($payload, $typeSummary);

            if ($originalVoter[$columnName] != $value) {
                $this->decreaseTotalSummary($originalVoter, $typeSummary);
            }
        }

        return true;
    }

    private function increaseTotalSummary($voter, $typeSummary)
    {
        $maleColumn = "{$this->statusDataTable}_m";
        $femaleColumn = "{$this->statusDataTable}_f";
        $columnName = $voter['gender'] == GENDER_MALE ? $maleColumn : $femaleColumn;
        $payload = [
            "id" => "{$voter['m_villages_id']}-{$voter['tps']}-{$typeSummary}",
            "m_villages_id" => $voter["m_villages_id"],
            "tps" => $voter["tps"],
            "type" => $typeSummary,
            "$columnName" => new RawSql("IFNULL({$columnName}, 0)+1")
        ];

        return $this->upsert($payload);
    }

    private function decreaseTotalSummary($voter, $typeSummary)
    {
        $maleColumn = "{$this->statusDataTable}_m";
        $femaleColumn = "{$this->statusDataTable}_f";
        $columnName = $voter['gender'] == GENDER_MALE ? $maleColumn : $femaleColumn;
        $payload = [
            "id" => "{$voter['m_villages_id']}-{$voter['tps']}-{$typeSummary}",
            "m_villages_id" => $voter["m_villages_id"],
            "tps" => $voter["tps"],
            "type" => $typeSummary,
            "$columnName" => new RawSql("{$columnName}-1")
        ];

        return $this->upsert($payload);
    }

    private function getOriginalVoter($voterId)
    {
        $model = new ProfileVotersModel();
        $model->setActiveTable($this->statusDataTable);

        return $model->find($voterId);
    }
}
