<?php

namespace App\Exports;

use App\Models\Ptasks;
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

class PtasksExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    private static $rowIndex = 0;

    public function __construct()
    {
        self::$rowIndex = 0;
    }

    public function collection()
    {
        return Ptasks::with(['project:id,pr_number,name'])->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'PR Number',
            'Project Name',
            'Task Date',
            'Task Details',
            'Assigned To',
            'Expected Date',
            'Status'
        ];
    }

    public function map($ptask): array
    {
        self::$rowIndex++;

        // Convert status to readable format
        $statusMap = [
            'completed' => 'Completed',
            'progress' => 'Under Progress',
            'pending' => 'Pending',
            'hold' => 'On Hold'
        ];
        $status = $statusMap[$ptask->status] ?? ucfirst($ptask->status ?? 'N/A');

        return [
            self::$rowIndex,
            $ptask->project && $ptask->project->pr_number ? $ptask->project->pr_number : 'N/A',
            $ptask->project && $ptask->project->name ? $ptask->project->name : 'N/A',
            $ptask->task_date ? \Carbon\Carbon::parse($ptask->task_date)->format('d/m/Y') : 'N/A',
            $ptask->details ?: 'No details',
            $ptask->assigned ?? 'Not assigned',
            $ptask->expected_com_date ? \Carbon\Carbon::parse($ptask->expected_com_date)->format('d/m/Y') : 'N/A',
            $status
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
            'D' => 15,  // Task Date
            'E' => 40,  // Task Details
            'F' => 20,  // Assigned To
            'G' => 18,  // Expected Date
            'H' => 18,  // Status
        ];
    }

    public function title(): string
    {
        return 'Project Tasks';
    }
}
