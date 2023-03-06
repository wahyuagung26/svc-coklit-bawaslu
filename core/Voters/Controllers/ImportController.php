<?php

namespace Core\Voters\Controllers;

use avadim\FastExcelReader\Excel;
use App\Controllers\BaseController;
use Core\Regions\Models\DistrictsModel;
use Core\Voters\Models\GenerateModel;
use Core\Regions\Models\VillagesModel;
use Core\Voters\Models\VotersOriginalModel;

class ImportController extends BaseController
{
    public function copy() {
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
            $arr = [
                'BANTUR-2.xlsx',
                'BANTUR.xlsx',
                'BULULAWANG-2.xlsx',
                'DONOMULYO-2.xlsx',
                'DONOMULYO-3.xlsx',
                'GEDANGAN-2.xlsx',
                'GONDANGLEGI-3.xlsx',
                'JABUNG-2.xlsx',
                'KROMENGAN-2.xlsx',
                'NGAJUM-2.xlsx',
                'PAGAK-2.xlsx',
                'PAGELARAN-2.xlsx',
                'PUJON-2.xlsx',
                'PUJON.xlsx',
                'SINGOSARI-2.xlsx',
                'SINGOSARI-3.xlsx',
                'SINGOSARI-4.xlsx',
                'SINGOSARI-5.xlsx',
                'SUMBERPUCUNG-2.xlsx',
                'TAJINAN-2.xlsx',
                'TIRTOYUDO-2.xlsx',
                'TIRTOYUDO-3.xlsx',
                'WAGIR-2.xlsx',
                'WONOSARI-2.xlsx',
                'AMPELGADING-2.xlsx',
                'AMPELGADING.xlsx',
                'BULULAWANG.xlsx',
                'DAMPIT.xlsx',
                'DAMPIT-2.xlsx',
                'DAMPIT-3.xlsx',
                'DONOMULYO.xlsx',
                'GEDANGAN.xlsx',
                'GONDANGLEGI.xlsx',
                'GONDANGLEGI-2.xlsx',
                'JABUNG.xlsx',
                'KASEMBON.xlsx',
                'KROMENGAN.xlsx',
                'LAWANG.xlsx',
                'LAWANG-2.xlsx',
                'LAWANG-3.xlsx',
                'NGAJUM.xlsx',
                'PAGAK.xlsx',
                'PAGELARAN.xlsx',
                'PAKIS.xlsx',
                'PAKIS-2.xlsx',
                'PAKIS-3.xlsx',
                'PAKIS-4.xlsx',
                'SINGOSARI.xlsx',
                'SUMBERPUCUNG.xlsx',
                'TAJINAN.xlsx',
                'TIRTOYUDO.xlsx',
                'TUREN.xlsx',
                'TUREN-2.xlsx',
                'TUREN-3.xlsx',
                'WAGIR.xlsx',
                'DAU-2.xlsx',
                'DAU-3.xlsx',
                'KALIPARE-1.xlsx',
                'KALIPARE-2.xlsx',
                'KARANGPLOSO-1.xlsx',
                'KARANGPLOSO-2.xlsx',
                'KEPANJEN-1.xlsx',
                'KEPANJEN-2.xlsx',
                'KEPANJEN-3.xlsx',
                'PAKISAJI-1.xlsx',
                'PAKISAJI-2.xlsx',
                'SUMAWE-1.xlsx',
                'SUMAWE-2.xlsx',
                'SUMAWE-3.xlsx',
                'TUMPANG-1.xlsx',
                'TUMPANG-2.xlsx'
            ];

            foreach ($arr as $val) {
                $file = APPPATH."Database/Xls/".$val;
                print "Mulai import : $val at " .date("Y-m-d H:i:s"). PHP_EOL;
                ob_flush();
                flush();
                if (file_exists($file)) {
                    $this->fastExcelReader($file);
                    print "Berhasil import : $val at " .date("Y-m-d H:i:s"). PHP_EOL;
                    ob_flush();
                    flush();
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
                    'tps' => $val['R'],
                    'sort_data' => $val['T'],
                ];

                if (count($arr) > 20000) {
                    $model = new VotersOriginalModel();
                    if (!empty($arr)) {
                        $model->insertBatch($arr);
                        echo "Insert : ".count($val)." data ".$fileName." at " .date("Y-m-d H:i:s"). PHP_EOL;
                        ob_flush();
                        flush();
                    }

                    $arr = [];
                }
            }

            $model = new VotersOriginalModel();
            if (!empty($arr)) {
                $model->insertBatch($arr);
            }

            // $generate = new GenerateModel();
            // $generate->setActiveTable('voters_pra_dps')
            //         ->setSourceTable('voters_original')
            //         ->setDistrict($districtId)
            //         ->setVillageId(1)
            //         ->run();

            rename($fileName, $newFileName);

            $this->db->transCommit();
        } catch (\Throwable $th) {
            if (file_exists($newFileName)) {
                rename($newFileName, $fileName);
            }
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
