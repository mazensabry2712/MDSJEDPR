<?php

namespace App\Exports;

use App\Models\Coc;
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

class CocExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    private static $rowIndex = 0;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        self::$rowIndex = 0;
        return Coc::with('project')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            '#',
            'PR Number',
            'Project Name',
            'Upload Date',
            'Upload Time'
        ];
    }

    /**
     * @param mixed $coc
     * @return array
     */
    public function map($coc): array
    {
        self::$rowIndex++;

        $uploadDate = $coc->created_at ? $coc->created_at->format('Y-m-d') : 'N/A';
        $uploadTime = $coc->created_at ? $coc->created_at->format('h:i A') : 'N/A';

        return [
            self::$rowIndex,
            $coc->project && $coc->project->pr_number ? $coc->project->pr_number : 'N/A',
            $coc->project && $coc->project->name ? $coc->project->name : 'No project assigned',
            $uploadDate,
            $uploadTime
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
            'B' => 15, // PR Number
            'C' => 35, // Project Name
            'D' => 15, // Upload Date
            'E' => 15, // Upload Time
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Certificate of Compliance';
    }
}
