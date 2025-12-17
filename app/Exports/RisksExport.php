<?php

namespace App\Exports;

use App\Models\Risks;
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

class RisksExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    private static $rowIndex = 0;

    public function __construct()
    {
        self::$rowIndex = 0;
    }

    public function collection()
    {
        return Risks::with(['project:id,pr_number,name'])->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'PR Number',
            'Project Name',
            'Risk/Issue',
            'Impact',
            'Mitigation',
            'Owner',
            'Status'
        ];
    }

    public function map($risk): array
    {
        self::$rowIndex++;

        // Convert impact to readable format
        $impactMap = [
            'low' => 'Low',
            'med' => 'Medium',
            'high' => 'High'
        ];
        $impact = $impactMap[$risk->impact] ?? ucfirst($risk->impact ?? 'N/A');

        // Convert status to readable format
        $statusMap = [
            'open' => 'Open',
            'closed' => 'Closed'
        ];
        $status = $statusMap[$risk->status] ?? ucfirst($risk->status ?? 'N/A');

        return [
            self::$rowIndex,
            $risk->project && $risk->project->pr_number ? $risk->project->pr_number : 'N/A',
            $risk->project && $risk->project->name ? $risk->project->name : 'N/A',
            $risk->risk ?: 'No risk description',
            $impact,
            $risk->mitigation ?: 'N/A',
            $risk->owner ?? 'N/A',
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
            'D' => 40,  // Risk/Issue
            'E' => 12,  // Impact
            'F' => 40,  // Mitigation
            'G' => 20,  // Owner
            'H' => 12,  // Status
        ];
    }

    public function title(): string
    {
        return 'Project Risks';
    }
}
