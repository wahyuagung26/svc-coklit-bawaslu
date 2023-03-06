<?php

namespace Core\Voters\Controllers;

use avadim\FastExcelReader\Excel;
use App\Controllers\BaseController;
use Core\Voters\Models\GenerateModel;
use Core\Regions\Models\VillagesModel;
use Core\Voters\Models\VotersOriginalModel;
use Core\Voters\Models\VotersPraDpsModel;

class ImportController extends BaseController
{
    public function copy()
    {
        $generate = new GenerateModel();
        $generate->setActiveTable('voters_pra_dps')
                ->setSourceTable('voters_original')
                ->runInitialData();

        print "Berhasil copy voters_original ke voters_pra_dps at " .date("Y-m-d H:i:s"). PHP_EOL;
        ob_flush();
    }

    public function run()
    {
        try {
            $files = array_diff(scandir(APPPATH."Database/Xls/"), array('.', '..'));
            foreach ($files as $val) {
                if (!preg_match("/xlsx/i", $val)) {
                    continue;
                }

                $file = APPPATH."Database/Xls/".$val;

                if (file_exists($file)) {
                    $this->fastExcelReader($file);
                }
            }
        } catch (\Throwable $th) {
            $this->db->transRollback();
            echo $th->getMessage();
        }
    }

    private function fastExcelReader($fileName)
    {
        try {
            $newFileName = str_replace('Database/Xls/', 'Database/Xls/done/', $fileName);
            $region = $this->getRegions();

            $excel = Excel::open($fileName);

            $result = $excel->readRows();

            $this->db->transStart();
            foreach ($result as $key => $val) {
                if ($key == 1) {
                    continue;
                }

                $district = strtolower(str_replace(' ', '', $val['B']));
                $village = strtolower(str_replace(' ', '', $val['C']));

                $arr[] = [
                    'code' => $val['A'],
                    'm_districts_id' => $region[$district]['district_id'] ?? $val['B'],
                    'm_villages_id' => $region[$district][$village]['village_id'] ?? $val['C'],
                    'dp_id' => $val['D'],
                    'nkk' => $val['E'],
                    'nik' => $val['F'],
                    'name' => $val['G'],
                    'place_of_birth' => $val['H'],
                    'date_of_birth' => $this->dateOfBirth($val['I']),
                    'married_status' => $this->marriedStatus($val['J']),
                    'gender' => $this->gender($val['K']),
                    'address' => $val['L'],
                    'rt' => $val['M'],
                    'rw' => $val['N'],
                    'disabilities' => $val['O'],
                    'filters' => $val['P'],
                    'm_data_status_id' => 1,
                    'tps' => isset($val['T']) ? $val['R'] : $val['Q'],
                    'sort_data' => $val['T'] ?? '',
                ];

                if (count($arr) > 20000) {
                    if (!empty($arr)) {
                        // Insert data original
                        $model = new VotersOriginalModel();
                        $model->insertBatch($arr);
                        echo "Insert data original : ".count($val)." data ".$fileName." at " .date("H:i:s"). PHP_EOL;
                        
                        // Insert data pra Dps
                        $model = new VotersPraDpsModel();
                        $model->insertBatch($arr);
                        echo "Insert data original : ".count($val)." data ".$fileName." at " .date("H:i:s"). PHP_EOL;
                    }

                    $arr = [];
                }
            }

            $model = new VotersOriginalModel();
            if (!empty($arr)) {
                $model->insertBatch($arr);
            }

            $model = new VotersPraDpsModel();
            if (!empty($arr)) {
                $model->insertBatch($arr);
            }

            rename($fileName, $newFileName);

            $this->db->transCommit();
        } catch (\Throwable $th) {
            if (file_exists($newFileName)) {
                rename($newFileName, $fileName);
            }
            $this->db->transRollback();

            print($th);
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
        $year = ($explode[2] ?? '1970');
        $month = ($explode[1] ?? '01');
        $day = ($explode[0] ?? '01');
        $date = ($year == '0000' ? '1970' : $year).'-'. ($month == '00' ? '01' : $month).'-'.($day ==  '00' ? '01' : $day);
        return $date == '0000-00-00' ? '1970-01-01' : $date;
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
