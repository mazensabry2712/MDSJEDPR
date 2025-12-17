<?php

namespace App\Exports;

use App\Models\Cust;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class CustomersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Cust::all();
    }

    /**
     * Define the headings for the Excel file
     */
    public function headings(): array
    {
        return [
            '#',
            'Customer Name',
            'Customer Abb',
            'Customer Type',
            'Logo',
            'Customer Contact Name',
            'Customer Contact Position',
            'Email',
            'Phone'
        ];
    }

    /**
     * Map the data for each row
     */
    public function map($customer): array
    {
        static $index = 0;
        $index++;

        // Check logo availability
        $logo = $customer->logo && file_exists(public_path($customer->logo))
            ? 'Available'
            : 'No Logo';

        return [
            $index,
            $customer->name ?? 'N/A',
            $customer->abb ?? 'N/A',
            $customer->tybe ?? 'N/A',
            $logo,
            $customer->customercontactname ?? 'N/A',
            $customer->customercontactposition ?? 'N/A',
            $customer->email ?? 'N/A',
            $customer->phone ?? 'N/A'
        ];
    }

    /**
     * Apply styles to the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '677EEA']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Add borders to all cells
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:I' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC']
                ]
            ]
        ]);

        // Center align # and Logo columns
        $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E2:E' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Set row height for header
        $sheet->getRowDimension(1)->setRowHeight(25);

        return [];
    }

    /**
     * Set the sheet title
     */
    public function title(): string
    {
        return 'Customers';
    }

    /**
     * Define column widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,   // #
            'B' => 25,  // Customer Name
            'C' => 15,  // Customer Abb
            'D' => 15,  // Customer Type
            'E' => 12,  // Logo
            'F' => 25,  // Customer Contact Name
            'G' => 25,  // Customer Contact Position
            'H' => 30,  // Email
            'I' => 15,  // Phone
        ];
    }
}
