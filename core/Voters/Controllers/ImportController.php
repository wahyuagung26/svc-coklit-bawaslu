<?php

namespace Core\Voters\Controllers;

use App\Controllers\BaseController;
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

            $arr = [];
            for ($i = 2; $i <= ($highestRow - 1); $i++) {
                $arr[] = [
                    'code' => $worksheet->getCell('A'. $i)->getValue(),
                    'm_districts_id' =>  $worksheet->getCell('B'. $i)->getValue(),
                    'm_villages_id' => $worksheet->getCell('C'. $i)->getValue(),
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
            if (!empty($arr)) {
                $model->insertBatch($arr);
            }
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    private function gender($status)
    {
        $arr = ['L' => 1, 'P' => 2];
        return isset($arr[$status]) ?? $arr[$status];
    }

    private function marriedStatus($status)
    {
        $arr = ['B' => 1, 'S' => 2];
        return isset($arr[$status]) ?? $arr[$status];
    }

    private function dateOfBirth($date)
    {
        $explode = explode('|', $date);
        return $explode[2].'-'.$explode[1].'-'.$explode[0];
    }
}
