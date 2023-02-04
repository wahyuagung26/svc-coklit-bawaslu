<?php

namespace Core\Voters\Models;

class CoklitSummaryModel extends BaseVotersModel
{
    public function getTotalCoklit()
    {
        return $this->getTotal(1);
    }

    public function getTotalUnCoklit()
    {
        return $this->getTotal(0);
    }

    private function getTotal($statusCoklit)
    {
        $this->selectCount('id')->where('is_coklit', $statusCoklit);

        if ($this->villageId > 0) {
            $this->where('m_villages_id', $this->villageId);
        }

        $total = $this->get()->getRowArray();

        return (int) $total['id'] ?? 0;
    }
}
