<?php

namespace App\Exports;

use App\Models\vendors;
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

class VendorsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    private static $rowIndex = 0;

    public function __construct()
    {
        self::$rowIndex = 0;
    }

    public function collection()
    {
        return vendors::select('id', 'vendors', 'vendor_am_details')->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Vendors',
            'Vendor AM Details'
        ];
    }

    public function map($vendor): array
    {
        self::$rowIndex++;

        return [
            self::$rowIndex,
            $vendor->vendors ?? 'N/A',
            $vendor->vendor_am_details ?? 'N/A'
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
            'B' => 30,  // Vendors
            'C' => 50,  // Vendor AM Details
        ];
    }

    public function title(): string
    {
        return 'Vendors';
    }
}
