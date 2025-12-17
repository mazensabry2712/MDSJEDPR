<?php

namespace App\Http\Controllers;

use App\Models\Pepo;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PepoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // استخدام Eager Loading للسرعة
        $pepo = Pepo::with(['project:id,pr_number,name'])->latest()->get();

        return view('dashboard.PEPO.index', compact('pepo'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::select('id', 'pr_number', 'name')
            ->orderBy('pr_number')
            ->get();

        return view('dashboard.PEPO.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pr_number' => 'required|exists:projects,id',
            'category' => 'nullable|string|max:255',
            'planned_cost' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
        ]);

        Pepo::create($validated);

        session()->flash('Add', 'PEPO added successfully');
        return redirect()->route('epo.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pepo = Pepo::with('project')->findOrFail($id);
        return view('dashboard.PEPO.show', compact('pepo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pepo = Pepo::with('project')->findOrFail($id);
        $projects = Project::select('id', 'pr_number', 'name')
            ->orderBy('pr_number')
            ->get();

        return view('dashboard.PEPO.edit', compact('pepo', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pepo = Pepo::findOrFail($id);

        $validated = $request->validate([
            'pr_number' => 'required|exists:projects,id',
            'category' => 'nullable|string|max:255',
            'planned_cost' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
        ]);

        $pepo->update($validated);

        session()->flash('edit', 'PEPO updated successfully');
        return redirect()->route('epo.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $pepo = Pepo::findOrFail($request->id);
        $pepo->delete();

        session()->flash('delete', 'PEPO deleted successfully');
        return redirect()->route('epo.index');
    }

    /**
     * Export PEPO to PDF
     */
    public function exportPDF()
    {
        $pepo = Pepo::with(['project:id,pr_number,name'])
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = new \TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('MDSJEDPR');
        $pdf->SetAuthor('MDSJEDPR');
        $pdf->SetTitle('Project EPO');
        $pdf->SetSubject('PEPO List');

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
        $pdf->Cell(0, 10, 'Project EPO Management', 0, 1, 'C');

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

        // Column widths (total 247mm for Landscape - 7 columns)
        $widths = array(10, 30, 60, 40, 45, 45, 37);

        $pdf->Cell($widths[0], 10, '#', 1, 0, 'C', true);
        $pdf->Cell($widths[1], 10, 'PR Number', 1, 0, 'L', true);
        $pdf->Cell($widths[2], 10, 'Project Name', 1, 0, 'L', true);
        $pdf->Cell($widths[3], 10, 'Category', 1, 0, 'L', true);
        $pdf->Cell($widths[4], 10, 'Planned Cost', 1, 0, 'R', true);
        $pdf->Cell($widths[5], 10, 'Selling Price', 1, 0, 'R', true);
        $pdf->Cell($widths[6], 10, 'Margin (%)', 1, 1, 'C', true);

        // Table content
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(0, 0, 0);

        $fill = false;
        foreach ($pepo as $index => $item) {
            if ($fill) {
                $pdf->SetFillColor(245, 245, 245);
            } else {
                $pdf->SetFillColor(255, 255, 255);
            }

            $pdf->Cell($widths[0], 10, ($index + 1), 1, 0, 'C', true);
            $pdf->Cell($widths[1], 10, $item->project->pr_number ?? 'N/A', 1, 0, 'L', true);
            $pdf->Cell($widths[2], 10, $item->project->name ?? 'N/A', 1, 0, 'L', true);
            $pdf->Cell($widths[3], 10, $item->category ?? 'N/A', 1, 0, 'L', true);
            $pdf->Cell($widths[4], 10, $item->planned_cost ? number_format($item->planned_cost, 2) : 'N/A', 1, 0, 'R', true);
            $pdf->Cell($widths[5], 10, $item->selling_price ? number_format($item->selling_price, 2) : 'N/A', 1, 0, 'R', true);

            $marginText = 'N/A';
            if ($item->margin !== null) {
                $marginText = number_format($item->margin * 100, 2) . '%';
            }
            $pdf->Cell($widths[6], 10, $marginText, 1, 1, 'C', true);

            $fill = !$fill;
        }

        // Output PDF
        $pdf->Output('PEPO_' . date('Y-m-d') . '.pdf', 'I');
    }

    /**
     * Print view for PEPO
     */
    public function printView()
    {
        $pepo = Pepo::with(['project:id,pr_number,name'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('dashboard.PEPO.print', compact('pepo'));
    }
}
