<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ProjectsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Project::with(['vendors', 'deliverySpecialists', 'cust', 'aams', 'ppms'])->get();
    }

    /**
     * Define the headings for the Excel file
     */
    public function headings(): array
    {
        return [
            '#',
            'PR Number',
            'Project Name',
            'Technologies',
            'All Vendors',
            'All DS',
            'Customer',
            'Customer PO',
            'Value',
            'AC Manager',
            'Project Manager',
            'Customer Contact',
            'PO Attachment',
            'EPO Attachment',
            'PO Date',
            'Duration (Days)',
            'Deadline',
            'Description'
        ];
    }

    /**
     * Map the data for each row
     */
    public function map($project): array
    {
        static $index = 0;
        $index++;

        // Get all vendors with primary marker
        $vendors = $project->vendors && $project->vendors->count() > 0
            ? $project->vendors->map(function($vendor) {
                $name = $vendor->vendors;
                if ($vendor->pivot->is_primary) {
                    $name .= ' ★';
                }
                return $name;
            })->implode(', ')
            : 'N/A';

        // Get all DS with lead marker
        $ds = $project->deliverySpecialists && $project->deliverySpecialists->count() > 0
            ? $project->deliverySpecialists->map(function($specialist) {
                $name = $specialist->dsname;
                if ($specialist->pivot->is_lead) {
                    $name .= ' ★';
                }
                return $name;
            })->implode(', ')
            : 'N/A';

        // Check attachments
        $poAttachment = $project->po_attachment && file_exists(public_path($project->po_attachment))
            ? 'Available'
            : 'N/A';

        $epoAttachment = $project->epo_attachment && file_exists(public_path($project->epo_attachment))
            ? 'Available'
            : 'N/A';

        // Format dates
        $poDate = $project->customer_po_date
            ? \Carbon\Carbon::parse($project->customer_po_date)->format('M d, Y')
            : 'N/A';

        $deadline = $project->customer_po_deadline
            ? \Carbon\Carbon::parse($project->customer_po_deadline)->format('M d, Y')
            : 'N/A';

        return [
            $index,
            $project->pr_number ?? 'N/A',
            $project->name ?? 'N/A',
            $project->technologies ?? 'N/A',
            $vendors,
            $ds,
            $project->cust->name ?? 'N/A',
            $project->customer_po ?? 'N/A',
            $project->value ? '$' . number_format($project->value, 2) : 'N/A',
            $project->aams->name ?? 'N/A',
            $project->ppms->name ?? 'N/A',
            $project->customer_contact_details ?? 'N/A',
            $poAttachment,
            $epoAttachment,
            $poDate,
            $project->customer_po_duration ? $project->customer_po_duration . ' days' : 'N/A',
            $deadline,
            $project->description ?? 'N/A'
        ];
    }

    /**
     * Apply styles to the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:R1')->applyFromArray([
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
        $sheet->getStyle('A1:R' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC']
                ]
            ]
        ]);

        // Center align specific columns
        $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I2:I' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('M2:Q' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Set row height for header
        $sheet->getRowDimension(1)->setRowHeight(25);

        return [];
    }

    /**
     * Set the sheet title
     */
    public function title(): string
    {
        return 'Projects';
    }

    /**
     * Define column widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,   // #
            'B' => 12,  // PR Number
            'C' => 25,  // Project Name
            'D' => 15,  // Technologies
            'E' => 20,  // All Vendors
            'F' => 20,  // All DS
            'G' => 20,  // Customer
            'H' => 15,  // Customer PO
            'I' => 12,  // Value
            'J' => 18,  // AC Manager
            'K' => 18,  // Project Manager
            'L' => 20,  // Customer Contact
            'M' => 12,  // PO Attachment
            'N' => 12,  // EPO Attachment
            'O' => 12,  // PO Date
            'P' => 12,  // Duration
            'Q' => 12,  // Deadline
            'R' => 30,  // Description
        ];
    }
}
