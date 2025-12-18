<?php

namespace App\Http\Controllers;

use App\Models\Ppos;
use App\Models\Project;
use App\Models\Pepo;
use App\Models\Ds;
use App\Exports\PposExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class PposController extends Controller
{
    /**
     * Export PPOs to Excel
     */
    public function exportExcel()
    {
        try {
            return Excel::download(new PposExport, 'PPOs_' . date('Y-m-d_H-i-s') . '.xlsx');
        } catch (\Exception $e) {
            Log::error('PPOs Excel Export Error: ' . $e->getMessage());
            return redirect()->back()->with('Error', 'Failed to export PPOs to Excel');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // استخدام Cache + Eager Loading للسرعة الفائقة
        $ppos = Cache::remember('ppos_list', 3600, function () {
            return Ppos::with(['project:id,pr_number,name', 'pepo:id,category', 'ds:id,dsname'])
                ->latest()
                ->get();
        });

        return view('dashboard.PPOs.index', compact('ppos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::all();
        $pepos = Pepo::all();
        $dses = Ds::all();

        return view('dashboard.PPOs.create', compact('projects', 'pepos', 'dses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pr_number' => 'required|exists:projects,id',
            'category' => 'required|array',
            'category.*' => 'required|exists:pepos,id',
            'dsname' => 'required|exists:ds,id',
            'po_number' => 'required|string|max:255|unique:ppos,po_number',
            'value' => 'nullable|numeric|min:0',
            'date' => 'nullable|date',
            'status' => 'nullable|string',
            'updates' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $categories = $request->input('category');

            // Create a PPO record for each selected category
            foreach ($categories as $categoryId) {
                Ppos::create([
                    'pr_number' => $request->pr_number,
                    'category' => $categoryId,
                    'dsname' => $request->dsname,
                    'po_number' => $request->po_number,
                    'value' => $request->value,
                    'date' => $request->date,
                    'status' => $request->status,
                    'updates' => $request->updates,
                    'notes' => $request->notes,
                ]);
            }

            // مسح الـ Cache بعد الإضافة
            Cache::forget('ppos_list');

            $categoryCount = count($categories);
            return redirect()->route('ppos.index')
                ->with('Add', "Successfully created {$categoryCount} PPO record(s) for the selected categories");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('Error', 'Failed to create PPO: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ppo = Ppos::with(['project', 'pepo', 'ds'])->findOrFail($id);
        return view('dashboard.PPOs.show', compact('ppo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ppos $ppo)
    {
    $projects = Project::all();
        $pepos = Pepo::all();
        $dses = Ds::all();

        return view('dashboard.PPOs.edit', compact('ppo', 'projects', 'pepos', 'dses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ppos $ppo)
    {
        $validator = Validator::make($request->all(), [
            'pr_number' => 'required|exists:projects,id',
            'category' => 'required|exists:pepos,id',
            'dsname' => 'required|exists:ds,id',
            'po_number' => 'required|string|max:255|unique:ppos,po_number,' . $ppo->id,
            'value' => 'nullable|numeric|min:0',
            'date' => 'nullable|date',
            'status' => 'nullable|string',
            'updates' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Update the current record (single category in edit mode)
            $ppo->update([
                'pr_number' => $request->pr_number,
                'category' => $request->category,
                'dsname' => $request->dsname,
                'po_number' => $request->po_number,
                'value' => $request->value,
                'date' => $request->date,
                'status' => $request->status,
                'updates' => $request->updates,
                'notes' => $request->notes,
            ]);

            // مسح الـ Cache بعد التحديث
            Cache::forget('ppos_list');

            return redirect()->route('ppos.index')
                ->with('Edit', 'PPO has been updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('Error', 'Failed to update PPO: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            $ppo = Ppos::findOrFail($request->id);
            $ppo->delete();

            // مسح الـ Cache بعد الحذف
            Cache::forget('ppos_list');

            return redirect()->route('ppos.index')
                ->with('delete', 'PPO "' . $request->name . '" has been deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('Error', 'Failed to delete PPO: ' . $e->getMessage());
        }
    }

    /**
     * Get categories for a specific project (AJAX)
     */
    public function getCategoriesByProject($pr_number)
    {
        try {
            $categories = Pepo::where('pr_number', $pr_number)
                ->select('id', 'category')
                ->get();

            return response()->json([
                'success' => true,
                'categories' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export PPOs to PDF - Card Layout
     */
    public function exportPDF()
    {
        try {
            $ppos = Ppos::with(['project:id,pr_number,name', 'pepo:id,category', 'ds:id,dsname'])->get();

            $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

            // Set document information
            $pdf->SetCreator('MDS JED Project System');
            $pdf->SetAuthor('Corporate Sites Management System');
            $pdf->SetTitle('PPOs Report');
            $pdf->SetSubject('PPOs Export - Card View');

            // Remove default header/footer
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            // Set margins for A4
            $pdf->SetMargins(10, 10, 10);
            $pdf->SetAutoPageBreak(false);

            // Set font
            $pdf->SetFont('helvetica', '', 9);

            $cardCount = 0;
            $cardsPerPage = 4;
            $cardHeight = 64;
            $cardGap = 2;

            foreach ($ppos as $index => $ppo) {
                // Add new page for every set of cards
                if ($cardCount % $cardsPerPage == 0) {
                    $pdf->AddPage('P');

                    // Page Header
                    $pdf->SetFont('helvetica', 'B', 14);
                    $pdf->SetTextColor(103, 126, 234);
                    $pdf->Cell(0, 8, 'PPOs Report', 0, 1, 'C');

                    $pdf->SetFont('helvetica', '', 8);
                    $pdf->SetTextColor(120, 120, 120);
                    $pdf->Cell(0, 5, 'Generated: ' . date('d/m/Y g:i A'), 0, 1, 'C');
                    $pdf->Ln(2);

                    // Add footer for each page
                    $pdf->SetY(-10);
                    $pdf->SetFont('helvetica', 'B', 9);
                    $pdf->SetTextColor(103, 126, 234);
                    $pdf->Cell(0, 8, 'MDSJEDPR', 0, 0, 'C');

                    // Reset Y position for content
                    $pdf->SetY(25);
                }

                // Card container
                $cardY = $pdf->GetY();

                // Card border and background
                $pdf->SetFillColor(255, 255, 255);
                $pdf->SetDrawColor(103, 126, 234);
                $pdf->SetLineWidth(0.5);
                $pdf->RoundedRect(10, $cardY, 190, $cardHeight, 3, '1111', 'DF');

                // Card header with PO number
                $pdf->SetFillColor(103, 126, 234);
                $pdf->SetTextColor(255, 255, 255);
                $pdf->RoundedRect(10, $cardY, 190, 8, 3, '1100', 'F');

                $pdf->SetXY(12, $cardY + 1.5);
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->Cell(90, 5, 'PO: ' . ($ppo->po_number ?? 'N/A'), 0, 0, 'L');

                $pdf->SetFont('helvetica', '', 7);
                $pdf->Cell(96, 5, 'PPO #' . ($index + 1), 0, 1, 'R');

                // Content area starts below the header
                $contentStartY = $cardY + 10;
                $currentY = $contentStartY;

                // Column settings
                $leftX = 15;
                $rightX = 105;
                $labelWidth = 40;
                $valueWidth = 45;
                $lineHeight = 6;

                // Get all categories for this PO Number
                $allCategories = Ppos::where('po_number', $ppo->po_number)
                    ->with('pepo:id,category')
                    ->get()
                    ->pluck('pepo.category')
                    ->filter()
                    ->unique()
                    ->implode(', ');

                // === LEFT COLUMN ===
                // PR Number
                $pdf->SetXY($leftX, $currentY);
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->SetTextColor(80, 80, 80);
                $pdf->Cell($labelWidth, $lineHeight, 'PR Number:', 0, 0, 'L');
                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Cell($valueWidth, $lineHeight, $ppo->project->pr_number ?? 'N/A', 0, 1, 'L');
                $currentY += $lineHeight;

                // Project Name
                $pdf->SetXY($leftX, $currentY);
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->SetTextColor(80, 80, 80);
                $pdf->Cell($labelWidth, $lineHeight, 'Project Name:', 0, 0, 'L');
                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetTextColor(0, 0, 0);
                $projectName = $ppo->project->name ?? 'N/A';
                if (strlen($projectName) > 30) {
                    $projectName = substr($projectName, 0, 30) . '...';
                }
                $pdf->Cell($valueWidth, $lineHeight, $projectName, 0, 1, 'L');
                $currentY += $lineHeight;

                // Category
                $pdf->SetXY($leftX, $currentY);
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->SetTextColor(80, 80, 80);
                $pdf->Cell($labelWidth, $lineHeight, 'Category:', 0, 0, 'L');
                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetTextColor(0, 0, 0);
                $category = $allCategories ?: 'N/A';
                if (strlen($category) > 30) {
                    $category = substr($category, 0, 30) . '...';
                }
                $pdf->Cell($valueWidth, $lineHeight, $category, 0, 1, 'L');
                $currentY += $lineHeight;

                // Supplier Name
                $pdf->SetXY($leftX, $currentY);
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->SetTextColor(80, 80, 80);
                $pdf->Cell($labelWidth, $lineHeight, 'Supplier:', 0, 0, 'L');
                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Cell($valueWidth, $lineHeight, $ppo->ds->dsname ?? 'N/A', 0, 1, 'L');
                $currentY += $lineHeight;

                // Updates (MultiCell with word wrap)
                $pdf->SetXY($leftX, $currentY);
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->SetTextColor(80, 80, 80);
                $pdf->Cell($labelWidth, $lineHeight, 'Updates:', 0, 1, 'L');

                // Move to value position (same X as label start)
                $pdf->SetXY($leftX, $pdf->GetY());
                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetTextColor(0, 0, 0);
                $updates = $ppo->updates ?? 'No updates';

                // MultiCell for wrapping text - width to fit ~23 chars per line
                $pdf->MultiCell(85, 5, $updates, 0, 'L', false, 1, $leftX, $pdf->GetY());
                $currentY = $pdf->GetY() + 1;

                // === RIGHT COLUMN ===
                $rightY = $contentStartY;

                // PO Number
                $pdf->SetXY($rightX, $rightY);
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->SetTextColor(80, 80, 80);
                $pdf->Cell($labelWidth, $lineHeight, 'PO Number:', 0, 0, 'L');
                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Cell($valueWidth, $lineHeight, $ppo->po_number ?? 'N/A', 0, 1, 'L');
                $rightY += $lineHeight;

                // Value
                $pdf->SetXY($rightX, $rightY);
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->SetTextColor(80, 80, 80);
                $pdf->Cell($labelWidth, $lineHeight, 'Value:', 0, 0, 'L');
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->SetTextColor(0, 128, 0);
                $pdf->Cell($valueWidth, $lineHeight, $ppo->value ? '$' . number_format($ppo->value, 2) : 'N/A', 0, 1, 'L');
                $rightY += $lineHeight;

                // Date
                $pdf->SetXY($rightX, $rightY);
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->SetTextColor(80, 80, 80);
                $pdf->Cell($labelWidth, $lineHeight, 'Date:', 0, 0, 'L');
                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Cell($valueWidth, $lineHeight, $ppo->date ? $ppo->date->format('d/m/Y') : 'N/A', 0, 1, 'L');
                $rightY += $lineHeight;

                // Status
                $pdf->SetXY($rightX, $rightY);
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->SetTextColor(80, 80, 80);
                $pdf->Cell($labelWidth, $lineHeight, 'Status:', 0, 0, 'L');
                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetTextColor(0, 0, 0);
                $status = $ppo->status ?? 'N/A';
                if (strlen($status) > 20) {
                    $status = substr($status, 0, 20) . '...';
                }
                $pdf->Cell($valueWidth, $lineHeight, $status, 0, 1, 'L');
                $rightY += $lineHeight;

                // Notes
                $pdf->SetXY($rightX, $rightY);
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->SetTextColor(80, 80, 80);
                $pdf->Cell($labelWidth, $lineHeight, 'Notes:', 0, 0, 'L');
                $pdf->SetFont('helvetica', '', 8);
                $pdf->SetTextColor(0, 0, 0);
                $notes = $ppo->notes ?? 'No notes';
                if (strlen($notes) > 20) {
                    $notes = substr($notes, 0, 20) . '...';
                }
                $pdf->Cell($valueWidth, $lineHeight, $notes, 0, 1, 'L');

                // Move to next card position (with gap between cards)
                $pdf->SetY($cardY + $cardHeight + $cardGap);
                $cardCount++;
            }

            // Output PDF
            $filename = 'PPOs_Cards_' . date('Y-m-d_His') . '.pdf';

            return response()->streamDownload(function() use ($pdf) {
                echo $pdf->Output('', 'S');
            }, $filename, [
                'Content-Type' => 'application/pdf',
            ]);

        } catch (\Exception $e) {
            Log::error('PPOs PDF export error: ' . $e->getMessage());
            return redirect()->back()->with('Error', 'Error generating PDF: ' . $e->getMessage());
        }
    }

    /**
     * Print view for PPOs
     */
    public function printView()
    {
        $ppos = Ppos::with(['project:id,pr_number,name', 'pepo:id,category', 'ds:id,dsname'])
            ->get();
        return view('dashboard.PPOs.print', compact('ppos'));
    }
}
