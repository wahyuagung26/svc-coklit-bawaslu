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
    public const SOURCE_EXCEL = APPPATH."Database/Xls/";
    public const TEMP_FILE_NAME = "temp.xlsx";

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
            $files = array_diff(scandir(self::SOURCE_EXCEL), array('.', '..'));
            foreach ($files as $val) {
                if (!preg_match("/xlsx/i", $val)) {
                    continue;
                }

                $file = self::SOURCE_EXCEL.$val;

                if (file_exists($file)) {
                    $this->fastImportExcel($file);
                }
            }
        } catch (\Throwable $th) {
            $this->db->transRollback();
            echo $th->getMessage();
        }
    }

    private function fastImportExcel($fileName)
    {
        try {
            $newFileName = str_replace('Database/Xls/', 'Database/Xls/done/', $fileName);
            $region = $this->getRegions();

            $excel = Excel::open($fileName);
            $original = new VotersOriginalModel();
            $praDps = new VotersPraDpsModel();

            // Remove all data from district
            if (isset($this->payload['is_reset']) && isset($this->payload['district_id'])) {
                $praDps->softDeleteAll($this->payload['district_id']);
            }

            $result = $excel->readRows();

            $this->db->transStart();
            foreach ($result as $key => $val) {
                if ($key == 1 || $val == self::TEMP_FILE_NAME) {
                    continue;
                }

                $district = strtolower(str_replace(' ', '', $val['B'] ?? '0'));
                $village = strtolower(str_replace(' ', '', $val['C'] ?? '0'));

                $arr[] = [
                    'code' => isset($val['A']) && $val['A'] == '-' ? '' : $val['A'],
                    'm_districts_id' => $region[$district]['district_id'] ?? substr($val['B'] ?? '0', 0, 8) ?? '0',
                    'm_villages_id' => $region[$district][$village]['village_id'] ?? substr($val['C'] ?? '0', 0, 12) ?? '0',
                    'dp_id' => isset($val['D']) && $val['D'] != '' ? $val['D'] : '0',
                    'nkk' => isset($val['E']) && $val['E'] != '' ? $val['E'] : '0',
                    'nik' => isset($val['F']) && $val['F'] != '' ? $val['F'] : '0',
                    'name' => isset($val['G']) && $val['G'] != '' ? $val['G'] : '0',
                    'place_of_birth' => isset($val['H']) && $val['H'] != '-' ? $val['H'] : '0',
                    'date_of_birth' => $this->dateOfBirth($val['I'] ?? '0'),
                    'married_status' => $this->marriedStatus($val['J'] ?? 0),
                    'gender' => $this->gender($val['K'] ?? 1),
                    'address' => isset($val['L']) && $val['L'] != '-' ? $val['L'] : '0',
                    'rt' => isset($val['M']) && $val['M'] != '-' ? $val['M'] : '0',
                    'rw' => isset($val['N']) && $val['N'] != '-' ? $val['N'] : '0',
                    'disabilities' => isset($val['O']) && $val['O'] != '-' ? $val['O'] : 0,
                    'filters' => isset($val['P']) && $val['P'] != '-' ? $val['P'] : '0',
                    'm_data_status_id' => 1,
                    'tps' => isset($val['T']) ? $val['R'] : $val['Q'] ?? 0,
                    'sort_data' => $val['T'] ?? '',
                ];

                if (count($arr) > 20000) {
                    if (!empty($arr)) {
                        if (!isset($this->payload['is_pra_dps'])) {
                            // Insert data original
                            $original->insertBatch($arr);
                        }

                        // Insert data pra Dps
                        $praDps->insertBatch($arr);
                    }

                    $arr = [];
                }
            }

            if (!isset($this->payload['is_pra_dps']) && !empty($arr)) {
                $original->insertBatch($arr);
            }

            if (!empty($arr)) {
                $praDps->insertBatch($arr);
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
        $date = (($year == '0000' || $year == '****') ? '1972' : $year).'-'. ($month == '00' ? '01' : $month).'-'.($day ==  '00' ? '01' : $day);
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

    public function upload()
    {
        $fileInBase64 = $this->payload['file'];

        $fileInBase64 = str_replace(' ', '+', $fileInBase64);
        $file = base64_decode($fileInBase64);

        file_put_contents(self::SOURCE_EXCEL.self::TEMP_FILE_NAME, $file);

        return $this->successResponse(null, 'success upload data');
    }

    public function importDistricts()
    {
        try {
            $file = self::SOURCE_EXCEL.'temp.xlsx';

            if (file_exists($file)) {
                $this->fastImportExcel($file);
            }

            return $this->successResponse(null, 'success import data');
        } catch (\Throwable $th) {
            $this->db->transRollback();
            echo $th->getMessage();
        }
    }
}
