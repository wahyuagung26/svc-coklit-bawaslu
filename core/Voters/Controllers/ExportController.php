<?php

namespace Core\Voters\Controllers;

use avadim\FastExcelWriter\Excel;
use Core\Voters\Models\GetVotersModel;
use App\Exceptions\ValidationException;
use Core\Voters\Controllers\BaseVotersController;

class ExportController extends BaseVotersController
{
    private $statusData;

    public function excel($statusDataId)
    {
        try {
            $this->statusData = $this->getStatusData($statusDataId);

            $model = new GetVotersModel();
            $tableName = $this->statusData->active_table_source;

            $payload = $this->payload;
            $list = $model->setActiveTable($tableName)
                        ->getAll()
                        ->setFilter($payload)
                        ->result();

            $this->convertToExcel($list);
        } catch (ValidationException $th) {
            return $this->failedValidationResponse([], $th->getMessage(), $th->getCode());
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }

    private function convertToExcel(&$list)
    {
        // Create Excel workbook
        $excel = Excel::create();

        // Get the first sheet;
        $sheet = $excel->getSheet();
        $sheet->writeHeader([
            'NIK',
            'NO KK',
            'NAMA',
            'TEMPAT LAHIR',
            'TANGGAL LAHIR',
            'JENIS KELAMIN',
            'STATUS PERKAWINAN',
            'ALAMAT',
            'RT',
            'RW',
            'DESA',
            'KECAMATAN',
            'TPS',
            'KODE TMS',
            'COKLIT',
            'KTP ELEKTRONIK',
            'PEMILIH BARU',
            'PEMILIH PEMULA',
            'KODE DISABILITAS',
            'UBAH DATA',
            'STATUS'
        ]);

        // The fastest way to write data is row by row
        $area = $sheet->beginArea('A2');
        foreach ($list as $key => $val) {
            $index = $key+2;

            $tms = empty($val['tms']) ? '' : $val['tms'];
            $disabilities = empty($val['disabilities']) ? '' : $val['disabilities'];
            $coklit = $val['is_coklit'] == 1 ? 'Ya' : '';
            $checked = $val['is_checked'] == 1 ? 'Ya' : '';
            $ktpEl = $val['is_ktp_el'] == 1 ? '' : 'Tidak';
            $newVoter = $val['is_new_voter'] == 1 ? 'Ya' : '';
            $noviceVoter = $val['is_novice_voter'] == 1 ? 'Ya' : '';
            $profileUpdate = $val['is_profile_updated'] == 1 ? 'Ya' : '';
            $gender = $val['gender'] == 1 ? 'Laki-Laki' : 'Perempuan';
            $married = $val['married_status'] == 1 ? 'Sudah Kawin' : 'Belum Kawin';

            $area->setValue("A$index", $val['nik'], ['format' => '@string']);
            $area->setValue("B$index", $val['nkk'], ['format' => '@string']);
            $area->setValue("C$index", $val['name']);
            $area->setValue("D$index", $val['place_of_birth']);
            $area->setValue("E$index", $val['date_of_birth'], ['format' => '@date']);
            $area->setValue("F$index", $gender);
            $area->setValue("G$index", $married);
            $area->setValue("H$index", $val['address']);
            $area->setValue("I$index", $val['rt']);
            $area->setValue("J$index", $val['rw']);
            $area->setValue("K$index", $val['village_name']);
            $area->setValue("L$index", $val['district_name']);
            $area->setValue("M$index", $val['tps']);
            $area->setValue("N$index", $tms);
            $area->setValue("O$index", $coklit);
            $area->setValue("P$index", $ktpEl);
            $area->setValue("Q$index", $newVoter);
            $area->setValue("R$index", $noviceVoter);
            $area->setValue("S$index", $disabilities);
            $area->setValue("T$index", $profileUpdate);
            $area->setValue("U$index", $checked);
        }

        // Save to XLSX-file
        $excel->output(str_replace(' ', '-', $this->statusData->name).'.xlsx');
    }
}
