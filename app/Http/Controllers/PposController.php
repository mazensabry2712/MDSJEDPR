<?php

namespace App\Http\Controllers;

use App\Models\Ppos;
use App\Models\Project;
use App\Models\Pepo;
use App\Models\Ds;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class PposController extends Controller
{
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
            'category' => 'required|array',
            'category.*' => 'required|exists:pepos,id',
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
            $categories = $request->input('category');

            // Update the current record with the first category
            $ppo->update([
                'pr_number' => $request->pr_number,
                'category' => $categories[0], // First category
                'dsname' => $request->dsname,
                'po_number' => $request->po_number,
                'value' => $request->value,
                'date' => $request->date,
                'status' => $request->status,
                'updates' => $request->updates,
                'notes' => $request->notes,
            ]);

            // Create additional records for remaining categories
            for ($i = 1; $i < count($categories); $i++) {
                Ppos::create([
                    'pr_number' => $request->pr_number,
                    'category' => $categories[$i],
                    'dsname' => $request->dsname,
                    'po_number' => $request->po_number,
                    'value' => $request->value,
                    'date' => $request->date,
                    'status' => $request->status,
                    'updates' => $request->updates,
                    'notes' => $request->notes,
                ]);
            }

            // مسح الـ Cache بعد التحديث
            Cache::forget('ppos_list');

            $totalRecords = count($categories);
            $newRecords = $totalRecords - 1;

            $message = 'PPO has been updated successfully';
            if ($newRecords > 0) {
                $message .= " and {$newRecords} additional record(s) created for other categories";
            }

            return redirect()->route('ppos.index')
                ->with('Edit', $message);
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
     * Export PPOs to PDF
     */
    public function exportPDF()
    {
        $ppos = Ppos::with(['project:id,pr_number,name', 'pepo:id,category', 'ds:id,dsname'])
            ->get();

        $pdf = new \TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('MDSJEDPR');
        $pdf->SetAuthor('MDSJEDPR');
        $pdf->SetTitle('Project Purchase Orders');
        $pdf->SetSubject('PPOs List');

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
        $pdf->Cell(0, 10, 'Project Purchase Orders Management', 0, 1, 'C');

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

        // Column widths (total 277mm for Landscape)
        $widths = array(10, 25, 45, 30, 30, 27, 25, 25, 30, 30);

        $pdf->Cell($widths[0], 10, '#', 1, 0, 'C', true);
        $pdf->Cell($widths[1], 10, 'PR Number', 1, 0, 'L', true);
        $pdf->Cell($widths[2], 10, 'Project Name', 1, 0, 'L', true);
        $pdf->Cell($widths[3], 10, 'Category', 1, 0, 'L', true);
        $pdf->Cell($widths[4], 10, 'Supplier', 1, 0, 'L', true);
        $pdf->Cell($widths[5], 10, 'PO Number', 1, 0, 'L', true);
        $pdf->Cell($widths[6], 10, 'Value', 1, 0, 'R', true);
        $pdf->Cell($widths[7], 10, 'Date', 1, 0, 'C', true);
        $pdf->Cell($widths[8], 10, 'Status', 1, 0, 'L', true);
        $pdf->Cell($widths[9], 10, 'Updates', 1, 1, 'L', true);

        // Table content
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(0, 0, 0);

        $fill = false;
        foreach ($ppos as $index => $item) {
            if ($fill) {
                $pdf->SetFillColor(245, 245, 245);
            } else {
                $pdf->SetFillColor(255, 255, 255);
            }

            // Get all categories for this PO Number
            $allCategories = Ppos::where('po_number', $item->po_number)
                ->with('pepo:id,category')
                ->get()
                ->pluck('pepo.category')
                ->filter()
                ->unique()
                ->implode(', ');

            $pdf->Cell($widths[0], 10, ($index + 1), 1, 0, 'C', true);
            $pdf->Cell($widths[1], 10, $item->project->pr_number ?? 'N/A', 1, 0, 'L', true);
            $pdf->Cell($widths[2], 10, $item->project->name ?? 'N/A', 1, 0, 'L', true);
            $pdf->Cell($widths[3], 10, $allCategories ?: 'N/A', 1, 0, 'L', true);
            $pdf->Cell($widths[4], 10, $item->ds->dsname ?? 'N/A', 1, 0, 'L', true);
            $pdf->Cell($widths[5], 10, $item->po_number ?? 'N/A', 1, 0, 'L', true);
            $pdf->Cell($widths[6], 10, $item->value ? '$' . number_format($item->value, 2) : 'N/A', 1, 0, 'R', true);
            $pdf->Cell($widths[7], 10, $item->date ? $item->date->format('Y-m-d') : 'N/A', 1, 0, 'C', true);
            $pdf->Cell($widths[8], 10, $item->status ?? 'N/A', 1, 0, 'L', true);
            $pdf->Cell($widths[9], 10, $item->updates ?? 'N/A', 1, 1, 'L', true);

            $fill = !$fill;
        }

        // Output PDF
        $pdf->Output('PPOs_' . date('Y-m-d') . '.pdf', 'I');
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
