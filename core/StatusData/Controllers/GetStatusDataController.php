<?php

namespace Core\StatusData\Controllers;

use App\Controllers\BaseController;
use Core\StatusData\Models\GetStatusModel;
use Core\StatusData\Models\GetTotalModel;
use Core\StatusData\Models\GetVillageStatusModel;

class GetStatusDataController extends BaseController
{
    public function index()
    {
        try {
            $payload = $this->getPayload();
            $payload['page'] = $payload['page'] ?? 1;
            $payload['per_page'] = $payload['per_page'] ?? 10;

            $model = new GetVillageStatusModel();
            $status = $model->setVillageId($payload['village_id'] ?? '')
                            ->setDistrictId($payload['district_id'] ?? '')
                            ->getAll()
                            ->pagination();

            if (!empty($status['data'])) {
                $total = new GetTotalModel();
                foreach ($status['data'] as $val) {
                    $val->total_checked = $total->setActiveTable($val->active_table_source)
                                                ->getTotalChecked($val->village_id);

                    $val->total_unchecked = $total->setActiveTable($val->active_table_source)
                                                  ->getTotalUnchecked($val->village_id);
                }
            }

            return $this->paginationResponse($status['data'] ?? [], $status['meta'] ?? []);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }
}
