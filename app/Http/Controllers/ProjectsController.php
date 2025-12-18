<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Ds;
use App\Models\aams;
use App\Models\Cust;
use App\Models\ppms;
use App\Models\vendors;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $projects = Project::with([
                'vendor', 'cust', 'ds', 'aams', 'ppms',
                'customers', 'vendors', 'deliverySpecialists'
            ])->get();
            return view('dashboard.projects.index', compact('projects'));
        } catch (Exception $e) {
            Log::error('Projects index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading projects list!');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $ds = Ds::all();
            $custs = Cust::all();
            $aams = aams::all();
            $ppms = ppms::all();
            $vendors = vendors::all();

            return view('dashboard.projects.addpro', compact('ds', 'custs', 'aams', 'ppms', 'vendors'));
        } catch (Exception $e) {
            Log::error('Projects create form error: ' . $e->getMessage());
            return redirect()->route('projects.index')->with('error', 'Error loading create form!');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $this->validateProjectData($request);
            $data = $validated;

            // Handle file uploads
            $data = $this->handleFileUploads($request, $data);

            // Set created by current user
            // $data['Created_by'] = auth()->id();

            // Create the project
            $project = Project::create($data);

            // Handle multiple relationships
            $this->handleMultipleRelationships($project, $request);

            Log::info('Project created successfully', ['project_number' => $data['pr_number']]);
            return redirect()->route('projects.index')->with('success', 'Project created successfully!');
        } catch (Exception $e) {
            Log::error('Project creation error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error creating project: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $project = Project::with([
                'vendor', 'cust', 'ds', 'aams', 'ppms',
                'customers', 'vendors', 'deliverySpecialists'
            ])->findOrFail($id);
            return view('dashboard.projects.show', compact('project'));
        } catch (Exception $e) {
            Log::error('Project show error: ' . $e->getMessage(), ['project_id' => $id]);
            return redirect()->route('projects.index')->with('error', 'Project not found!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $project = Project::with(['customers', 'vendors', 'deliverySpecialists'])->findOrFail($id);
            $ds = Ds::all();
            $custs = Cust::all();
            $aams = aams::all();
            $ppms = ppms::all();
            $vendors = vendors::all();

            return view('dashboard.projects.edit', compact('project', 'ds', 'custs', 'aams', 'ppms', 'vendors'));
        } catch (Exception $e) {
            Log::error('Project edit form error: ' . $e->getMessage(), ['project_id' => $id]);
            return redirect()->route('projects.index')->with('error', 'Project not found!');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $project = Project::findOrFail($id);

            $validated = $this->validateProjectData($request, $project->id);
            $data = $validated;

            // Handle file uploads
            $data = $this->handleFileUploads($request, $data, $project);

            $project->update($data);

            // Clear existing relationships
            $project->customers()->detach();
            $project->vendors()->detach();
            $project->deliverySpecialists()->detach();

            // Handle multiple relationships
            $this->handleMultipleRelationships($project, $request);

            Log::info('Project updated successfully', ['project_id' => $id]);
            return redirect()->route('projects.index')->with('success', 'Project updated successfully!');
        } catch (Exception $e) {
            Log::error('Project update error: ' . $e->getMessage(), ['project_id' => $id]);
            return redirect()->back()->withInput()->with('error', 'Error updating project: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $project = Project::findOrFail($id);

            // Delete associated files
            $this->deleteProjectFiles($project);

            $project->delete();

            Log::info('Project deleted successfully', ['project_id' => $id]);
            return redirect()->route('projects.index')->with('success', 'Project deleted successfully!');
        } catch (Exception $e) {
            Log::error('Project deletion error: ' . $e->getMessage(), ['project_id' => $id]);
            return redirect()->route('projects.index')->with('error', 'Error deleting project!');
        }
    }

    /**
     * Validate project data
     */
    private function validateProjectData(Request $request, $projectId = null)
    {
        $uniqueRule = $projectId ? 'required|unique:projects,pr_number,' . $projectId : 'required|unique:projects,pr_number';

        return $request->validate([
            'pr_number' => $uniqueRule,
            'name' => 'required|string|max:255',
            'technologies' => 'nullable|string',
            'vendors_id' => 'nullable|exists:vendors,id',
            'cust_id' => 'nullable|exists:custs,id',
            'ds_id' => 'nullable|exists:ds,id',
            'aams_id' => 'nullable|exists:aams,id',
            'ppms_id' => 'nullable|exists:ppms,id',
            'value' => 'nullable|numeric',
            'customer_name' => 'nullable|string|max:255',
            'customer_po' => 'nullable|string',
            'ac_manager' => 'nullable|string|max:255',
            'project_manager' => 'nullable|string|max:255',
            'customer_contact_details' => 'nullable|string',
            'customer_po_date' => 'nullable|date',
            'customer_po_duration' => 'nullable|integer',
            'customer_po_deadline' => 'nullable|date',
            'description' => 'nullable|string',
            'po_attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'epo_attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);
    }

    /**
     * Handle file uploads
     */
    private function handleFileUploads(Request $request, array $data, $project = null)
    {
        if ($request->hasFile('po_attachment')) {
            // Delete old file if updating
            if ($project && $project->po_attachment) {
                $oldFilePath = base_path($project->po_attachment);
                $oldPublicPath = public_path($project->po_attachment);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
                if (file_exists($oldPublicPath)) {
                    unlink($oldPublicPath);
                }
            }

            $file = $request->file('po_attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();

            // حفظ في مجلد storge الأصلي
            $destinationPath = base_path('storge');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $fileName);

            // نسخ إلى مجلد public للعرض
            $publicPath = public_path('storge');
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0755, true);
            }
            copy(base_path('storge/' . $fileName), $publicPath . '/' . $fileName);

            $data['po_attachment'] = 'storge/' . $fileName;
        }

        if ($request->hasFile('epo_attachment')) {
            // Delete old file if updating
            if ($project && $project->epo_attachment) {
                $oldFilePath = base_path($project->epo_attachment);
                $oldPublicPath = public_path($project->epo_attachment);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
                if (file_exists($oldPublicPath)) {
                    unlink($oldPublicPath);
                }
            }

            $file = $request->file('epo_attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();

            // حفظ في مجلد storge الأصلي
            $destinationPath = base_path('storge');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $fileName);

            // نسخ إلى مجلد public للعرض
            $publicPath = public_path('storge');
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0755, true);
            }
            copy(base_path('storge/' . $fileName), $publicPath . '/' . $fileName);

            $data['epo_attachment'] = 'storge/' . $fileName;
        }

        return $data;
    }

    /**
     * Delete project files
     */
    private function deleteProjectFiles($project)
    {
        if ($project->po_attachment) {
            $filePath = base_path($project->po_attachment);
            $publicPath = public_path($project->po_attachment);

            if (file_exists($filePath)) {
                unlink($filePath);
            }
            if (file_exists($publicPath)) {
                unlink($publicPath);
            }
        }

        if ($project->epo_attachment) {
            $filePath = base_path($project->epo_attachment);
            $publicPath = public_path($project->epo_attachment);

            if (file_exists($filePath)) {
                unlink($filePath);
            }
            if (file_exists($publicPath)) {
                unlink($publicPath);
            }
        }
    }

    /**
     * Handle multiple relationships for projects
     */
    private function handleMultipleRelationships($project, Request $request)
    {
        // Handle multiple customers
        if ($request->has('customers') && $request->customers) {
            foreach ($request->customers as $index => $customerId) {
                $isPrimary = ($index === 0); // First selected is primary
                $project->customers()->attach($customerId, [
                    'is_primary' => $isPrimary,
                    'role' => $isPrimary ? 'Primary Customer' : 'Partner Customer',
                    'notes' => null
                ]);
            }
        }

        // Handle multiple vendors
        if ($request->has('vendors') && $request->vendors) {
            foreach ($request->vendors as $index => $vendorId) {
                $isPrimary = ($index === 0); // First selected is primary
                $project->vendors()->attach($vendorId, [
                    'is_primary' => $isPrimary,
                    'service_type' => $isPrimary ? 'Primary Service' : 'Additional Service',
                    'contract_value' => $isPrimary ? $project->value : null,
                    'start_date' => $isPrimary ? $project->customer_po_date : null,
                    'end_date' => $isPrimary ? $project->customer_po_deadline : null,
                    'notes' => null
                ]);
            }
        }

        // Handle multiple delivery specialists
        if ($request->has('delivery_specialists') && $request->delivery_specialists) {
            foreach ($request->delivery_specialists as $index => $dsId) {
                $isLead = ($index === 0); // First selected is lead
                $allocationPercentage = count($request->delivery_specialists) > 1 ?
                    ($isLead ? 60.00 : (40.00 / (count($request->delivery_specialists) - 1))) : 100.00;

                $project->deliverySpecialists()->attach($dsId, [
                    'is_lead' => $isLead,
                    'responsibility' => $isLead ? 'Lead Distributor/Supplier' : 'Support Distributor/Supplier',
                    'allocation_percentage' => $allocationPercentage,
                    'assigned_date' => $project->customer_po_date ?? now(),
                    'notes' => null
                ]);
            }
        }

        // Backward compatibility: handle old field names if new ones are empty
        if ((!$request->has('customers') || empty($request->customers)) && $project->cust_id) {
            $project->customers()->attach($project->cust_id, [
                'is_primary' => true,
                'role' => 'Customer',
                'notes' => null
            ]);
        }

        if ((!$request->has('vendors') || empty($request->vendors)) && $project->vendors_id) {
            $project->vendors()->attach($project->vendors_id, [
                'is_primary' => true,
                'service_type' => 'Service',
                'contract_value' => $project->value,
                'start_date' => $project->customer_po_date,
                'end_date' => $project->customer_po_deadline,
                'notes' => null
            ]);
        }

        if ((!$request->has('delivery_specialists') || empty($request->delivery_specialists)) && $project->ds_id) {
            $project->deliverySpecialists()->attach($project->ds_id, [
                'is_lead' => true,
                'responsibility' => 'Distributor/Supplier',
                'allocation_percentage' => 100.00,
                'assigned_date' => $project->customer_po_date ?? now(),
                'notes' => null
            ]);
        }
    }

    /**
     * Export Projects to PDF using TCPDF - Card Layout
     */
    public function exportPDF()
    {
        try {
            // Get all projects with relationships
            $projects = Project::with([
                'vendor', 'cust', 'ds', 'aams', 'ppms',
                'vendors', 'customers', 'deliverySpecialists'
            ])->get();

            // Create new PDF document - A4 Portrait for card layout
            $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

            // Set document information
            $pdf->SetCreator('MDS JED Project System');
            $pdf->SetAuthor('Corporate Sites Management System');
            $pdf->SetTitle('Projects Cards Report');
            $pdf->SetSubject('Projects Export - Card View');

            // Remove default header/footer
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            // Set margins for A4
            $pdf->SetMargins(10, 10, 10);
            $pdf->SetAutoPageBreak(false); // Disable auto page break for better control

            // Set font
            $pdf->SetFont('helvetica', '', 9);

            $cardCount = 0;
            $cardsPerPage = 5; // 5 cards per page

            foreach ($projects as $index => $project) {
                // Add new page for every set of cards
                if ($cardCount % $cardsPerPage == 0) {
                    $pdf->AddPage('P');

                    // Page Header
                    $pdf->SetFont('helvetica', 'B', 14);
                    $pdf->SetTextColor(103, 126, 234);
                    $pdf->Cell(0, 8, 'Projects Report', 0, 1, 'C');

                    $pdf->SetFont('helvetica', '', 8);
                    $pdf->SetTextColor(120, 120, 120);
                    $pdf->Cell(0, 5, 'Generated: ' . date('d/m/Y g:i A'), 0, 1, 'C');
                    $pdf->Ln(1); // Minimal spacing

                    // Add footer for each page
                    $pdf->SetY(-10);
                    $pdf->SetFont('helvetica', 'B', 9);
                    $pdf->SetTextColor(103, 126, 234);
                    $pdf->Cell(0, 8, 'MDSJEDPR', 0, 0, 'C');

                    // Reset Y position for content
                    $pdf->SetY(27);
                }                // Get related data
                $allVendors = $project->vendors()->pluck('vendors')->implode(', ') ?: 'N/A';
                $allCustomers = $project->customers()->pluck('name')->implode(', ') ?: 'N/A';
                $allDS = $project->deliverySpecialists()->pluck('dsname')->implode(', ') ?: 'N/A';

                // Card container with compact spacing for 5 cards
                $cardY = $pdf->GetY();

                // Card border and background - reduced height for 5 cards per page
                $pdf->SetFillColor(255, 255, 255);
                $pdf->SetDrawColor(103, 126, 234);
                $pdf->SetLineWidth(0.5);
                $pdf->RoundedRect(10, $cardY, 190, 52, 3, '1111', 'DF'); // Reduced from 90 to 52

                // Card header with project number - compact
                $pdf->SetFillColor(103, 126, 234);
                $pdf->SetTextColor(255, 255, 255);
                $pdf->RoundedRect(10, $cardY, 190, 8, 3, '1100', 'F'); // Reduced from 10 to 8

                $pdf->SetXY(12, $cardY + 1.5);
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->Cell(90, 5, 'PR: ' . ($project->pr_number ?? 'N/A'), 0, 0, 'L');

                $pdf->SetFont('helvetica', '', 7);
                $pdf->Cell(96, 5, 'Project #' . ($index + 1), 0, 1, 'R');

                // Card content
                $pdf->SetTextColor(0, 0, 0);
                $contentY = $cardY + 9.5; // Adjusted for smaller header

                // Project Name (smaller)
                $pdf->SetXY(15, $contentY);
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->SetTextColor(50, 50, 50);
                $pdf->MultiCell(180, 4, $project->name ?? 'N/A', 0, 'L', false, 1);

                $contentY = $pdf->GetY() + 0.5;

                // Three-column layout - more compact
                $leftX = 15;
                $middleX = 75;
                $rightX = 135;
                $labelWidth = 32;
                $valueWidth = 25;
                $lineHeight = 3.5; // Reduced from 4.5

                // Left column
                $pdf->SetFont('helvetica', 'B', 6);
                $pdf->SetTextColor(80, 80, 80);

                // Technologies
                $pdf->SetXY($leftX, $contentY);
                $pdf->Cell($labelWidth, $lineHeight, 'Technologies:', 0, 0, 'L');
                $pdf->SetFont('helvetica', '', 6);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->MultiCell($valueWidth, $lineHeight, $project->technologies ?? 'N/A', 0, 'L', false, 1);

                // All Vendors
                $pdf->SetXY($leftX, $pdf->GetY());
                $pdf->SetFont('helvetica', 'B', 6);
                $pdf->SetTextColor(80, 80, 80);
                $pdf->Cell($labelWidth, $lineHeight, 'All Vendors:', 0, 0, 'L');
                $pdf->SetFont('helvetica', '', 6);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->MultiCell($valueWidth, $lineHeight, $allVendors, 0, 'L', false, 1);

                // All Customers
                $pdf->SetXY($leftX, $pdf->GetY());
                $pdf->SetFont('helvetica', 'B', 6);
                $pdf->SetTextColor(80, 80, 80);
                $pdf->Cell($labelWidth, $lineHeight, 'All Customers:', 0, 0, 'L');
                $pdf->SetFont('helvetica', '', 6);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->MultiCell($valueWidth, $lineHeight, $allCustomers, 0, 'L', false, 1);

                // Middle column
                $currentLeftY = $pdf->GetY();

                // Value
                $pdf->SetXY($middleX, $contentY);
                $pdf->SetFont('helvetica', 'B', 6);
                $pdf->SetTextColor(80, 80, 80);
                $pdf->Cell($labelWidth, $lineHeight, 'Value:', 0, 0, 'L');
                $pdf->SetFont('helvetica', '', 6);
                $pdf->SetTextColor(0, 128, 0);
                $pdf->MultiCell($valueWidth, $lineHeight, $project->value ? number_format($project->value, 2) . ' SAR' : 'N/A', 0, 'L', false, 1);

                // AC Manager
                $pdf->SetXY($middleX, $pdf->GetY());
                $pdf->SetFont('helvetica', 'B', 6);
                $pdf->SetTextColor(80, 80, 80);
                $pdf->Cell($labelWidth, $lineHeight, 'AC Manager:', 0, 0, 'L');
                $pdf->SetFont('helvetica', '', 6);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->MultiCell($valueWidth, $lineHeight, optional($project->aams)->name ?? 'N/A', 0, 'L', false, 1);

                // Project Manager
                $pdf->SetXY($middleX, $pdf->GetY());
                $pdf->SetFont('helvetica', 'B', 6);
                $pdf->SetTextColor(80, 80, 80);
                $pdf->Cell($labelWidth, $lineHeight, 'Project Manager:', 0, 0, 'L');
                $pdf->SetFont('helvetica', '', 6);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->MultiCell($valueWidth, $lineHeight, optional($project->ppms)->name ?? 'N/A', 0, 'L', false, 1);

                // All D/S
                $pdf->SetXY($middleX, $pdf->GetY());
                $pdf->SetFont('helvetica', 'B', 6);
                $pdf->SetTextColor(80, 80, 80);
                $pdf->Cell($labelWidth, $lineHeight, 'All D/S:', 0, 0, 'L');
                $pdf->SetFont('helvetica', '', 6);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->MultiCell($valueWidth, $lineHeight, $allDS, 0, 'L', false, 1);

                // Right column
                // PO Date
                $pdf->SetXY($rightX, $contentY);
                $pdf->SetFont('helvetica', 'B', 6);
                $pdf->SetTextColor(80, 80, 80);
                $pdf->Cell($labelWidth, $lineHeight, 'PO Date:', 0, 0, 'L');
                $pdf->SetFont('helvetica', '', 6);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->MultiCell($valueWidth, $lineHeight, $project->customer_po_date ? date('d/m/Y', strtotime($project->customer_po_date)) : 'N/A', 0, 'L', false, 1);

                // Duration
                $pdf->SetXY($rightX, $pdf->GetY());
                $pdf->SetFont('helvetica', 'B', 6);
                $pdf->SetTextColor(80, 80, 80);
                $pdf->Cell($labelWidth, $lineHeight, 'Duration:', 0, 0, 'L');
                $pdf->SetFont('helvetica', '', 6);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->MultiCell($valueWidth, $lineHeight, ($project->customer_po_duration ?? 'N/A') . ' days', 0, 'L', false, 1);

                // Customer Contact
                $pdf->SetXY($rightX, $pdf->GetY());
                $pdf->SetFont('helvetica', 'B', 6);
                $pdf->SetTextColor(80, 80, 80);
                $pdf->Cell($labelWidth, $lineHeight, 'Contact:', 0, 0, 'L');
                $pdf->SetFont('helvetica', '', 6);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->MultiCell($valueWidth, $lineHeight, $project->customer_contact_details ?? 'N/A', 0, 'L', false, 1);

                // Description (full width at bottom) - more compact
                $descY = max($pdf->GetY(), $currentLeftY) + 0.5;
                $pdf->SetXY(15, $descY);
                $pdf->SetFont('helvetica', 'B', 6);
                $pdf->SetTextColor(80, 80, 80);
                $pdf->Cell(30, $lineHeight, 'Description:', 0, 1, 'L');

                // Move to value position (same X as label start)
                $pdf->SetXY(15, $pdf->GetY());
                $pdf->SetFont('helvetica', '', 6);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->MultiCell(180, 4, $project->description ?? 'N/A', 0, 'L', false, 1);
                // Move to next card position - 5 cards per page
                $pdf->SetY($cardY + 53.5); // Tight spacing for 5 cards
                $cardCount++;
            }

            // No footer needed - MDSJEDPR already added to each page

            // Output PDF
            $filename = 'Projects_Cards_' . date('Y-m-d_His') . '.pdf';

            return response()->streamDownload(function() use ($pdf) {
                echo $pdf->Output('', 'S');
            }, $filename, [
                'Content-Type' => 'application/pdf',
            ]);

        } catch (Exception $e) {
            Log::error('Projects PDF export error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error generating PDF: ' . $e->getMessage());
        }
    }

    /**
     * Print Projects View - Same design as PDF for direct printing
     */
    public function printView()
    {
        try {
            // Get all projects with relationships
            $projects = Project::with([
                'vendor', 'cust', 'ds', 'aams', 'ppms',
                'vendors', 'customers', 'deliverySpecialists'
            ])->get();

            return view('dashboard.projects.print', compact('projects'));

        } catch (Exception $e) {
            Log::error('Projects print view error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading print view: ' . $e->getMessage());
        }
    }

    /**
     * Export Projects to Excel using Maatwebsite/Laravel-Excel
     */
    public function exportExcel()
    {
        try {
            $fileName = 'Projects_' . date('Y-m-d') . '.xlsx';
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ProjectsExport, $fileName);
        } catch (Exception $e) {
            Log::error('Projects Excel export error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error exporting Excel: ' . $e->getMessage());
        }
    }
}
