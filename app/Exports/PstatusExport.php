<?php

namespace App\Exports;

use App\Models\Pstatus;
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

class PstatusExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    private static $rowIndex = 0;

    public function __construct()
    {
        self::$rowIndex = 0;
    }

    public function collection()
    {
        return Pstatus::with(['project:id,pr_number,name', 'ppm:id,name'])->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'PR Number',
            'Project Name',
            'Date & Time',
            'PM Name',
            'Status',
            'Actual %',
            'Expected Date',
            'Pending Cost',
            'Notes'
        ];
    }

    public function map($pstatus): array
    {
        self::$rowIndex++;

        return [
            self::$rowIndex,
            $pstatus->project && $pstatus->project->pr_number ? $pstatus->project->pr_number : 'N/A',
            $pstatus->project && $pstatus->project->name ? $pstatus->project->name : 'N/A',
            $pstatus->date_time ? \Carbon\Carbon::parse($pstatus->date_time)->format('d/m/Y H:i') : 'N/A',
            $pstatus->ppm && $pstatus->ppm->name ? $pstatus->ppm->name : 'N/A',
            $pstatus->status ?: 'No status',
            $pstatus->actual_completion ? number_format($pstatus->actual_completion, 2) . '%' : 'N/A',
            $pstatus->expected_completion ? \Carbon\Carbon::parse($pstatus->expected_completion)->format('d/m/Y') : 'N/A',
            $pstatus->date_pending_cost_orders ?: 'N/A',
            $pstatus->notes ?: 'No notes'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // Header style (blue background)
        $sheet->getStyle('A1:' . $highestColumn . '1')->applyFromArray([
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
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Body style (all cells with borders and center alignment)
        $sheet->getStyle('A2:' . $highestColumn . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // #
            'B' => 15,  // PR Number
            'C' => 30,  // Project Name
            'D' => 20,  // Date & Time
            'E' => 20,  // PM Name
            'F' => 30,  // Status
            'G' => 12,  // Actual %
            'H' => 18,  // Expected Date
            'I' => 25,  // Pending Cost
            'J' => 35,  // Notes
        ];
    }

    public function title(): string
    {
        return 'Project Status';
    }
}
