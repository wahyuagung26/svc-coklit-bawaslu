<?php

namespace Core\Voters\Controllers;

use Core\Voters\Models\GenerateModel;
use App\Exceptions\ValidationException;
use Core\Regions\Entities\VillagesEntity;
use Core\StatusData\Entities\StatusDataEntity;

class GenerateController extends BaseVotersController
{
    public function generate($statusDataId, $villageId)
    {
        $statusData = $this->getStatusData($statusDataId);
        $village = $this->getVillageById($villageId);

        try {
            if ($statusData->id == STATUS_DATA_ORIGINAL) {
                $this->generateOriginalData($statusData, $village);
            } else {
                $this->generateSecondaryData($statusData, $village);
            }

            $message = "data {$statusData->name} desa {$village->village_name} berhasil dibuat";
            return $this->successResponse(null, $message);
        } catch (ValidationException $th) {
            return $this->failedValidationResponse([], $th->getMessage(), $th->getCode());
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }

    private function generateOriginalData(StatusDataEntity $statusData, VillagesEntity $village)
    {
        $activeTableName = $statusData->active_table_source;
        $sourceTableName = $statusData->prev_table_source;

        $model = new GenerateModel();
        $model->setActiveTable($activeTableName)
                ->setSourceTable($sourceTableName)
                ->setDistrict($village->district_id)
                ->setVillageId($village->id)
                ->run();
    }

    private function generateSecondaryData(StatusDataEntity $statusData, VillagesEntity $village)
    {
        $activeTableName = $statusData->active_table_source;
        $sourceTableName = $statusData->prev_table_source;
        $column = [
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
        $model->setActiveTable($activeTableName)
                ->setSourceTable($sourceTableName)
                ->setGeneratedColumn($column)
                ->setDistrict($village->district_id)
                ->setVillageId($village->id)
                ->run();
    }
}
