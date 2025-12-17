<?php

namespace App\Http\Controllers;

use App\Models\Milestones;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MilestonesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Milestones = Cache::remember('milestones_list', 3600, function () {
            return Milestones::with(['project:id,pr_number,name'])->get();
        });
        return view('dashboard.Milestones.index', compact('Milestones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    $projects = Project::all();


        return view('dashboard.Milestones.create', compact( 'projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'milestone' => 'required|max:255',
            'planned_com' => 'nullable',
            'actual_com' => 'nullable',
            'status' => 'required',
            'comments' => 'nullable|string',
            'pr_number'=>"required|exists:projects,id"
        ]);

        Milestones::create($validatedData);
        Cache::forget('milestones_list');
        session()->flash('Add', 'Registration successful');
        return redirect()->route('milestones.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $milestones = Milestones::with('project')->findOrFail($id);
        return view('dashboard.Milestones.show', compact('milestones'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //

    $projects = Project::all();
        $milestones=Milestones::find($id);

        return view('dashboard.Milestones.edit', compact('milestones', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $milestones = Milestones::findOrFail($id);
        $validatedData = $request->validate([
            'milestone' => 'required|max:255',
            'planned_com' => 'nullable',
            'actual_com' => 'nullable',
            'status' => 'required',
            'comments' => 'nullable|string',
            'pr_number'=>"required|exists:projects,id"
        ]);

        $milestones->update($validatedData);
        Cache::forget('milestones_list');
        session()->flash('edit', 'The section has been successfully modified');
        return redirect()->route('milestones.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        Milestones::findOrFail($id)->delete();
        Cache::forget('milestones_list');
        session()->flash('delete', 'Deleted successfully');
        return redirect()->route('milestones.index');
    }

    /**
     * Export Milestones to PDF
     */
    public function exportPDF()
    {
        $milestones = Milestones::with(['project:id,pr_number,name'])
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = new \TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('MDSJEDPR');
        $pdf->SetAuthor('MDSJEDPR');
        $pdf->SetTitle('Project Milestones');
        $pdf->SetSubject('Milestones List');

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
        $pdf->Cell(0, 10, 'Project Milestones Management', 0, 1, 'C');

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

        // Column widths (total 277mm for Landscape - 9 columns)
        $widths = array(8, 25, 45, 40, 30, 30, 25, 40);

        $pdf->Cell($widths[0], 10, '#', 1, 0, 'C', true);
        $pdf->Cell($widths[1], 10, 'PR Number', 1, 0, 'L', true);
        $pdf->Cell($widths[2], 10, 'Project Name', 1, 0, 'L', true);
        $pdf->Cell($widths[3], 10, 'Milestone', 1, 0, 'L', true);
        $pdf->Cell($widths[4], 10, 'Planned Date', 1, 0, 'C', true);
        $pdf->Cell($widths[5], 10, 'Actual Date', 1, 0, 'C', true);
        $pdf->Cell($widths[6], 10, 'Status', 1, 0, 'C', true);
        $pdf->Cell($widths[7], 10, 'Comments', 1, 1, 'L', true);

        // Table content
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(0, 0, 0);

        $fill = false;
        foreach ($milestones as $index => $item) {
            if ($fill) {
                $pdf->SetFillColor(245, 245, 245);
            } else {
                $pdf->SetFillColor(255, 255, 255);
            }

            // Get current Y position
            $startY = $pdf->GetY();
            $startX = $pdf->GetX();

            // Calculate row height based on content
            $commentsText = $item->comments ?? '-';

            // Estimate heights needed
            $pdf->SetFont('helvetica', '', 8);
            $commentsHeight = $pdf->getStringHeight($widths[7], $commentsText);
            $rowHeight = max(10, $commentsHeight) + 2;

            // Draw cells with MultiCell
            // Column 1: #
            $pdf->SetXY($startX, $startY);
            $pdf->MultiCell($widths[0], $rowHeight, ($index + 1), 1, 'C', true, 0);

            // Column 2: PR Number
            $pdf->SetXY($startX + $widths[0], $startY);
            $pdf->MultiCell($widths[1], $rowHeight, $item->project->pr_number ?? 'N/A', 1, 'L', true, 0);

            // Column 3: Project Name
            $pdf->SetXY($startX + $widths[0] + $widths[1], $startY);
            $projectName = $item->project->name ?? 'N/A';
            if (strlen($projectName) > 35) {
                $projectName = substr($projectName, 0, 32) . '...';
            }
            $pdf->MultiCell($widths[2], $rowHeight, $projectName, 1, 'L', true, 0);

            // Column 4: Milestone
            $pdf->SetXY($startX + $widths[0] + $widths[1] + $widths[2], $startY);
            $pdf->MultiCell($widths[3], $rowHeight, $item->milestone, 1, 'L', true, 0);

            // Column 5: Planned Completion
            $pdf->SetXY($startX + $widths[0] + $widths[1] + $widths[2] + $widths[3], $startY);
            $plannedDate = $item->planned_com ? date('Y-m-d', strtotime($item->planned_com)) : 'N/A';
            $pdf->MultiCell($widths[4], $rowHeight, $plannedDate, 1, 'C', true, 0);

            // Column 6: Actual Completion
            $pdf->SetXY($startX + $widths[0] + $widths[1] + $widths[2] + $widths[3] + $widths[4], $startY);
            $actualDate = $item->actual_com ? date('Y-m-d', strtotime($item->actual_com)) : 'N/A';
            $pdf->MultiCell($widths[5], $rowHeight, $actualDate, 1, 'C', true, 0);

            // Column 7: Status
            $pdf->SetXY($startX + $widths[0] + $widths[1] + $widths[2] + $widths[3] + $widths[4] + $widths[5], $startY);
            $statusText = $item->status == 'on track' ? 'On Track' : 'Delayed';
            $pdf->MultiCell($widths[6], $rowHeight, $statusText, 1, 'C', true, 0);

            // Column 8: Comments (with text wrapping)
            $pdf->SetXY($startX + $widths[0] + $widths[1] + $widths[2] + $widths[3] + $widths[4] + $widths[5] + $widths[6], $startY);
            $pdf->MultiCell($widths[7], $rowHeight, $commentsText, 1, 'L', true, 1);

            $fill = !$fill;
        }

        // Output PDF
        $pdf->Output('Milestones_' . date('Y-m-d') . '.pdf', 'I');
    }

    /**
     * Print view for Milestones
     */
    public function printView()
    {
        $milestones = Milestones::with(['project:id,pr_number,name'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('dashboard.Milestones.print', compact('milestones'));
    }

    /**
     * Export Milestones to Excel
     */
    public function exportExcel()
    {
        try {
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\MilestonesExport,
                'Milestones_' . date('Y-m-d') . '.xlsx'
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Milestones Excel Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to export Milestones data to Excel.');
        }
    }
}
