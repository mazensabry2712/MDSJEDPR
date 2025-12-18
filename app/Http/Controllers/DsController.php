<?php

namespace App\Http\Controllers;

use App\Models\Ds;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ds = Cache::remember('ds_list', 3600, function () {
            return Ds::select('id', 'dsname', 'ds_contact', 'created_at', 'updated_at')->get();
        });

        return view('dashboard.ds.index', compact('ds'));
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
            'dsname' => 'required|string|max:255|unique:ds,dsname',
            'ds_contact' => 'required|string|max:2000',
        ]);

        Ds::create([
            'dsname' => $validatedData['dsname'],
            'ds_contact' => $validatedData['ds_contact'],
        ]);

        Cache::forget('ds_list');
        session()->flash('Add', 'Distributor/Supplier registration successful');
        return redirect('/ds');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ds $ds)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ds $ds)
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
                'dsname' => 'required|max:255|unique:ds,dsname,' . $id,
                'ds_contact' => 'required|max:2000',
            ]
        );

        $ds = Ds::findOrFail($id);
        $ds->update([
            'dsname' => $request->dsname,
            'ds_contact' => $request->ds_contact,
        ]);

        Cache::forget('ds_list');
        session()->flash('edit', 'Distributor/Supplier updated successfully!');
        return redirect('/ds');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        $ds = Ds::findOrFail($id);
        $ds->delete();

        Cache::forget('ds_list');
        session()->flash('delete', 'Distributor/Supplier deleted successfully');
        return redirect('/ds');
    }

    /**
     * Export DS to PDF
     */
    public function exportPDF()
    {
        $ds = Ds::select('id', 'dsname', 'ds_contact')->get();

        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('MDSJEDPR');
        $pdf->SetAuthor('MDSJEDPR');
        $pdf->SetTitle('Distributors/Suppliers');
        $pdf->SetSubject('DS List');

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
        $pdf->Cell(0, 10, 'Distributors/Suppliers Management', 0, 1, 'C');

        // Add date
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell(0, 8, 'Generated: ' . date('m/d/Y, g:i:s A'), 0, 1, 'C');
        $pdf->Ln(5);

        // Table header
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->SetFillColor(103, 126, 234); // #677EEA
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetDrawColor(221, 221, 221);

        // Column widths (total 190mm)
        $widths = array(10, 60, 120);

        $pdf->Cell($widths[0], 10, '#', 1, 0, 'C', true);
        $pdf->Cell($widths[1], 10, 'DS Name', 1, 0, 'L', true);
        $pdf->Cell($widths[2], 10, 'DS Contact', 1, 1, 'L', true);

        // Table content
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetTextColor(0, 0, 0);

        $fill = false;
        foreach ($ds as $index => $item) {
            if ($fill) {
                $pdf->SetFillColor(245, 245, 245);
            } else {
                $pdf->SetFillColor(255, 255, 255);
            }

            $pdf->Cell($widths[0], 10, ($index + 1), 1, 0, 'C', true);
            $pdf->Cell($widths[1], 10, $item->dsname, 1, 0, 'L', true);
            $pdf->Cell($widths[2], 10, $item->ds_contact, 1, 1, 'L', true);

            $fill = !$fill;
        }

        // Output PDF
        $pdf->Output('DS_' . date('Y-m-d') . '.pdf', 'I');
    }

    /**
     * Print view for DS
     */
    public function printView()
    {
        $ds = Ds::select('id', 'dsname', 'ds_contact')->get();
        return view('dashboard.ds.print', compact('ds'));
    }

    /**
     * Export DS to Excel
     */
    public function exportExcel()
    {
        try {
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\DsExport,
                'Delivery_Specialists_' . date('Y-m-d') . '.xlsx'
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('DS Excel Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to export DS data to Excel.');
        }
    }
}
