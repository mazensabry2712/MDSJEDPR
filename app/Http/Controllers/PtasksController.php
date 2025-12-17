<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Ptasks;
use App\Exports\PtasksExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class PtasksController extends Controller
{
    /**
     * Export PTasks to Excel
     */
    public function exportExcel()
    {
        try {
            return Excel::download(new PtasksExport, 'Project_Tasks_' . date('Y-m-d_H-i-s') . '.xlsx');
        } catch (\Exception $e) {
            Log::error('PTasks Excel Export Error: ' . $e->getMessage());
            return redirect()->back()->with('Error', 'Failed to export Project Tasks to Excel');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ptasks = Cache::remember('ptasks_list', 3600, function () {
            return Ptasks::with(['project:id,pr_number,name'])
                ->latest()
                ->get();
        });

        return view('dashboard.PTasks.index', compact('ptasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    $projects=Project::all();
       // print_r($projects);
        return view('dashboard.PTasks.create',compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        $validatedData = $request->validate([
            'expected_com_date' => 'nullable',
            'task_date' => 'required',
            'details' => 'nullable|string',
            'assigned' => 'nullable|string',
            'status' => 'required',
            'pr_number'=>"required|exists:projects,id"
        ]);


        Ptasks::create($validatedData);
        Cache::forget('ptasks_list');

        session()->flash('Add', 'Task added successfully');
        return redirect()->route('ptasks.index');


    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ptask = Ptasks::with(['project'])->findOrFail($id);
        return view('dashboard.PTasks.show', compact('ptask'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    $projects = Project::all();

        $ptasks=Ptasks::find($id);
        return view('dashboard.PTasks.edit', compact('ptasks', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $ptasks=Ptasks::find($id);
        $validatedData = $request->validate([
            'expected_com_date' => 'nullable',
            'task_date' => 'required',
            'details' => 'nullable|string',
            'assigned' => 'nullable|string',
            'status' => 'required',
            'pr_number'=>"required|exists:projects,id"
        ]);

        $ptasks->update($validatedData);
        Cache::forget('ptasks_list');

        session()->flash('edit', 'Task updated successfully');
        return redirect()->route('ptasks.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        Ptasks::findOrFail($id)->delete();
        Cache::forget('ptasks_list');

        session()->flash('delete', 'Task deleted successfully');
        return redirect()->route('ptasks.index');
    }

    /**
     * Export Project Tasks to PDF
     */
    public function exportPDF()
    {
        $ptasks = Ptasks::with(['project:id,pr_number,name'])
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = new \TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('MDSJEDPR');
        $pdf->SetAuthor('MDSJEDPR');
        $pdf->SetTitle('Project Tasks');
        $pdf->SetSubject('Project Tasks List');

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
        $pdf->Cell(0, 10, 'Project Tasks Management', 0, 1, 'C');

        // Add date
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell(0, 8, 'Generated: ' . date('m/d/Y, g:i:s A'), 0, 1, 'C');
        $pdf->Ln(5);

        // Table header
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetFillColor(103, 126, 234); // #677EEA
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetDrawColor(221, 221, 221);

        // Column widths (total 277mm for Landscape)
        $widths = array(8, 25, 55, 35, 55, 35, 32, 32);

        $pdf->Cell($widths[0], 10, '#', 1, 0, 'C', true);
        $pdf->Cell($widths[1], 10, 'PR Number', 1, 0, 'L', true);
        $pdf->Cell($widths[2], 10, 'Project Name', 1, 0, 'L', true);
        $pdf->Cell($widths[3], 10, 'Task Date', 1, 0, 'C', true);
        $pdf->Cell($widths[4], 10, 'Task Details', 1, 0, 'L', true);
        $pdf->Cell($widths[5], 10, 'Assigned To', 1, 0, 'L', true);
        $pdf->Cell($widths[6], 10, 'Expected Date', 1, 0, 'C', true);
        $pdf->Cell($widths[7], 10, 'Status', 1, 1, 'L', true);

        // Table content
        $pdf->SetFont('helvetica', '', 7);
        $pdf->SetTextColor(0, 0, 0);

        $fill = false;
        foreach ($ptasks as $index => $item) {
            if ($fill) {
                $pdf->SetFillColor(245, 245, 245);
            } else {
                $pdf->SetFillColor(255, 255, 255);
            }

            $pdf->Cell($widths[0], 10, ($index + 1), 1, 0, 'C', true);
            $pdf->Cell($widths[1], 10, $item->project->pr_number ?? 'N/A', 1, 0, 'L', true);
            $pdf->Cell($widths[2], 10, $item->project->name ?? 'N/A', 1, 0, 'L', true);
            $pdf->Cell($widths[3], 10, $item->task_date ? \Carbon\Carbon::parse($item->task_date)->format('d/m/Y') : 'N/A', 1, 0, 'C', true);
            $pdf->Cell($widths[4], 10, $item->details ?? 'N/A', 1, 0, 'L', true);
            $pdf->Cell($widths[5], 10, $item->assigned ?? 'N/A', 1, 0, 'L', true);
            $pdf->Cell($widths[6], 10, $item->expected_com_date ? \Carbon\Carbon::parse($item->expected_com_date)->format('d/m/Y') : 'N/A', 1, 0, 'C', true);
            $pdf->Cell($widths[7], 10, $item->status ?? 'N/A', 1, 1, 'L', true);

            $fill = !$fill;
        }

        // Output PDF
        $pdf->Output('ProjectTasks_' . date('Y-m-d') . '.pdf', 'I');
    }

    /**
     * Print view for Project Tasks
     */
    public function printView()
    {
        $ptasks = Ptasks::with(['project:id,pr_number,name'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('dashboard.PTasks.print', compact('ptasks'));
    }
}
