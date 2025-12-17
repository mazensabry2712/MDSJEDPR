<?php

namespace App\Http\Controllers;

use App\Models\Coc;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class CocController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // استخدام Cache + Eager Loading للسرعة الفائقة
        $coc = Cache::remember('coc_list', 3600, function () {
            return Coc::with('project:id,name,pr_number')->latest()->get();
        });

        return view('dashboard.CoC.index', compact('coc'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    $projects=Project::all();
        return view('dashboard.CoC.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'coc_copy' => 'required|mimes:pdf,docx,doc,jpg,jpeg,png,gif|max:10240',
            'pr_number' => "required|exists:projects,id"
        ]);

        if($request->hasFile('coc_copy')){
            $file = $request->file('coc_copy');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('../storge'), $filename);
            $validatedData['coc_copy'] = $filename;
        }

        Coc::create($validatedData);

        // مسح الـ Cache بعد الإضافة
        Cache::forget('coc_list');

        session()->flash('Add', 'Certificate of Compliance added successfully');
        return redirect('/coc');

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $coc = Coc::with('project:id,pr_number,name')->findOrFail($id);
        return view('dashboard.CoC.show', compact('coc'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
    $projects = Project::all();
        $coc=Coc::find($id);

        return view('dashboard.CoC.edit', compact('coc', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $coc = Coc::findOrFail($id);

        $validatedData = $request->validate([
            'coc_copy' => 'nullable|mimes:pdf,docx,doc,jpg,jpeg,png,gif|max:10240',
            'pr_number' => "required|exists:projects,id"
        ]);

        if($request->hasFile('coc_copy')){
            // حذف الملف القديم
            $oldFile = public_path('../storge/' . $coc->coc_copy);
            if(file_exists($oldFile)){
                unlink($oldFile);
            }

            // رفع الملف الجديد
            $file = $request->file('coc_copy');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('../storge'), $filename);
            $validatedData['coc_copy'] = $filename;
        }

        $coc->update($validatedData);

        // مسح الـ Cache بعد التعديل
        Cache::forget('coc_list');

        session()->flash('edit', 'Certificate of Compliance updated successfully');
        return redirect('/coc');


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id=$request->id;
        $coc=Coc::find($id);

        // حذف الملف من مجلد storge
        $filePath = public_path('../storge/' . $coc->coc_copy);
        if(file_exists($filePath)){
            unlink($filePath);
        }

        $coc->delete();

        // مسح الـ Cache بعد الحذف
        Cache::forget('coc_list');

        session()->flash('delete', 'Deleted successfully');

        return redirect('/coc');
    }

    /**
     * Export CoC to PDF
     */
    public function exportPDF()
    {
        $coc = Coc::with('project:id,pr_number,name')->get();

        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('MDSJEDPR');
        $pdf->SetAuthor('MDSJEDPR');
        $pdf->SetTitle('Certificate of Compliance');
        $pdf->SetSubject('CoC List');

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
        $pdf->Cell(0, 10, 'Certificate of Compliance Management', 0, 1, 'C');

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
        $widths = array(10, 35, 70, 40, 35);

        $pdf->Cell($widths[0], 10, '#', 1, 0, 'C', true);
        $pdf->Cell($widths[1], 10, 'PR Number', 1, 0, 'L', true);
        $pdf->Cell($widths[2], 10, 'Project Name', 1, 0, 'L', true);
        $pdf->Cell($widths[3], 10, 'Upload Date', 1, 0, 'C', true);
        $pdf->Cell($widths[4], 10, 'Upload Time', 1, 1, 'C', true);

        // Table content
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetTextColor(0, 0, 0);

        $fill = false;
        foreach ($coc as $index => $item) {
            if ($fill) {
                $pdf->SetFillColor(245, 245, 245);
            } else {
                $pdf->SetFillColor(255, 255, 255);
            }

            $pdf->Cell($widths[0], 10, ($index + 1), 1, 0, 'C', true);
            $pdf->Cell($widths[1], 10, $item->project->pr_number ?? 'N/A', 1, 0, 'L', true);
            $pdf->Cell($widths[2], 10, $item->project->name ?? 'N/A', 1, 0, 'L', true);
            $pdf->Cell($widths[3], 10, $item->created_at->format('Y-m-d'), 1, 0, 'C', true);
            $pdf->Cell($widths[4], 10, $item->created_at->format('h:i A'), 1, 1, 'C', true);

            $fill = !$fill;
        }

        // Output PDF
        $pdf->Output('CoC_' . date('Y-m-d') . '.pdf', 'I');
    }

    /**
     * Print view for CoC
     */
    public function printView()
    {
        $coc = Coc::with('project:id,pr_number,name')->get();
        return view('dashboard.coc.print', compact('coc'));
    }

    /**
     * Export CoC to Excel
     */
    public function exportExcel()
    {
        try {
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\CocExport,
                'Certificate_of_Compliance_' . date('Y-m-d') . '.xlsx'
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('CoC Excel Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to export CoC data to Excel.');
        }
    }
}
