<?php

namespace Core\Voters\Controllers;

use Core\Regions\Entities\VillagesEntity;
use Core\StatusData\Entities\StatusDataEntity;
use Core\Voters\Models\GenerateModel;

class GenerateController extends BaseVotersController
{
    public function run($statusDataId, $villageId)
    {
        $statusData = $this->getStatusData($statusDataId);
        $village = $this->getVillage($villageId);

        try {
            if ($statusData->id == STATUS_DATA_ORIGINAL) {
                $this->generateOriginalData($statusData, $village);
            } else {
                $this->generateSecondaryData($statusData, $village);
            }

            return $this->successResponse(null, "data {$statusData->name} desa {$village->village_name} berhasil dibuat");
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode() > 0 ? $th->getCode() : HTTP_STATUS_SERVER_ERROR);
        }
    }

    private function generateOriginalData(StatusDataEntity $statusData, VillagesEntity $village)
    {
        $model = new GenerateModel();
        $model->setActiveTable($statusData->active_table_source)
                ->setSourceTable($statusData->prev_table_source)
                ->setVillageId($village->id)
                ->run();
    }

    private function generateSecondaryData(StatusDataEntity $statusData, VillagesEntity $village)
    {
        $generatedColumn = [
            'voters_original_id',
            'm_districts_id',
            'm_villages_id',
            'nkk',
            'nik',
            'name',
            'place_of_birth',
            'date_of_birth',
            'married_status',
            'gender',
            'address',
            'rt',
            'rw',
            'disabilities',
            'tps',
            'tms',
            'is_coklit',
            'is_ktp_el',
            'is_new_voter',
            'is_novice_voter',
            'is_profile_updated',
            'is_new_data',
            'is_checked',
            'is_deleted'
        ];

        $model = new GenerateModel();
        $model->setActiveTable($statusData->active_table_source)
                ->setSourceTable($statusData->prev_table_source)
                ->setGeneratedColumn($generatedColumn)
                ->setVillageId($village->id)
                ->run();
    }
}
