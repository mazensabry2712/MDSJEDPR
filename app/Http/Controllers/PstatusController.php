<?php

namespace App\Http\Controllers;

use App\Models\ppms;
use App\Models\Project;
use App\Models\Pstatus;
use App\Exports\PstatusExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class PstatusController extends Controller
{
    /**
     * Export PStatus to Excel
     */
    public function exportExcel()
    {
        try {
            return Excel::download(new PstatusExport, 'Project_Status_' . date('Y-m-d_H-i-s') . '.xlsx');
        } catch (\Exception $e) {
            Log::error('PStatus Excel Export Error: ' . $e->getMessage());
            return redirect()->back()->with('Error', 'Failed to export Project Status to Excel');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pstatus = Cache::remember('pstatus_list', 3600, function () {
            return Pstatus::with(['project:id,pr_number,name', 'ppm:id,name'])
                ->latest()
                ->get();
        });

        return view('dashboard.PStatus.index', compact('pstatus'));
    }

    /**
     * Show the form for creating a new resource.
     */
     public function create()
    {
        // يجب جلب علاقة ppms مع المشاريع لكي تتمكن من الوصول لاسم مدير المشروع
        $projects = Project::with('ppms')->get(); // ⬅️ تم التعديل هنا
        $ppms = ppms::all();
        return view('dashboard.PStatus.create', compact('projects', 'ppms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pr_number' => 'nullable|exists:projects,id',
            'date_time' => 'nullable|date',
            'pm_name' => 'nullable|exists:ppms,id',
            'status' => 'nullable|string',
            'actual_completion' => 'nullable|numeric|min:0|max:100',
            'expected_completion' => 'nullable|date',
            'date_pending_cost_orders' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        Pstatus::create($validated);
        Cache::forget('pstatus_list');

        session()->flash('Add', 'Project Status added successfully');
        return redirect()->route('pstatus.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pstatus = Pstatus::with(['project', 'ppm'])->findOrFail($id);
        return view('dashboard.PStatus.show', compact('pstatus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    $projects=Project::all();
        $pstatus=Pstatus::find($id);
        $ppms=ppms::all();
        return view('dashboard.PStatus.edit',compact('projects','pstatus','ppms'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pstatus = Pstatus::findOrFail($id);

        $validated = $request->validate([
            'pr_number' => 'required|exists:projects,id',
            'date_time' => 'nullable|date',
            'pm_name' => 'required|exists:ppms,id',
            'status' => 'nullable|string',
            'actual_completion' => 'nullable|numeric|min:0|max:100',
            'expected_completion' => 'nullable|date',
            'date_pending_cost_orders' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $pstatus->update($validated);
        Cache::forget('pstatus_list');

        session()->flash('edit', 'Project Status updated successfully');
        return redirect()->route('pstatus.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        Pstatus::findOrFail($id)->delete();
        Cache::forget('pstatus_list');

        session()->flash('delete', 'Project Status deleted successfully');
        return redirect()->route('pstatus.index');
    }

    /**
     * Export Project Status to PDF
     */
    public function exportPDF()
    {
        $pstatus = Pstatus::with(['project:id,pr_number,name', 'ppm:id,name'])
            ->orderBy('date_time', 'desc')
            ->get();

        $pdf = new \TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('MDSJEDPR');
        $pdf->SetAuthor('MDSJEDPR');
        $pdf->SetTitle('Project Status');
        $pdf->SetSubject('Project Status List');

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
        $pdf->Cell(0, 10, 'Project Status Management', 0, 1, 'C');

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
        $widths = array(8, 20, 40, 28, 22, 25, 18, 25, 35, 36);

        $pdf->Cell($widths[0], 10, '#', 1, 0, 'C', true);
        $pdf->Cell($widths[1], 10, 'PR Number', 1, 0, 'L', true);
        $pdf->Cell($widths[2], 10, 'Project Name', 1, 0, 'L', true);
        $pdf->Cell($widths[3], 10, 'Date & Time', 1, 0, 'C', true);
        $pdf->Cell($widths[4], 10, 'PM Name', 1, 0, 'L', true);
        $pdf->Cell($widths[5], 10, 'Status', 1, 0, 'L', true);
        $pdf->Cell($widths[6], 10, 'Actual %', 1, 0, 'C', true);
        $pdf->Cell($widths[7], 10, 'Expected', 1, 0, 'C', true);
        $pdf->Cell($widths[8], 10, 'Pending Cost', 1, 0, 'L', true);
        $pdf->Cell($widths[9], 10, 'Notes', 1, 1, 'L', true);

        // Table content
        $pdf->SetFont('helvetica', '', 7);
        $pdf->SetTextColor(0, 0, 0);

        $fill = false;
        foreach ($pstatus as $index => $item) {
            if ($fill) {
                $pdf->SetFillColor(245, 245, 245);
            } else {
                $pdf->SetFillColor(255, 255, 255);
            }

            // Calculate row height based on notes content
            $notes = $item->notes ?? 'N/A';

            // Use TCPDF's getStringHeight to accurately calculate text height
            $pdf->SetFont('helvetica', '', 7);
            $notesHeight = $pdf->getStringHeight($widths[9] - 2, $notes); // -2 for cell padding
            $rowHeight = max(10, $notesHeight + 2); // Add padding

            // Store current Y position
            $startY = $pdf->GetY();
            $startX = 10; // Left margin

            // Draw all cells with same calculated height
            $pdf->Cell($widths[0], $rowHeight, ($index + 1), 1, 0, 'C', true);
            $pdf->Cell($widths[1], $rowHeight, $item->project->pr_number ?? 'N/A', 1, 0, 'L', true);
            $pdf->Cell($widths[2], $rowHeight, $item->project->name ?? 'N/A', 1, 0, 'L', true);
            $pdf->Cell($widths[3], $rowHeight, $item->date_time ? \Carbon\Carbon::parse($item->date_time)->format('d/m/Y H:i') : 'N/A', 1, 0, 'C', true);
            $pdf->Cell($widths[4], $rowHeight, $item->ppm->name ?? 'N/A', 1, 0, 'L', true);
            $pdf->Cell($widths[5], $rowHeight, $item->status ?? 'N/A', 1, 0, 'L', true);
            $pdf->Cell($widths[6], $rowHeight, $item->actual_completion ? number_format($item->actual_completion, 2) . '%' : 'N/A', 1, 0, 'C', true);
            $pdf->Cell($widths[7], $rowHeight, $item->expected_completion ? \Carbon\Carbon::parse($item->expected_completion)->format('d/m/Y') : 'N/A', 1, 0, 'C', true);
            $pdf->Cell($widths[8], $rowHeight, $item->date_pending_cost_orders ?? 'N/A', 1, 0, 'L', true);

            // Calculate X position for Notes column
            $notesX = $startX + array_sum(array_slice($widths, 0, 9));

            // Draw empty bordered cell for Notes with proper background
            $pdf->Cell($widths[9], $rowHeight, '', 1, 1, 'C', true);

            // Calculate vertical centering
            $verticalPadding = ($rowHeight - $notesHeight) / 2;
            $notesY = $startY + max(0.5, $verticalPadding);

            // Write text inside Notes cell (no border, transparent background)
            $pdf->SetXY($notesX + 1, $notesY); // +1 for left padding
            $pdf->SetFillColor(255, 255, 255, 0); // Transparent
            $pdf->MultiCell($widths[9] - 2, $rowHeight, $notes, 0, 'C', false, 0);

            // Move to next row
            $pdf->SetXY($startX, $startY + $rowHeight);

            $fill = !$fill;
        }

        // Output PDF
        $pdf->Output('ProjectStatus_' . date('Y-m-d') . '.pdf', 'I');
    }

    /**
     * Print view for Project Status
     */
    public function printView()
    {
        $pstatus = Pstatus::with(['project:id,pr_number,name', 'ppm:id,name'])
            ->orderBy('date_time', 'desc')
            ->get();
        return view('dashboard.PStatus.print', compact('pstatus'));
    }
}
