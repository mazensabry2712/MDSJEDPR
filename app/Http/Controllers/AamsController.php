<?php

namespace App\Http\Controllers;

use App\Models\aams;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AamsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $aams = Cache::remember('aams_list', 3600, function () {
            return aams::select('id', 'name', 'email', 'phone')->get();
        });

        return view('dashboard.AMs.index', compact('aams'));
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
            'name' => 'required|string|max:255|unique:aams,name',
            'email' => 'required|email|max:255|unique:aams,email',
            'phone' => 'required|string|max:15',
        ]);

        aams::create($validatedData);

        Cache::forget('aams_list');

        return redirect()->route('am.index')
            ->with('Add', 'AM added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(aams $aams)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(aams $aams)
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
            'name' => 'required|string|max:255|unique:aams,name,' . $id,
            'email' => 'required|email|max:255|unique:aams,email,' . $id,
            'phone' => 'required|string|max:15',
        ]);

        $aams = aams::findOrFail($id);
        $aams->update($validatedData);

        Cache::forget('aams_list');

        return redirect()->route('am.index')
            ->with('edit', 'AM updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;

        $aams = aams::findOrFail($id);
        $aams->delete();

        Cache::forget('aams_list');

        return redirect()->route('am.index')
            ->with('delete', 'AM deleted successfully');
    }

    /**
     * Export AMs to PDF
     */
    public function exportPDF()
    {
        $aams = aams::select('id', 'name', 'email', 'phone')->get();

        // Create PDF - Portrait A4
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document info
        $pdf->SetCreator('MDSJEDPR');
        $pdf->SetTitle('Account Managers - MDSJEDPR');
        $pdf->SetSubject('Account Managers Report');

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
        $pdf->Cell(0, 7, 'Account Managers Management', 0, 1, 'C');

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
        foreach ($aams as $index => $am) {
            $pdf->Cell($w[0], 6, $index + 1, 1, 0, 'C', $fill);
            $pdf->Cell($w[1], 6, $am->name, 1, 0, 'L', $fill);
            $pdf->Cell($w[2], 6, $am->email, 1, 0, 'L', $fill);
            $pdf->Cell($w[3], 6, $am->phone ?? 'N/A', 1, 1, 'C', $fill);
            $fill = !$fill;
        }

        // Output PDF
        $pdf->Output('account_managers_' . date('Y-m-d') . '.pdf', 'I');
    }

    /**
     * Print view for AMs
     */
    public function printView()
    {
        $aams = aams::select('id', 'name', 'email', 'phone')->get();
        return view('dashboard.AMs.print', compact('aams'));
    }

    /**
     * Export AMs to Excel using Maatwebsite/Laravel-Excel
     */
    public function exportExcel()
    {
        try {
            $fileName = 'Account_Managers_' . date('Y-m-d') . '.xlsx';
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\AmsExport, $fileName);
        } catch (Exception $e) {
            Log::error('AMs Excel export error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error exporting Excel: ' . $e->getMessage());
        }
    }
}
