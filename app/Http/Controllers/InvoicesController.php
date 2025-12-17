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
        $invoices = invoices::with('project:id,pr_number,name,value')->get();

        $pdf = new \TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('MDSJEDPR');
        $pdf->SetAuthor('MDSJEDPR');
        $pdf->SetTitle('Invoices');
        $pdf->SetSubject('Invoices List');

        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Set margins
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(TRUE, 10);

        // Add a page
        $pdf->AddPage();

        // Add system name at top
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->SetTextColor(103, 126, 234); // #677EEA
        $pdf->Cell(0, 10, 'MDSJEDPR', 0, 1, 'C');

        // Add title
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 10, 'Invoices Management', 0, 1, 'C');

        // Add date
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell(0, 8, 'Generated: ' . date('m/d/Y, g:i:s A'), 0, 1, 'C');
        $pdf->Ln(5);

        // Table header
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetFillColor(103, 126, 234); // #677EEA
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetDrawColor(221, 221, 221);

        // Column widths (total 277mm for Landscape A4)
        $widths = array(10, 30, 50, 35, 35, 40, 40, 37);

        $pdf->Cell($widths[0], 10, '#', 1, 0, 'C', true);
        $pdf->Cell($widths[1], 10, 'PR Number', 1, 0, 'L', true);
        $pdf->Cell($widths[2], 10, 'Project Name', 1, 0, 'L', true);
        $pdf->Cell($widths[3], 10, 'Invoice Number', 1, 0, 'L', true);
        $pdf->Cell($widths[4], 10, 'Value', 1, 0, 'R', true);
        $pdf->Cell($widths[5], 10, 'PR Total Value', 1, 0, 'R', true);
        $pdf->Cell($widths[6], 10, 'Project Value', 1, 0, 'R', true);
        $pdf->Cell($widths[7], 10, 'Status', 1, 1, 'C', true);

        // Table content
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(0, 0, 0);

        $fill = false;
        foreach ($invoices as $index => $invoice) {
            if ($fill) {
                $pdf->SetFillColor(245, 245, 245);
            } else {
                $pdf->SetFillColor(255, 255, 255);
            }

            $pdf->Cell($widths[0], 10, ($index + 1), 1, 0, 'C', true);
            $pdf->Cell($widths[1], 10, $invoice->project->pr_number ?? 'N/A', 1, 0, 'L', true);
            $pdf->Cell($widths[2], 10, $invoice->project->name ?? 'N/A', 1, 0, 'L', true);
            $pdf->Cell($widths[3], 10, $invoice->invoice_number, 1, 0, 'L', true);
            $pdf->Cell($widths[4], 10, number_format($invoice->value, 2) . ' SAR', 1, 0, 'R', true);
            $pdf->Cell($widths[5], 10, number_format($invoice->pr_invoices_total_value, 2), 1, 0, 'R', true);
            $pdf->Cell($widths[6], 10, number_format($invoice->project->value ?? 0, 2), 1, 0, 'R', true);
            $pdf->Cell($widths[7], 10, $invoice->status, 1, 1, 'C', true);

            $fill = !$fill;
        }

        // Output PDF
        $pdf->Output('Invoices_' . date('Y-m-d') . '.pdf', 'I');
    }

    /**
     * Print view for Invoices
     */
    public function printView()
    {
        $invoices = invoices::with('project:id,pr_number,name,value')->get();
        return view('dashboard.invoice.print', compact('invoices'));
    }
}
