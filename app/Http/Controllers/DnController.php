<?php

namespace App\Http\Controllers;

use App\Models\Dn;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dn = Dn::with('project')->latest()->get();
        return view('dashboard.DN.index', compact('dn'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    $projects = Project::orderBy('pr_number')->get();
        return view('dashboard.DN.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if DN number already exists manually
        $existingDn = Dn::where('dn_number', $request->dn_number)->first();
        if ($existingDn) {
            return back()->withErrors(['dn_number' => 'This DN Number already exists!'])->withInput();
        }

        $validatedData = $request->validate([
            'dn_number' => 'required|string|max:255',
            'pr_number' => 'required|exists:projects,id',
            'dn_copy' => 'nullable|mimes:pdf,jpg,png,jpeg|max:2048',
            'status' => 'required|string|max:500',
        ], [
            'dn_number.required' => 'DN Number is required',
            'pr_number.required' => 'PR Number is required',
            'pr_number.exists' => 'Selected PR Number does not exist',
            'dn_copy.mimes' => 'File must be PDF, JPG, PNG, or JPEG format',
            'dn_copy.max' => 'File size must not exceed 2MB',
            'status.required' => 'Status is required',
        ]);

        if ($request->hasFile('dn_copy')) {
            $file = $request->file('dn_copy');
            $fileName = time() . '_' . $file->getClientOriginalName();

            // حفظ في مجلد storge الأصلي
            $destinationPath = base_path('storge');
            $file->move($destinationPath, $fileName);

            // نسخ إلى مجلد public للعرض
            $publicPath = public_path('storge');
            copy(base_path('storge/' . $fileName), $publicPath . '/' . $fileName);

            $validatedData['dn_copy'] = 'storge/' . $fileName;
        }

        Dn::create($validatedData);

        return redirect()->route('dn.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(Dn $dn)
    {
        return view('dashboard.DN.show', compact('dn'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $dn = Dn::findOrFail($id);
    $projects = Project::orderBy('pr_number')->get();
        return view('dashboard.DN.edit', compact('projects', 'dn'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dn $dn)
    {
        // Check if DN number already exists (excluding current record)
        $existingDn = Dn::where('dn_number', $request->dn_number)
                        ->where('id', '!=', $dn->id)
                        ->first();
        if ($existingDn) {
            return back()->withErrors(['dn_number' => 'This DN Number already exists!'])->withInput();
        }

        $validatedData = $request->validate([
            'dn_number' => 'required|string|max:255',
            'pr_number' => 'required|exists:projects,id',
            'dn_copy' => 'nullable|mimes:pdf,jpg,png,jpeg|max:2048',
            'status' => 'required|string|max:500',
        ], [
            'dn_number.required' => 'DN Number is required',
            'pr_number.required' => 'PR Number is required',
            'pr_number.exists' => 'Selected PR Number does not exist',
            'dn_copy.mimes' => 'File must be PDF, JPG, PNG, or JPEG format',
            'dn_copy.max' => 'File size must not exceed 2MB',
            'status.required' => 'Status is required',
        ]);

        if ($request->hasFile('dn_copy')) {
            // Delete old file if exists from both locations
            $old_dn_copy = $dn->dn_copy;
            if($old_dn_copy && file_exists(base_path($old_dn_copy))) {
                unlink(base_path($old_dn_copy));
            }
            if($old_dn_copy && file_exists(public_path($old_dn_copy))) {
                unlink(public_path($old_dn_copy));
            }

            $file = $request->file('dn_copy');
            $fileName = time() . '_' . $file->getClientOriginalName();

            // حفظ في مجلد storge الأصلي
            $destinationPath = base_path('storge');
            $file->move($destinationPath, $fileName);

            // نسخ إلى مجلد public للعرض
            $publicPath = public_path('storge');
            copy(base_path('storge/' . $fileName), $publicPath . '/' . $fileName);

            $validatedData['dn_copy'] = 'storge/' . $fileName;
        }

        $dn->update($validatedData);

        return redirect()->route('dn.index');
    }    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        $dn = Dn::find($id);

        // Delete associated file if exists from both locations
        if ($dn && $dn->dn_copy) {
            if(file_exists(base_path($dn->dn_copy))) {
                unlink(base_path($dn->dn_copy));
            }
            if(file_exists(public_path($dn->dn_copy))) {
                unlink(public_path($dn->dn_copy));
            }
        }

        $dn->delete();

        session()->flash('delete', 'Delivery Note has been deleted successfully!');
        return redirect()->route('dn.index');
    }

    /**
     * Export DN to PDF
     */
    public function exportPDF()
    {
        $dn = Dn::with('project:id,pr_number,name')->get();

        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('MDSJEDPR');
        $pdf->SetAuthor('MDSJEDPR');
        $pdf->SetTitle('Delivery Notes');
        $pdf->SetSubject('DN List');

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
        $pdf->Cell(0, 10, 'Delivery Notes Management', 0, 1, 'C');

        // Add date
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell(0, 8, 'Generated: ' . date('m/d/Y, g:i:s A'), 0, 1, 'C');
        $pdf->Ln(5);

        // Table header
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetFillColor(103, 126, 234); // #677EEA
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetDrawColor(221, 221, 221);

        // Column widths (total 190mm)
        $widths = array(10, 35, 35, 50, 60);

        $pdf->Cell($widths[0], 10, '#', 1, 0, 'C', true);
        $pdf->Cell($widths[1], 10, 'DN Number', 1, 0, 'L', true);
        $pdf->Cell($widths[2], 10, 'PR Number', 1, 0, 'L', true);
        $pdf->Cell($widths[3], 10, 'Project Name', 1, 0, 'L', true);
        $pdf->Cell($widths[4], 10, 'Status', 1, 1, 'L', true);

        // Table content
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetTextColor(0, 0, 0);

        $fill = false;
        foreach ($dn as $index => $item) {
            if ($fill) {
                $pdf->SetFillColor(245, 245, 245);
            } else {
                $pdf->SetFillColor(255, 255, 255);
            }

            $pdf->Cell($widths[0], 10, ($index + 1), 1, 0, 'C', true);
            $pdf->Cell($widths[1], 10, $item->dn_number, 1, 0, 'L', true);
            $pdf->Cell($widths[2], 10, $item->project->pr_number ?? 'N/A', 1, 0, 'L', true);
            $pdf->Cell($widths[3], 10, $item->project->name ?? 'N/A', 1, 0, 'L', true);
            $pdf->Cell($widths[4], 10, $item->status, 1, 1, 'L', true);

            $fill = !$fill;
        }

        // Output PDF
        $pdf->Output('DN_' . date('Y-m-d') . '.pdf', 'I');
    }

    /**
     * Print view for DN
     */
    public function printView()
    {
        $dn = Dn::with('project:id,pr_number,name')->get();
        return view('dashboard.dn.print', compact('dn'));
    }
}
