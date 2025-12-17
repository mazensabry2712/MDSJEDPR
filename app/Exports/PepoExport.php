<?php

namespace App\Exports;

use App\Models\pepo;
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

class PepoExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    private static $rowIndex = 0;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        self::$rowIndex = 0;
        return pepo::with('project')->orderBy('created_at', 'desc')->get();
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
            'Category',
            'Planned Cost',
            'Selling Price',
            'Margin (%)'
        ];
    }

    /**
     * @param mixed $pepo
     * @return array
     */
    public function map($pepo): array
    {
        self::$rowIndex++;

        // Calculate margin percentage (multiply by 100)
        $marginPercentage = 'N/A';
        if ($pepo->margin !== null) {
            $marginPercentage = number_format($pepo->margin * 100, 2) . '%';
        }

        return [
            self::$rowIndex,
            $pepo->project && $pepo->project->pr_number ? $pepo->project->pr_number : 'N/A',
            $pepo->project && $pepo->project->name ? $pepo->project->name : 'N/A',
            $pepo->category ?? '-',
            $pepo->planned_cost ? number_format($pepo->planned_cost, 2) : '0.00',
            $pepo->selling_price ? number_format($pepo->selling_price, 2) : '0.00',
            $marginPercentage
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
        $sheet->getStyle('A1:G1')->applyFromArray([
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
        $sheet->getStyle('A1:G' . $lastRow)->applyFromArray([
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
            'C' => 30, // Project Name
            'D' => 20, // Category
            'E' => 15, // Planned Cost
            'F' => 15, // Selling Price
            'G' => 15, // Margin (%)
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Project EPO';
    }
}
