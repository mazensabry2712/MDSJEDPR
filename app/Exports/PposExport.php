<?php

namespace App\Exports;

use App\Models\Ppos;
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

class PposExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    private static $rowIndex = 0;

    public function __construct()
    {
        self::$rowIndex = 0;
    }

    public function collection()
    {
        return Ppos::with(['project:id,pr_number,name', 'pepo:id,category', 'ds:id,dsname'])->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'PR Number',
            'Project Name',
            'Category',
            'Supplier Name',
            'PO Number',
            'Value',
            'Date',
            'Status',
            'Updates',
            'Notes'
        ];
    }

    public function map($ppo): array
    {
        self::$rowIndex++;

        // Get all categories for this PO Number
        $allCategories = Ppos::where('po_number', $ppo->po_number)
            ->with('pepo:id,category')
            ->get()
            ->pluck('pepo.category')
            ->filter()
            ->unique()
            ->implode(', ');

        return [
            self::$rowIndex,
            $ppo->project && $ppo->project->pr_number ? $ppo->project->pr_number : 'N/A',
            $ppo->project && $ppo->project->name ? $ppo->project->name : 'N/A',
            $allCategories ?: 'N/A',
            $ppo->ds && $ppo->ds->dsname ? $ppo->ds->dsname : 'N/A',
            $ppo->po_number ?? 'N/A',
            $ppo->value ? number_format($ppo->value, 2) . ' SAR' : 'N/A',
            $ppo->date ? $ppo->date->format('Y-m-d') : 'N/A',
            $ppo->status ?? 'N/A',
            $ppo->updates ?? 'No updates',
            $ppo->notes ?? 'No notes'
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
            'D' => 20,  // Category
            'E' => 25,  // Supplier Name
            'F' => 15,  // PO Number
            'G' => 18,  // Value
            'H' => 15,  // Date
            'I' => 20,  // Status
            'J' => 35,  // Updates
            'K' => 35,  // Notes
        ];
    }

    public function title(): string
    {
        return 'PPOs';
    }
}
