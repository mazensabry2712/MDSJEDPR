<?php

namespace App\Exports;

use App\Models\Dn;
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

class DnExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    private static $rowIndex = 0;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        self::$rowIndex = 0;
        return Dn::with('project')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            '#',
            'DN Number',
            'PR Number',
            'Project Name',
            'Date'
        ];
    }

    /**
     * @param mixed $dn
     * @return array
     */
    public function map($dn): array
    {
        self::$rowIndex++;

        return [
            self::$rowIndex,
            $dn->dn_number ?? 'N/A',
            $dn->project && $dn->project->pr_number ? $dn->project->pr_number : 'N/A',
            $dn->project && $dn->project->name ? $dn->project->name : 'No project assigned',
            $dn->date ? \Carbon\Carbon::parse($dn->date)->format('d/m/Y') : 'N/A'
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
        $sheet->getStyle('A1:E1')->applyFromArray([
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
        $sheet->getStyle('A1:E' . $lastRow)->applyFromArray([
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
            'B' => 15, // DN Number
            'C' => 15, // PR Number
            'D' => 35, // Project Name
            'E' => 20, // Date
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Delivery Notes';
    }
}
