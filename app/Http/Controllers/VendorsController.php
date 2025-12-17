<?php

namespace App\Http\Controllers;

use App\Models\vendors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class VendorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vendors = Cache::remember('vendors_list', 3600, function () {
            return vendors::select('id', 'vendors', 'vendor_am_details', 'created_at', 'updated_at')->get();
        });

        return view('dashboard.vendors.index', compact('vendors'));
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
            'vendors' => 'required|string|max:255',
            'vendor_am_details' => 'required|string|max:2000',
        ]);

        vendors::create([
            'vendors' => $validatedData['vendors'],
            'vendor_am_details' => $validatedData['vendor_am_details'],
        ]);

        Cache::forget('vendors_list');
        session()->flash('Add', 'Vendor registration successful');
        return redirect('/vendors');
    }

    /**
     * Display the specified resource.
     */
    public function show(vendors $vendors)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(vendors $vendors)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */



    public function update(Request $request)
    {
        $id = $request->id;

        $this->validate(
            $request,
            [
                'vendors' => 'required|max:255',
                'vendor_am_details' => 'required|max:2000',
            ]
        );

        $vendor = vendors::findOrFail($id);
        $vendor->update([
            'vendors' => $request->vendors,
            'vendor_am_details' => $request->vendor_am_details,
        ]);

        Cache::forget('vendors_list');
        session()->flash('success', 'Vendor updated successfully!');
        return redirect('/vendors');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        $vendor = vendors::findOrFail($id);
        $vendor->delete();

        Cache::forget('vendors_list');
        session()->flash('delete', 'Vendor deleted successfully!');
        return redirect('/vendors');
    }

    /**
     * Export Vendors to PDF
     */
    public function exportPDF()
    {
        $vendors = vendors::select('id', 'vendors', 'vendor_am_details')->get();

        // Create PDF - Portrait A4
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document info
        $pdf->SetCreator('MDSJEDPR');
        $pdf->SetTitle('Vendors - MDSJEDPR');
        $pdf->SetSubject('Vendors Report');

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
        $pdf->Cell(0, 7, 'Vendors Management', 0, 1, 'C');

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
        $w = array(10, 60, 120);

        // Header row
        $pdf->Cell($w[0], 7, '#', 1, 0, 'C', true);
        $pdf->Cell($w[1], 7, 'Vendor Name', 1, 0, 'C', true);
        $pdf->Cell($w[2], 7, 'Vendor AM Details', 1, 1, 'C', true);

        // Table body
        $pdf->SetFillColor(245, 245, 245); // Light gray
        $pdf->SetTextColor(0, 0, 0); // Black text
        $pdf->SetFont('helvetica', '', 9);

        $fill = false;
        foreach ($vendors as $index => $vendor) {
            $pdf->Cell($w[0], 6, $index + 1, 1, 0, 'C', $fill);
            $pdf->Cell($w[1], 6, $vendor->vendors, 1, 0, 'L', $fill);
            $pdf->Cell($w[2], 6, $vendor->vendor_am_details ?? 'N/A', 1, 1, 'L', $fill);
            $fill = !$fill;
        }

        // Output PDF
        $pdf->Output('vendors_' . date('Y-m-d') . '.pdf', 'I');
    }

    /**
     * Print view for Vendors
     */
    public function printView()
    {
        $vendors = vendors::select('id', 'vendors', 'vendor_am_details')->get();
        return view('dashboard.vendors.print', compact('vendors'));
    }
}
