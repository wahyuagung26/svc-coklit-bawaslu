<?php

namespace Core\StatusData\Models;

use App\Models\CoreModel;

class GetTotalModel extends CoreModel
{
    public const CHECKED = 1;
    public const UNCHECKED = 0;

    protected $table = '';

    public function setActiveTable(string $tableName)
    {
        $this->table = $tableName;
        return $this;
    }

    public function getTotalChecked(string $villageId): int
    {
        return $this->getTotal(self::CHECKED, $villageId);
    }

    public function getTotalUnchecked(string $villageId): int
    {
        return $this->getTotal(self::UNCHECKED, $villageId);
    }

    public function getTotal(string $statusChecked, int $villageId)
    {
        $this->selectCount('id')
            ->table($this->table)
            ->where('m_villages_id', $villageId)
            ->where('is_checked', $statusChecked)
            ->where('is_deleted', 0);

        $total = $this->get()->getRowArray();
        return (int) $total['id'] ?? 0;
    }
}
