<?php

namespace App\Exports;

use App\Models\ds;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    private static $rowIndex = 0;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        self::$rowIndex = 0;
        return ds::all();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            '#',
            'DS Name',
            'DS Contact'
        ];
    }

    /**
     * @param mixed $ds
     * @return array
     */
    public function map($ds): array
    {
        self::$rowIndex++;

        return [
            self::$rowIndex,
            $ds->dsname ?? 'N/A',
            $ds->ds_contact ?? 'N/A'
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        $lastRow = self::$rowIndex + 1;

        // Header styling
        $sheet->getStyle('A1:C1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '677EEA'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Apply borders to all cells
        $sheet->getStyle('A1:C' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Center align # column
        $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,  // #
            'B' => 35, // DS Name
            'C' => 40, // DS Contact
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Delivery Specialists';
    }
}
