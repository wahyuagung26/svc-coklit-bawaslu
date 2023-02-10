<?php

namespace Core\Recaps\Models;

class SaveRecapModel extends BaseRecapModel
{
    private $statusDataId;

    protected $table = 'voters_summaries';
    protected $allowedFields = [
        'm_data_status_id',
        'm_villages_id',
        'tps',
        'voter_m',
        'voter_f',
        'new_voter_m',
        'new_voter_f',
        'novice_voter_m',
        'novice_voter_f',
        'ktp_el_m',
        'ktp_el_f',
        'disabilities_m',
        'disabilities_f',
        'profile_updated_m',
        'profile_updated_f',
        'unknown_m',
        'unknown_f',
        'pass_away_m',
        'pass_away_f',
        'double_m',
        'double_f',
        'minor_m',
        'minor_f',
        'tni_m',
        'tni_f',
        'polri_m',
        'polri_f',
        'updated_at',
        'updated_by',
    ];

    public function setStatusDataId($id)
    {
        $this->statusDataId = $id;
        return $this;
    }

    public function store(array $total)
    {
        foreach ($total as $field => $val) {
            $this->storeRecap($field, $val ?? []);
        }

        return $this;
    }

    private function mappingTms($value)
    {
        $tms = [
            STATUS_TMS_UNKNOWN => FIELD_UNKNOWN,
            STATUS_TMS_PASS_AWAY => FIELD_PASS_AWAY,
            STATUS_TMS_DOUBLE => FIELD_DOUBLE,
            STATUS_TMS_MINORS => FIELD_MINORS,
            STATUS_TMS_TNI => FIELD_TNI,
            STATUS_TMS_POLRI => FIELD_POLRI
        ];

        return $tms[$value] ?? null;
    }

    private function storeRecap(string $field, array $data)
    {
        $payload = [];
        $villageId = $this->villageId ?? 0;
        $statusDataId = $this->statusDataId ?? 0;

        foreach ($data as $val) {
            $gender = $val['gender'] == GENDER_MALE ? 'm' : 'f';

            $fieldName = $field == 'tms' ? $this->mappingTms($val['tms']) : $field;
            if (empty($fieldName)) {
                continue;
            }

            $payload["id"] = "{$villageId}-{$statusDataId}-{$val["tps"]}";
            $payload["m_data_status_id"] = $statusDataId ?? 0;
            $payload["m_villages_id"] = $villageId ?? 0;
            $payload["tps"] = $val["tps"];
            $payload["updated_at"] = date('Y-m-d H:i:s');
            $payload["updated_by"] = $this->user('id');
            $payload["{$fieldName}_{$gender}"] = $val["total"] ?? 0;

            if (!empty($payload)) {
                $this->upsert($payload);
            }
        }
    }
}
