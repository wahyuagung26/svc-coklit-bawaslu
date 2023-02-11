<?php

namespace Core\Voters\Models;

class CoklitSummaryModel extends BaseVotersModel
{
    public const STATUS_COKLIT = 1;
    public const STATUS_UNCOKLIT = 0;

    public function getTotalCoklit()
    {
        return $this->getTotal(self::STATUS_COKLIT);
    }

    public function getTotalUnCoklit()
    {
        return $this->getTotal(self::STATUS_UNCOKLIT);
    }

    private function getTotal($statusCoklit)
    {
        $this->selectCount('id');

        if ($this->villageId > 0) {
            $this->where('m_villages_id', $this->villageId);
        }

        $this->where('is_coklit', $statusCoklit)
            ->where('is_deleted', 0);

        $total = $this->get()->getRowArray();
        return (int) $total['id'] ?? 0;
    }
}
