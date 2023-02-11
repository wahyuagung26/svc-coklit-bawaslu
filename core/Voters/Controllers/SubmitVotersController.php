<?php

namespace Core\Voters\Controllers;

use Core\Voters\Models\GenerateModel;
use Core\Regions\Models\VillagesModel;
use Core\Recaps\Models\BaseRecapModel;
use App\Exceptions\ValidationException;
use Core\Recaps\Models\SaveRecapModel;
use Core\StatusData\Models\GetStatusModel;
use Core\Voters\Controllers\BaseVotersController;

class SubmitVotersController extends BaseVotersController
{
    private $statusData;
    private $newStatusData;
    private $village;

    public function execute($statusDataId, $villageId)
    {
        try {
            $this->village = $this->getVillageById($villageId);
            $this->statusData = $this->getStatusData($statusDataId);

            $this->db->transStart();
            $this->switchNewStatus();
            $this->generateNextData();
            $this->submitRecap();
            $this->db->transCommit();

            return $this->successResponse(null, 'Data rekapitulasi berhasil disimpan');
        } catch (ValidationException $th) {
            $this->db->transRollback();
            return $this->failedValidationResponse([], $th->getMessage(), $th->getCode());
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }

    private function submitRecap()
    {
        $model = new BaseRecapModel();
        $model->setActiveTable($this->statusData->active_table_source)->setVillageId($this->village->id);

        $total['voter'] = $model->getTotalPerTps();
        $total['new_voter'] = $model->getTotalNewVoter();
        $total['novice_voter'] = $model->getTotalNoviceVoter();
        $total['ktp_el'] = $model->getTotalKtpEl();
        $total['disabilities'] = $model->getTotalProfileUpdated();
        $total['profile_updated'] = $model->getTotalDisabilities();
        $total['tms'] = $model->getTotalTms();

        $recap = new SaveRecapModel();
        $recap->setStatusDataId($this->statusData->id)->setVillageId($this->village->id)->store($total);
    }

    public function generateNextData()
    {
        if ($this->statusData->id >= STATUS_DATA_DPSHP4) {
            return false;
        }

        if (($this->newStatusData->id) == STATUS_DATA_ORIGINAL) {
            $this->generateOriginalData();
        } else {
            $this->generateSecondaryData();
        }
    }

    private function generateOriginalData()
    {
        $activeTableName = $this->statusData->active_table_source;
        $sourceTableName = $this->statusData->prev_table_source;

        $model = new GenerateModel();
        $model->setActiveTable($activeTableName)
                ->setSourceTable($sourceTableName)
                ->setVillageId($this->village->id)
                ->run();
    }

    private function generateSecondaryData()
    {
        $activeTableName = $this->newStatusData->active_table_source;
        $sourceTableName = $this->newStatusData->prev_table_source;
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
            'is_deleted'
        ];

        $model = new GenerateModel();
        $model->setActiveTable($activeTableName)
                ->setSourceTable($sourceTableName)
                ->setGeneratedColumn($column)
                ->setVillageId($this->village->id)
                ->nonTMSOnly()
                ->run();
    }

    private function switchNewStatus()
    {
        $newStatusDataId = $this->statusData->id + 1;
        $this->getMasterStatus($newStatusDataId);

        $model = new VillagesModel();
        $model->setId($this->village->id)->setActiveStatusData($newStatusDataId);
    }

    private function getMasterStatus($statusDataId)
    {
        if ($this->statusData->id >= STATUS_DATA_FINAL) {
            return false;
        }

        $statusModel = new GetStatusModel();
        $status = $statusModel->getById($statusDataId);
        if (!$status) {
            return $this->errorResponse('status data pemilih tidak ditemukan', HTTP_STATUS_NOT_FOUND);
        }

        $this->newStatusData = $status;
        return $status;
    }
}
