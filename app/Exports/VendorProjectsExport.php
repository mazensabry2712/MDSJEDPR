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

class VendorProjectsExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle
{
    protected $data;
    protected $vendorName;

    public function __construct($data, $vendorName = null)
    {
        $this->data = $data;
        $this->vendorName = $vendorName;
    }

    public function collection()
    {
        $collection = new Collection();

        foreach ($this->data as $index => $project) {
            $collection->push([
                $index + 1,
                $project['pr_number'] ?? 'N/A',
                $project['name'] ?? 'N/A',
                $project['customer'] ?? 'N/A',
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
            'Customer',
            'Value',
            'PO Number',
            'Deadline'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

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
            'D' => 25,  // Customer
            'E' => 15,  // Value
            'F' => 20,  // PO Number
            'G' => 15,  // Deadline
        ];
    }

    public function title(): string
    {
        return $this->vendorName ? substr($this->vendorName, 0, 31) : 'Vendor Projects';
    }
}
