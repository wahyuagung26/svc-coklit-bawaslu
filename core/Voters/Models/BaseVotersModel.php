<?php

namespace Core\Voters\Models;

use App\Models\CoreModel;

class BaseVotersModel extends CoreModel
{
    protected $table = 'voters_pra_dps';
    protected $sourceTable = 'voters_original';

    public function setActiveTable(string $tableName)
    {
        $this->table = $tableName;
        return $this;
    }

    public function setSourceTable(string $tableName)
    {
        $this->sourceTable = $tableName;
        return $this;
    }
}
