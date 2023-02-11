<?php

namespace Core\Voters\Controllers;

use App\Controllers\BaseController;
use Core\Regions\Models\VillagesModel;
use Core\Voters\Models\GenerateModel;
use Core\Voters\Models\VotersOriginalModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportController extends BaseController
{
    public function run()
    {
        try {
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load(APPPATH."Database/Xls/KASEMBON.xlsx");

            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow(); // e.g. 10

            $region = $this->getRegions();

            $arr = [];
            $districtId = 0;
            for ($i = 2; $i <= ($highestRow - 1); $i++) {
                $district = strtolower(str_replace(' ', '', $worksheet->getCell('B'. $i)->getValue()));
                $village = strtolower(str_replace(' ', '', $worksheet->getCell('C'. $i)->getValue()));
                $districtId = $region[$district]['district_id'] ?? '';

                $arr[] = [
                    'code' => $worksheet->getCell('A'. $i)->getValue(),
                    'm_districts_id' => $region[$district]['district_id'] ?? '',
                    'm_villages_id' => $region[$district][$village]['village_id'] ?? '',
                    'dp_id' => $worksheet->getCell('D'. $i)->getValue(),
                    'nkk' => $worksheet->getCell('E'. $i)->getValue(),
                    'nik' => $worksheet->getCell('F'. $i)->getValue(),
                    'name' => $worksheet->getCell('G'. $i)->getValue(),
                    'place_of_birth' => $worksheet->getCell('H'. $i)->getValue(),
                    'date_of_birth' => $this->dateOfBirth($worksheet->getCell('I'. $i)->getValue()),
                    'married_status' => $this->marriedStatus($worksheet->getCell('J'. $i)->getValue()),
                    'gender' => $this->gender($worksheet->getCell('K'. $i)->getValue()),
                    'address' => $worksheet->getCell('L'. $i)->getValue(),
                    'rt' => $worksheet->getCell('M'. $i)->getValue(),
                    'rw' => $worksheet->getCell('N'. $i)->getValue(),
                    'disabilities' => $worksheet->getCell('O'. $i)->getValue(),
                    'filters' => $worksheet->getCell('P'. $i)->getValue(),
                    'm_data_status_id' => 1,
                    'tps' => $worksheet->getCell('R'. $i)->getValue(),
                    'sort_data' => $worksheet->getCell('T'. $i)->getValue(),
                ];
            }

            $model = new VotersOriginalModel();
            if (empty($arr)) {
                return false;
            }
            $this->db->transStart();
            $model->insertBatch($arr);

            $generate = new GenerateModel();
            $generate->setActiveTable('voters_pra_dps')
                    ->setSourceTable('voters_original')
                    ->setDistrict($districtId)
                    ->setVillageId(1)
                    ->run();
            $this->db->transCommit();
        } catch (\Throwable $th) {
            $this->db->transRollback();
            echo $th->getMessage();
        }
    }

    private function gender($status)
    {
        $arr = ['L' => 1, 'P' => 2];
        return $arr[$status] ?? 1;
    }

    private function marriedStatus($status)
    {
        $arr = ['B' => 1, 'S' => 2];
        return $arr[$status] ?? 1;
    }

    private function dateOfBirth($date)
    {
        $explode = explode('|', $date);
        return $explode[2].'-'.$explode[1].'-'.$explode[0];
    }

    private function getRegions()
    {
        $model = new VillagesModel();
        $villages = $model->getAll();

        $region = [];
        foreach ($villages as $val) {
            $district = strtolower(str_replace(' ', '', $val['district_name']));
            $village = strtolower(str_replace(' ', '', $val['village_name']));

            $region[$district]['district_id'] = $val['district_id'];
            $region[$district][$village]['village_id'] = $val['village_id'];
        }

        return $region;
    }
}
