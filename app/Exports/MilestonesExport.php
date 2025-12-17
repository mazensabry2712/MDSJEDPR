<?php

namespace App\Exports;

use App\Models\Milestones;
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

class MilestonesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    private static $rowIndex = 0;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        self::$rowIndex = 0;
        return Milestones::with('project')->orderBy('created_at', 'desc')->get();
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
            'Milestone',
            'Planned Completion',
            'Actual Completion',
            'Status',
            'Comments'
        ];
    }

    /**
     * @param mixed $milestone
     * @return array
     */
    public function map($milestone): array
    {
        self::$rowIndex++;

        return [
            self::$rowIndex,
            $milestone->project && $milestone->project->pr_number ? $milestone->project->pr_number : 'N/A',
            $milestone->project && $milestone->project->name ? $milestone->project->name : 'N/A',
            $milestone->milestone ?? 'N/A',
            $milestone->planned_com ? date('Y-m-d', strtotime($milestone->planned_com)) : 'N/A',
            $milestone->actual_com ? date('Y-m-d', strtotime($milestone->actual_com)) : 'N/A',
            $milestone->status == 'on track' ? 'On Track' : 'Delayed',
            $milestone->comments ?? '-'
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
        $sheet->getStyle('A1:H1')->applyFromArray([
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
        $sheet->getStyle('A1:H' . $lastRow)->applyFromArray([
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
            'D' => 25, // Milestone
            'E' => 18, // Planned Completion
            'F' => 18, // Actual Completion
            'G' => 15, // Status
            'H' => 35, // Comments
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Project Milestones';
    }
}
