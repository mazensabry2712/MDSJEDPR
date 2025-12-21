<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Collection;

class CustomerProjectsExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle
{
    protected $data;
    protected $customerName;

    public function __construct($data, $customerName = null)
    {
        $this->data = $data;
        $this->customerName = $customerName;
    }

    public function collection()
    {
        $collection = new Collection();

        foreach ($this->data as $project) {
            $collection->push([
                $project['index'] ?? '',
                $project['pr_number'] ?? 'N/A',
                $project['name'] ?? 'N/A',
                $project['value'] ?? 'N/A',
                $project['po_number'] ?? 'N/A',
                $project['deadline'] ?? 'N/A'
            ]);
        }

        return $collection;
    }

    public function headings(): array
    {
        return [
            '#',
            'PR Number',
            'Project Name',
            'Value',
            'Customer PO',
            'Deadline'
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
        if ($highestRow > 1) {
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
        }

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // #
            'B' => 15,  // PR Number
            'C' => 35,  // Project Name
            'D' => 15,  // Value
            'E' => 20,  // Customer PO
            'F' => 15,  // Deadline
        ];
    }

    public function title(): string
    {
        return $this->customerName ? substr($this->customerName, 0, 31) : 'Projects';
    }
}
