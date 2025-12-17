<?php

namespace App\Http\Controllers;

use App\Models\ppms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PpmsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ppms = Cache::remember('ppms_list', 3600, function () {
            return ppms::select('id', 'name', 'email', 'phone')->get();
        });

        return view('dashboard.PMs.index', compact('ppms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:ppms,name',
            'email' => 'required|email|max:255|unique:ppms,email',
            'phone' => 'required|string|max:15',
        ]);

        ppms::create($validatedData);

        Cache::forget('ppms_list');

        return redirect()->route('pm.index')
            ->with('Add', 'PM added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(ppms $ppms)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ppms $ppms)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->id;

        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:ppms,name,' . $id,
            'email' => 'required|email|max:255|unique:ppms,email,' . $id,
            'phone' => 'required|string|max:15',
        ]);

        $ppms = ppms::findOrFail($id);
        $ppms->update($validatedData);

        Cache::forget('ppms_list');

        return redirect()->route('pm.index')
            ->with('edit', 'PM updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;

        $ppms = ppms::findOrFail($id);
        $ppms->delete();

        Cache::forget('ppms_list');

        return redirect()->route('pm.index')
            ->with('delete', 'PM deleted successfully');
    }

    /**
     * Export PMs to PDF
     */
    public function exportPDF()
    {
        $ppms = ppms::select('id', 'name', 'email', 'phone')->get();

        // Create PDF - Portrait A4
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document info
        $pdf->SetCreator('MDSJEDPR');
        $pdf->SetTitle('Project Managers - MDSJEDPR');
        $pdf->SetSubject('Project Managers Report');

        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Set margins
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(true, 15);

        // Add a page
        $pdf->AddPage();

        // System Name
        $pdf->SetFont('helvetica', 'B', 18);
        $pdf->SetTextColor(103, 126, 234);
        $pdf->Cell(0, 8, 'MDSJEDPR', 0, 1, 'C');

        // Title
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 7, 'Project Managers Management', 0, 1, 'C');

        // Date
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell(0, 5, 'Generated: ' . date('m/d/Y, g:i:s A'), 0, 1, 'C');
        $pdf->Ln(3);

        // Table header
        $pdf->SetFillColor(103, 126, 234); // Blue background
        $pdf->SetTextColor(255, 255, 255); // White text
        $pdf->SetFont('helvetica', 'B', 10);

        // Column widths for Portrait A4 (total ~190mm)
        $w = array(10, 60, 70, 50);

        // Header row
        $pdf->Cell($w[0], 7, '#', 1, 0, 'C', true);
        $pdf->Cell($w[1], 7, 'Name', 1, 0, 'C', true);
        $pdf->Cell($w[2], 7, 'Email', 1, 0, 'C', true);
        $pdf->Cell($w[3], 7, 'Phone', 1, 1, 'C', true);

        // Table body
        $pdf->SetFillColor(245, 245, 245); // Light gray
        $pdf->SetTextColor(0, 0, 0); // Black text
        $pdf->SetFont('helvetica', '', 9);

        $fill = false;
        foreach ($ppms as $index => $pm) {
            $pdf->Cell($w[0], 6, $index + 1, 1, 0, 'C', $fill);
            $pdf->Cell($w[1], 6, $pm->name, 1, 0, 'L', $fill);
            $pdf->Cell($w[2], 6, $pm->email, 1, 0, 'L', $fill);
            $pdf->Cell($w[3], 6, $pm->phone ?? 'N/A', 1, 1, 'C', $fill);
            $fill = !$fill;
        }

        // Output PDF
        $pdf->Output('project_managers_' . date('Y-m-d') . '.pdf', 'I');
    }

    /**
     * Print view for PMs
     */
    public function printView()
    {
        $ppms = ppms::select('id', 'name', 'email', 'phone')->get();
        return view('dashboard.PMs.print', compact('ppms'));
    }
}
