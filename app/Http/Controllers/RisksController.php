<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Risks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;


class RisksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $risks = Cache::remember('risks_list', 3600, function () {
            return Risks::with(['project:id,pr_number,name'])->latest()->get();
        });
        return view('dashboard.Risks.index', compact('risks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    $projects=Project::all();
        return view('dashboard.Risks.create',compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'risk' => 'required|max:255',
            'impact' => 'required',
            'mitigation' => 'nullable|string',
            'owner' => 'nullable|string',
            'status' => 'required',
            'pr_number'=>"required|exists:projects,id"
        ]);

        Risks::create($validatedData);
        Cache::forget('risks_list');
        session()->flash('Add', 'Registration successful');
        return redirect()->route('risks.index');


    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $risks = Risks::with(['project'])->findOrFail($id);
        return view('dashboard.Risks.show', compact('risks'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // جلب جميع المشاريع من قاعدة البيانات
    $projects = Project::all();

        $risks=Risks::find($id);
        return view('dashboard.Risks.edit', compact('risks', 'projects'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $risks=Risks::find($id);
        $validatedData = $request->validate([
            'risk' => 'required|max:255',
            'impact' => 'required',
            'mitigation' => 'nullable|string',
            'owner' => 'nullable|string',
            'status' => 'required',
            'pr_number'=>"required|exists:projects,id"
        ]);

        $risks->update($validatedData);
        Cache::forget('risks_list');
        session()->flash('edit', 'The section has been successfully modified');
        return redirect()->route('risks.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        Risks::findOrFail($id)->delete();
        Cache::forget('risks_list');
        session()->flash('delete', 'Deleted successfully');
        return redirect()->route('risks.index');
    }

    /**
     * Export Risks to PDF
     */
    public function exportPDF()
    {
        $risks = Risks::with(['project:id,pr_number,name'])
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = new \TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('MDSJEDPR');
        $pdf->SetAuthor('MDSJEDPR');
        $pdf->SetTitle('Project Risks');
        $pdf->SetSubject('Risks List');

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
        $pdf->Cell(0, 10, 'Project Risks Management', 0, 1, 'C');

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
        $widths = array(8, 25, 45, 50, 25, 40, 30, 27, 27);

        $pdf->Cell($widths[0], 10, '#', 1, 0, 'C', true);
        $pdf->Cell($widths[1], 10, 'PR Number', 1, 0, 'L', true);
        $pdf->Cell($widths[2], 10, 'Project Name', 1, 0, 'L', true);
        $pdf->Cell($widths[3], 10, 'Risk/Issue', 1, 0, 'L', true);
        $pdf->Cell($widths[4], 10, 'Impact', 1, 0, 'C', true);
        $pdf->Cell($widths[5], 10, 'Mitigation', 1, 0, 'L', true);
        $pdf->Cell($widths[6], 10, 'Owner', 1, 0, 'L', true);
        $pdf->Cell($widths[7], 10, 'Status', 1, 1, 'C', true);

        // Table content
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(0, 0, 0);

        $fill = false;
        foreach ($risks as $index => $item) {
            if ($fill) {
                $pdf->SetFillColor(245, 245, 245);
            } else {
                $pdf->SetFillColor(255, 255, 255);
            }

            // Get current Y position
            $startY = $pdf->GetY();
            $startX = $pdf->GetX();
            
            // Calculate row height based on content
            $riskText = $item->risk ?: 'No description';
            $mitigationText = $item->mitigation ?: 'N/A';
            
            // Estimate heights needed
            $pdf->SetFont('helvetica', '', 8);
            $riskHeight = $pdf->getStringHeight($widths[3], $riskText);
            $mitigationHeight = $pdf->getStringHeight($widths[5], $mitigationText);
            $rowHeight = max(10, $riskHeight, $mitigationHeight) + 2;

            // Draw cells with MultiCell for long text
            // Column 1: #
            $pdf->SetXY($startX, $startY);
            $pdf->MultiCell($widths[0], $rowHeight, ($index + 1), 1, 'C', true, 0);
            
            // Column 2: PR Number
            $pdf->SetXY($startX + $widths[0], $startY);
            $pdf->MultiCell($widths[1], $rowHeight, $item->project->pr_number ?? 'N/A', 1, 'L', true, 0);
            
            // Column 3: Project Name
            $pdf->SetXY($startX + $widths[0] + $widths[1], $startY);
            $projectName = $item->project->name ?? 'N/A';
            if (strlen($projectName) > 30) {
                $projectName = substr($projectName, 0, 27) . '...';
            }
            $pdf->MultiCell($widths[2], $rowHeight, $projectName, 1, 'L', true, 0);
            
            // Column 4: Risk/Issue (with text wrapping)
            $pdf->SetXY($startX + $widths[0] + $widths[1] + $widths[2], $startY);
            $pdf->MultiCell($widths[3], $rowHeight, $riskText, 1, 'L', true, 0);
            
            // Column 5: Impact
            $pdf->SetXY($startX + $widths[0] + $widths[1] + $widths[2] + $widths[3], $startY);
            $impactText = $item->impact == 'low' ? 'Low' : ($item->impact == 'med' ? 'Medium' : 'High');
            $pdf->MultiCell($widths[4], $rowHeight, $impactText, 1, 'C', true, 0);
            
            // Column 6: Mitigation (with text wrapping)
            $pdf->SetXY($startX + $widths[0] + $widths[1] + $widths[2] + $widths[3] + $widths[4], $startY);
            $pdf->MultiCell($widths[5], $rowHeight, $mitigationText, 1, 'L', true, 0);
            
            // Column 7: Owner
            $pdf->SetXY($startX + $widths[0] + $widths[1] + $widths[2] + $widths[3] + $widths[4] + $widths[5], $startY);
            $pdf->MultiCell($widths[6], $rowHeight, $item->owner ?? 'N/A', 1, 'L', true, 0);
            
            // Column 8: Status
            $pdf->SetXY($startX + $widths[0] + $widths[1] + $widths[2] + $widths[3] + $widths[4] + $widths[5] + $widths[6], $startY);
            $statusText = $item->status == 'open' ? 'Open' : 'Closed';
            $pdf->MultiCell($widths[7], $rowHeight, $statusText, 1, 'C', true, 1);

            $fill = !$fill;
        }

        // Output PDF
        $pdf->Output('Risks_' . date('Y-m-d') . '.pdf', 'I');
    }

    /**
     * Print view for Risks
     */
    public function printView()
    {
        $risks = Risks::with(['project:id,pr_number,name'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('dashboard.Risks.print', compact('risks'));
    }
}
