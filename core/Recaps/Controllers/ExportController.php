<?php

namespace Core\Recaps\Controllers;

use avadim\FastExcelWriter\Excel;
use Core\Recaps\Models\GetRecapModel;
use App\Exceptions\ValidationException;
use Core\Voters\Controllers\BaseVotersController;

class ExportController extends BaseVotersController
{
    protected $statusData;

    public function excel($statusDataId)
    {
        try {
            $villageId = $this->payload['village_id'] ?? null;
            $districtId = $this->payload['district_id'] ?? null;

            $this->statusData = $this->getStatusData($statusDataId);

            $model = new GetRecapModel();
            $model->setStatusDataId($this->statusData->id)
                    ->setVillageId($villageId)
                    ->setDistrictId($districtId);

            $list = $model->getAll();
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
        $excel = Excel::create('REKAP TPS');

        $this->generateRecap($excel, $list, 'REKAP TPS', 'voter', false)
            ->generateRecap($excel, $list, 'PEMILIH BARU', 'new_voter')
            ->generateRecap($excel, $list, 'PEMILIH PEMULA', 'novice_voter')
            ->generateRecap($excel, $list, 'BELUM KTP ELEKTRONIK', 'ktp_el')
            ->generateRecap($excel, $list, 'DISABILITAS', 'disabilities')
            ->generateRecap($excel, $list, 'UBAH DATA', 'profile_updated')
            ->generateTMS($excel, $list);

        // Save to XLSX-file
        $excel->output('Rekap-'.str_replace(' ', '-', $this->statusData->name).'.xlsx');
    }

    private function generateRecap(&$excel, &$list, string $sheetName, string $fieldName, $isCreateSheet = true)
    {
        if ($isCreateSheet) {
            $sheet = $excel->makeSheet($sheetName);
        } else {
            $sheet = $excel->getSheet($sheetName);
        }

        $sheet->mergeCells('A1:F1');

        // The fastest way to write data is row by row
        $area = $sheet->beginArea('A1');
        $area->setValue("A1", "REKAPITULASI {$sheetName}", ['font-style' => 'bold', 'font-size' => 16]);

        $area->setValue("A3", "DESA / KELURAHAN", ['font-style' => 'bold', 'border' => 'thin']);
        $area->setValue("B3", "KECAMATAN", ['font-style' => 'bold', 'border' => 'thin']);
        $area->setValue("C3", "TPS", ['font-style' => 'bold', 'border' => 'thin']);
        $area->setValue("D3", "LAKI-LAKI", ['font-style' => 'bold', 'border' => 'thin']);
        $area->setValue("E3", "PEREMPUAN", ['font-style' => 'bold', 'border' => 'thin']);
        $area->setValue("F3", "TOTAL", ['font-style' => 'bold', 'border' => 'thin']);

        $startRow = 4;
        foreach ($list as $key => $val) {
            $val = $val->toArray();
            $row = $key + $startRow;
            $male = $val[$fieldName.'_m'];
            $female = $val[$fieldName.'_f'];

            $area->setValue("A$row", $val["village_name"], ['border' => 'thin']);
            $area->setValue("B$row", $val["district_name"], ['border' => 'thin']);
            $area->setValue("C$row", $val['tps'], ['border' => 'thin']);
            $area->setValue("D$row", $male, ['border' => 'thin']);
            $area->setValue("E$row", $female, ['border' => 'thin']);
            $area->setValue("F$row", "=SUM(D$row:E$row)", ['font-style' => 'bold', 'border' => 'thin']);
        }

        $lastRow = ($row ?? $startRow) + 1;
        $area->setValue("D$lastRow", "=SUM(D$startRow:D$row)", ['font-style' => 'bold', 'border' => 'thin']);
        $area->setValue("E$lastRow", "=SUM(E$startRow:E$row)", ['font-style' => 'bold', 'border' => 'thin']);
        $area->setValue("F$lastRow", "=SUM(D$lastRow:E$lastRow)", ['font-style' => 'bold', 'border' => 'thin']);

        return $this;
    }

    private function generateTMS(&$excel, &$list)
    {
        $sheet = $excel->makeSheet('TMS');
        $sheet->mergeCells('A1:F1', ['font-style' => 'bold', 'font-size' => 16]);
        $sheet->mergeCells('A3:A4', ['font-style' => 'bold', 'font-size' => 16]);
        $sheet->mergeCells('B3:B4', ['font-style' => 'bold', 'font-size' => 16]);
        $sheet->mergeCells('C3:C4');
        $sheet->mergeCells('D3:E3');
        $sheet->mergeCells('F3:G3');
        $sheet->mergeCells('H3:I3');
        $sheet->mergeCells('J3:K3');
        $sheet->mergeCells('L3:M3');
        $sheet->mergeCells('N3:O3');
        $sheet->mergeCells('P3:P4');

        // The fastest way to write data is row by row
        $area = $sheet->beginArea('A1');
        $area->setValue("A1", "REKAPITULASI TMS", ['font-style' => 'bold', 'font-size' => 16]);

        $area->setValue("A3", "DESA / KELURAHAN");
        $area->setValue("B3", "KECAMATAN");
        $area->setValue("C3", "TPS");
        $area->setValue("D3", "TIDAK DIKENAL");
        $area->setValue("F3", "MENINGGAL");
        $area->setValue("H3", "GANDA");
        $area->setValue("J3", "BLM CUKUP UMUR");
        $area->setValue("L3", "TNI");
        $area->setValue("N3", "POLRI");
        $area->setValue("P3", "TOTAL");

        $area->setValue("D4", "L");
        $area->setValue("E4", "P");
        $area->setValue("F4", "L");
        $area->setValue("G4", "P");
        $area->setValue("H4", "L");
        $area->setValue("I4", "P");
        $area->setValue("J4", "L");
        $area->setValue("K4", "P");
        $area->setValue("L4", "L");
        $area->setValue("M4", "P");
        $area->setValue("N4", "L");
        $area->setValue("O4", "P");
        $area->setValue("P4", "TOTAL");

        $area->setStyle('A3:P4', ['font-style' => 'bold', 'border' => 'thin']); // Set style for single cell of area

        $border = ['border' => 'thin'];

        $startRow = 5;
        foreach ($list as $key => $val) {
            $val = $val->toArray();
            $row = $key + $startRow;

            $area->setValue("A$row", $val["village_name"], $border);
            $area->setValue("B$row", $val["district_name"], $border);
            $area->setValue("C$row", $val['tps'], $border);
            $area->setValue("D$row", $val['unknown_m'], $border);
            $area->setValue("E$row", $val['unknown_f'], $border);
            $area->setValue("F$row", $val['pass_away_m'], $border);
            $area->setValue("G$row", $val['pass_away_f'], $border);
            $area->setValue("H$row", $val['double_m'], $border);
            $area->setValue("I$row", $val['double_f'], $border);
            $area->setValue("J$row", $val['minor_m'], $border);
            $area->setValue("K$row", $val['minor_f'], $border);
            $area->setValue("L$row", $val['tni_m'], $border);
            $area->setValue("M$row", $val['tni_f'], $border);
            $area->setValue("N$row", $val['polri_m'], $border);
            $area->setValue("O$row", $val['polri_f'], $border);
            $area->setValue("P$row", "=SUM(D$row:O$row)", $border);
        }

        $lastRow = ($row ?? $startRow) + 1;

        $area->setValue("D$lastRow", "=SUM(D$startRow:D$row)", $border);
        $area->setValue("E$lastRow", "=SUM(E$startRow:E$row)", $border);
        $area->setValue("F$lastRow", "=SUM(F$startRow:F$row)", $border);
        $area->setValue("G$lastRow", "=SUM(G$startRow:G$row)", $border);
        $area->setValue("H$lastRow", "=SUM(H$startRow:H$row)", $border);
        $area->setValue("I$lastRow", "=SUM(I$startRow:I$row)", $border);
        $area->setValue("J$lastRow", "=SUM(J$startRow:J$row)", $border);
        $area->setValue("K$lastRow", "=SUM(K$startRow:K$row)", $border);
        $area->setValue("L$lastRow", "=SUM(L$startRow:L$row)", $border);
        $area->setValue("M$lastRow", "=SUM(M$startRow:M$row)", $border);
        $area->setValue("N$lastRow", "=SUM(N$startRow:N$row)", $border);
        $area->setValue("O$lastRow", "=SUM(O$startRow:O$row)", $border);
        $area->setValue("P$lastRow", "=SUM(D$lastRow:O$lastRow)", $border);

        return $this;
    }
}
