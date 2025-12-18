<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource with caching for speed
     */
    public function index()
    {
        // Cache for 1 hour (3600 seconds) for ultra-fast performance
        $invoices = Cache::remember('invoices_list', 3600, function () {
            return invoices::with('project:id,pr_number,name,value')->get();
        });

        return view('dashboard.invoice.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource with caching
     */
    public function create()
    {
        // Cache projects list for speed
        $pr_number_idd = Cache::remember('projects_list', 3600, function () {
            return Project::select('id', 'pr_number', 'name')->get();
        });

        return view('dashboard.invoice.create', compact('pr_number_idd'));
    }

    /**
     * Store a newly created resource in storage.
     * Files saved to external 'storge' folder for speed
     * Auto-calculates pr_invoices_total_value
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'invoice_number' => 'required|unique:invoices,invoice_number',
            'value' => 'required|numeric|min:0',
            'pr_number' => 'required|exists:projects,id',
            'invoice_copy_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png,gif|max:10240', // 10MB max
            'status' => 'required|string|max:255',
        ]);

        // Handle file upload to external 'storge' folder
        if ($request->hasFile('invoice_copy_path')) {
            $file = $request->file('invoice_copy_path');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Move to external 'storge' folder (not storage)
            $file->move(public_path('../storge'), $filename);

            $data['invoice_copy_path'] = $filename;
        }

        // Calculate total value for this PR_NUMBER
        // Sum all existing invoices with same pr_number + current value
        $existingTotal = invoices::where('pr_number', $data['pr_number'])->sum('value');
        $data['pr_invoices_total_value'] = $existingTotal + $data['value'];

        // Create invoice
        invoices::create($data);

        // Update all other invoices with same pr_number to have the same total
        $newTotal = invoices::where('pr_number', $data['pr_number'])->sum('value');
        invoices::where('pr_number', $data['pr_number'])
            ->update(['pr_invoices_total_value' => $newTotal]);

        // Clear cache for instant update
        Cache::forget('invoices_list');

        session()->flash('Add', 'Invoice added successfully! ✅');

        return redirect()->route('invoices.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $invoice = invoices::with('project:id,pr_number,name,value')->findOrFail($id);
        return view('dashboard.invoice.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource with caching
     */
    public function edit($id)
    {
        $invoices = invoices::findOrFail($id);

        // Cache projects list for speed
        $pr_number_idd = Cache::remember('projects_list', 3600, function () {
            return Project::select('id', 'pr_number', 'name')->get();
        });

        return view('dashboard.invoice.edit', compact('invoices', 'pr_number_idd'));
    }

    /**
     * Update the specified resource in storage.
     * Files saved to external 'storge' folder for speed
     * Auto-recalculates pr_invoices_total_value
     */
    public function update(Request $request, $id)
    {
        $invoices = invoices::findOrFail($id);
        $oldPrNumber = $invoices->pr_number; // Store old PR number in case it changes

        $data = $request->validate([
            'invoice_number' => 'required|unique:invoices,invoice_number,' . $id,
            'value' => 'required|numeric|min:0',
            'invoice_copy_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png,gif|max:10240', // 10MB
            'status' => 'required|string|max:255',
            'pr_number' => 'required|exists:projects,id'
        ]);

        // Handle new file upload to external 'storge' folder
        if ($request->hasFile('invoice_copy_path')) {
            // Delete old file if exists
            if ($invoices->invoice_copy_path) {
                $oldFilePath = public_path('../storge/' . $invoices->invoice_copy_path);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            // Upload new file to external 'storge' folder
            $file = $request->file('invoice_copy_path');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('../storge'), $filename);

            $data['invoice_copy_path'] = $filename;
        } else {
            // Keep existing file
            unset($data['invoice_copy_path']);
        }

        // Update invoice
        $invoices->update($data);

        // Recalculate total for NEW pr_number
        $newTotal = invoices::where('pr_number', $data['pr_number'])->sum('value');
        invoices::where('pr_number', $data['pr_number'])
            ->update(['pr_invoices_total_value' => $newTotal]);

        // If pr_number changed, recalculate total for OLD pr_number too
        if ($oldPrNumber != $data['pr_number']) {
            $oldTotal = invoices::where('pr_number', $oldPrNumber)->sum('value');
            invoices::where('pr_number', $oldPrNumber)
                ->update(['pr_invoices_total_value' => $oldTotal]);
        }

        // Clear cache for instant update
        Cache::forget('invoices_list');

        session()->flash('edit', 'Invoice updated successfully! ✅');

        return redirect()->route('invoices.index');
    }

    /**
     * Remove the specified resource from storage.
     * Also deletes file from external 'storge' folder
     * Auto-recalculates pr_invoices_total_value after deletion
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        $invoice = invoices::findOrFail($id);
        $prNumber = $invoice->pr_number; // Store before deletion

        // Delete file from external 'storge' folder if exists
        if ($invoice->invoice_copy_path) {
            $filePath = public_path('../storge/' . $invoice->invoice_copy_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Delete invoice record
        $invoice->delete();

        // Recalculate total for remaining invoices with same pr_number
        $newTotal = invoices::where('pr_number', $prNumber)->sum('value');
        invoices::where('pr_number', $prNumber)
            ->update(['pr_invoices_total_value' => $newTotal]);

        // Clear cache for instant update
        Cache::forget('invoices_list');

        session()->flash('delete', 'Invoice deleted successfully! 🗑️');

        return redirect()->route('invoices.index');
    }

    /**
     * Export Invoices to PDF
     */
    public function exportPDF()
    {
        try {
            $invoices = invoices::with('project:id,pr_number,name,value')->get();

            $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

            // Set document information
            $pdf->SetCreator('MDS JED Project System');
            $pdf->SetAuthor('Corporate Sites Management System');
            $pdf->SetTitle('Invoices Report');
            $pdf->SetSubject('Invoices Export - Card View');

            // Remove default header/footer
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            // Set margins for A4
            $pdf->SetMargins(10, 10, 10);
            $pdf->SetAutoPageBreak(false);

            // Set font
            $pdf->SetFont('helvetica', '', 9);

            $cardCount = 0;
            $cardsPerPage = 5;
            $cardHeight = 52;
            $cardGap = 1.5;

            foreach ($invoices as $index => $invoice) {
                // Add new page for every set of cards
                if ($cardCount % $cardsPerPage == 0) {
                    $pdf->AddPage('P');

                    // Page Header
                    $pdf->SetFont('helvetica', 'B', 14);
                    $pdf->SetTextColor(103, 126, 234);
                    $pdf->Cell(0, 8, 'Invoices Report', 0, 1, 'C');

                    $pdf->SetFont('helvetica', '', 8);
                    $pdf->SetTextColor(120, 120, 120);
                    $pdf->Cell(0, 5, 'Generated: ' . date('d/m/Y g:i A'), 0, 1, 'C');
                    $pdf->Ln(2);

                    // Add footer for each page
                    $pdf->SetY(-10);
                    $pdf->SetFont('helvetica', 'B', 9);
                    $pdf->SetTextColor(103, 126, 234);
                    $pdf->Cell(0, 8, 'MDSJEDPR', 0, 0, 'C');

                    // Reset Y position for content
                    $pdf->SetY(25);
                }

                // Card container
                $cardY = $pdf->GetY();

                // Card border and background
                $pdf->SetFillColor(255, 255, 255);
                $pdf->SetDrawColor(103, 126, 234);
                $pdf->SetLineWidth(0.5);
                $pdf->RoundedRect(10, $cardY, 190, $cardHeight, 3, '1111', 'DF');

                // Card header with invoice number
                $pdf->SetFillColor(103, 126, 234);
                $pdf->SetTextColor(255, 255, 255);
                $pdf->RoundedRect(10, $cardY, 190, 8, 3, '1100', 'F');

                $pdf->SetXY(12, $cardY + 1.5);
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->Cell(90, 5, 'Invoice: ' . ($invoice->invoice_number ?? 'N/A'), 0, 0, 'L');

                $pdf->SetFont('helvetica', '', 7);
                $pdf->Cell(96, 5, 'Invoice #' . ($index + 1), 0, 1, 'R');

                // Content area starts below the header
                $contentStartY = $cardY + 10;
                $currentY = $contentStartY;

                // Column settings
                $leftX = 15;
                $rightX = 105;
                $labelWidth = 40;
                $valueWidth = 45;
                $lineHeight = 6;

                // === LEFT COLUMN ===
                // Invoice Number
                $pdf->SetXY($leftX, $currentY);
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->SetTextColor(80, 80, 80);
                $pdf->Cell($labelWidth, $lineHeight, 'Invoice Number:', 0, 0, 'L');
                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Cell($valueWidth, $lineHeight, $invoice->invoice_number ?? 'N/A', 0, 1, 'L');
                $currentY += $lineHeight;

                // PR Number
                $pdf->SetXY($leftX, $currentY);
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->SetTextColor(80, 80, 80);
                $pdf->Cell($labelWidth, $lineHeight, 'PR Number:', 0, 0, 'L');
                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Cell($valueWidth, $lineHeight, $invoice->project->pr_number ?? 'N/A', 0, 1, 'L');
                $currentY += $lineHeight;

                // Project Name
                $pdf->SetXY($leftX, $currentY);
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->SetTextColor(80, 80, 80);
                $pdf->Cell($labelWidth, $lineHeight, 'Project Name:', 0, 0, 'L');
                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetTextColor(0, 0, 0);
                $projectName = $invoice->project->name ?? 'N/A';
                if (strlen($projectName) > 30) {
                    $projectName = substr($projectName, 0, 30) . '...';
                }
                $pdf->Cell($valueWidth, $lineHeight, $projectName, 0, 1, 'L');
                $currentY += $lineHeight;

                // Invoice Value
                $pdf->SetXY($leftX, $currentY);
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->SetTextColor(80, 80, 80);
                $pdf->Cell($labelWidth, $lineHeight, 'Invoice Value:', 0, 0, 'L');
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->SetTextColor(0, 128, 0);
                $pdf->Cell($valueWidth, $lineHeight, $invoice->value ? number_format($invoice->value, 2) . ' SAR' : 'N/A', 0, 1, 'L');

                // === RIGHT COLUMN ===
                $rightY = $contentStartY;

                // PR Invoices Total Value
                $pdf->SetXY($rightX, $rightY);
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->SetTextColor(80, 80, 80);
                $pdf->Cell($labelWidth, $lineHeight, 'PR Invoices Total:', 0, 1, 'L');
                $rightY += $lineHeight;

                $pdf->SetXY($rightX, $rightY);
                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetTextColor(0, 0, 128);
                $totalValueText = number_format($invoice->pr_invoices_total_value, 2);
                if ($invoice->project && $invoice->project->value) {
                    $totalValueText .= ' of ' . number_format($invoice->project->value, 2);
                }
                $pdf->Cell($labelWidth, $lineHeight, $totalValueText . ' SAR', 0, 1, 'L');
                $rightY += $lineHeight;

                // Status
                $pdf->SetXY($rightX, $rightY);
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->SetTextColor(80, 80, 80);
                $pdf->Cell($labelWidth, $lineHeight, 'Status:', 0, 0, 'L');
                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Cell($valueWidth, $lineHeight, $invoice->status ?? 'N/A', 0, 1, 'L');
                $rightY += $lineHeight;

                // Created Date
                $pdf->SetXY($rightX, $rightY);
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->SetTextColor(80, 80, 80);
                $pdf->Cell($labelWidth, $lineHeight, 'Created Date:', 0, 0, 'L');
                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Cell($valueWidth, $lineHeight, $invoice->created_at ? $invoice->created_at->format('d/m/Y') : 'N/A', 0, 1, 'L');

                // Move to next card position (with gap between cards)
                $pdf->SetY($cardY + $cardHeight + $cardGap);
                $cardCount++;
            }

            // Output PDF
            $filename = 'Invoices_Cards_' . date('Y-m-d_His') . '.pdf';

            return response()->streamDownload(function() use ($pdf) {
                echo $pdf->Output('', 'S');
            }, $filename, [
                'Content-Type' => 'application/pdf',
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Invoices PDF export error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error generating PDF: ' . $e->getMessage());
        }
    }

    /**
     * Print view for Invoices
     */
    public function printView()
    {
        $invoices = invoices::with('project:id,pr_number,name,value')->get();
        return view('dashboard.invoice.print', compact('invoices'));
    }

    /**
     * Export Invoices to Excel
     */
    public function exportExcel()
    {
        try {
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\InvoicesExport,
                'Invoices_' . date('Y-m-d') . '.xlsx'
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Invoices Excel Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to export Invoices data to Excel.');
        }
    }
}
