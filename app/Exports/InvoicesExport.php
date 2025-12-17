<?php

namespace App\Exports;

use App\Models\invoices;
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

class InvoicesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    private static $rowIndex = 0;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        self::$rowIndex = 0;
        return invoices::with('project')->get();
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
            'Invoice Number',
            'Value',
            'PR Invoices Total Value',
            'Status'
        ];
    }

    /**
     * @param mixed $invoice
     * @return array
     */
    public function map($invoice): array
    {
        self::$rowIndex++;

        // Build PR Invoices Total Value with project value
        $prInvoicesTotalValue = '0.00';
        if ($invoice->pr_invoices_total_value) {
            $prInvoicesTotalValue = number_format($invoice->pr_invoices_total_value, 2);
            if ($invoice->project && $invoice->project->value) {
                $prInvoicesTotalValue .= ' of ' . number_format($invoice->project->value, 2);
            }
        }

        return [
            self::$rowIndex,
            $invoice->project && $invoice->project->pr_number ? $invoice->project->pr_number : 'N/A',
            $invoice->project && $invoice->project->name ? $invoice->project->name : 'No project assigned',
            $invoice->invoice_number ?? 'N/A',
            $invoice->value ? number_format($invoice->value, 2) : '0.00',
            $prInvoicesTotalValue,
            $invoice->status ?? 'N/A'
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
            'C' => 35, // Project Name
            'D' => 20, // Invoice Number
            'E' => 15, // Value
            'F' => 20, // PR Invoices Total Value
            'G' => 15, // Status
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Invoices';
    }
}
