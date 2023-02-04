<?php

namespace Core\Voters\Models;

use App\Models\CoreModel;
use Core\Voters\Entities\VotersEntity;

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

    public function setStatusDataTable(string $table)
    {
        $this->statusDataTable = $table;
        return $this;
    }
}
