<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Project;
use App\Models\ppms;
use App\Models\aams;
use App\Models\vendors;
use App\Models\Ds;
use App\Models\Cust;
use App\Http\Requests\ReportFilterRequest;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class ReportController extends Controller
{
    /**
     * Report Service Instance
     */
    protected $reportService;

    /**
     * Constructor - Inject ReportService
     */
    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Display a listing of the resource with filters.
     */
    public function index(ReportFilterRequest $request)
    {
        try {
            // Get filter options (cached) - always needed for dropdowns
            $filterOptions = $this->reportService->getFilterOptions();

            return view('dashboard.reports.index', [
                'filterOptions' => $filterOptions
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ReportController@index: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->with('error', 'An error occurred while loading reports. Please try again.')
                ->withInput();
        }
    }



    /**
     * Get customer projects via AJAX
     */
    public function getCustomerProjects(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'customer_name' => 'required|string|max:255'
            ]);

            $customerName = $request->input('customer_name');

            // Get customer with projects count
            $customer = Cust::where('name', $customerName)->first();

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found: ' . $customerName
                ], 404);
            }

            // Get projects for this customer with eager loading if needed
            $projects = Project::where('cust_id', $customer->id)
                ->select('id', 'pr_number', 'name', 'value', 'customer_po', 'customer_po_deadline')
                ->orderBy('created_at', 'desc')
                ->get();

            // Calculate total value
            $totalValue = $projects->sum('value');

            // Format projects
            $formattedProjects = $projects->map(function($project) {
                return [
                    'id' => $project->id,
                    'pr_number' => $project->pr_number ?? 'N/A',
                    'name' => $project->name ?? 'Untitled Project',
                    'value' => number_format($project->value ?? 0, 2),
                    'customer_po' => $project->customer_po,
                    'deadline' => $project->customer_po_deadline
                        ? \Carbon\Carbon::parse($project->customer_po_deadline)->format('Y-m-d')
                        : null,
                ];
            });

            return response()->json([
                'success' => true,
                'customer' => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'abb' => $customer->abb ?? 'N/A',
                    'type' => $customer->tybe ?? 'N/A',
                ],
                'projects' => $formattedProjects,
                'total_projects' => $projects->count(),
                'total_value' => $totalValue
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error getting customer projects', [
                'customer_name' => $request->input('customer_name'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching customer projects. Please try again.'
            ], 500);
        }
    }

    /**
     * Get vendor projects via AJAX
     */
    public function getVendorProjects(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'vendor_name' => 'required|string|max:255'
            ]);

            $vendorName = $request->input('vendor_name');

            // Get vendor - field name is 'vendors' not 'name'
            $vendor = vendors::where('vendors', $vendorName)->first();

            if (!$vendor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vendor not found: ' . $vendorName
                ], 404);
            }

            // Get projects for this vendor with customer info
            $projects = Project::where('vendors_id', $vendor->id)
                ->with('cust:id,name')
                ->select('id', 'pr_number', 'name', 'value', 'customer_po', 'customer_po_deadline', 'cust_id')
                ->orderBy('created_at', 'desc')
                ->get();

            // Calculate total value
            $totalValue = $projects->sum('value');

            // Format projects
            $formattedProjects = $projects->map(function($project) {
                return [
                    'id' => $project->id,
                    'pr_number' => $project->pr_number ?? 'N/A',
                    'name' => $project->name ?? 'Untitled Project',
                    'customer_name' => $project->cust->name ?? 'N/A',
                    'value' => number_format($project->value ?? 0, 2),
                    'customer_po' => $project->customer_po,
                    'deadline' => $project->customer_po_deadline
                        ? \Carbon\Carbon::parse($project->customer_po_deadline)->format('Y-m-d')
                        : null,
                ];
            });

            return response()->json([
                'success' => true,
                'vendor' => [
                    'id' => $vendor->id,
                    'name' => $vendor->vendors,
                    'abb' => 'N/A',
                    'type' => 'Vendor',
                ],
                'projects' => $formattedProjects,
                'total_projects' => $projects->count(),
                'total_value' => $totalValue
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error getting vendor projects', [
                'vendor_name' => $request->input('vendor_name'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching vendor projects. Please try again.'
            ], 500);
        }
    }

    /**
     * Get supplier (delivery specialist) projects via AJAX
     */
    public function getSupplierProjects(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'supplier_name' => 'required|string|max:255'
            ]);

            $supplierName = $request->input('supplier_name');

            // Get supplier (DS) - note: DS table uses 'dsname' not 'name'
            $supplier = Ds::where('dsname', $supplierName)->first();

            if (!$supplier) {
                return response()->json([
                    'success' => false,
                    'message' => 'Supplier not found: ' . $supplierName
                ], 404);
            }

            // Get projects for this supplier through pivot table
            $projects = Project::whereHas('deliverySpecialists', function($query) use ($supplier) {
                $query->where('ds_id', $supplier->id);
            })
            ->with(['cust:id,name', 'deliverySpecialists:id,dsname'])
            ->select('id', 'pr_number', 'name', 'value', 'customer_po', 'customer_po_deadline', 'cust_id')
            ->orderBy('created_at', 'desc')
            ->get();

            // Calculate total value
            $totalValue = $projects->sum('value');

            // Format projects
            $formattedProjects = $projects->map(function($project) {
                return [
                    'id' => $project->id,
                    'pr_number' => $project->pr_number ?? 'N/A',
                    'name' => $project->name ?? 'Untitled Project',
                    'customer_name' => $project->cust->name ?? 'N/A',
                    'value' => number_format($project->value ?? 0, 2),
                    'customer_po' => $project->customer_po ?? 'N/A',
                    'po_value' => number_format($project->value ?? 0, 2),
                    'all_ds' => $project->deliverySpecialists->pluck('dsname')->implode(', '),
                    'deadline' => $project->customer_po_deadline
                        ? \Carbon\Carbon::parse($project->customer_po_deadline)->format('Y-m-d')
                        : null,
                ];
            });

            return response()->json([
                'success' => true,
                'supplier' => [
                    'id' => $supplier->id,
                    'name' => $supplier->dsname,
                    'abb' => 'N/A',
                    'type' => 'Supplier',
                ],
                'projects' => $formattedProjects,
                'total_projects' => $projects->count(),
                'total_value' => $totalValue
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error getting supplier projects', [
                'supplier_name' => $request->input('supplier_name'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching supplier projects. Please try again.'
            ], 500);
        }
    }

    /**
     * Get PM (Project Manager) projects via AJAX
     */
    public function getPMProjects(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'pm_name' => 'required|string|max:255'
            ]);

            $pmName = $request->input('pm_name');

            // Get PM
            $pm = ppms::where('name', $pmName)->first();

            if (!$pm) {
                return response()->json([
                    'success' => false,
                    'message' => 'PM not found: ' . $pmName
                ], 404);
            }

            // Get projects for this PM with customer info
            $projects = Project::where('ppms_id', $pm->id)
                ->with('cust:id,name')
                ->select('id', 'pr_number', 'name', 'value', 'cust_id')
                ->orderBy('created_at', 'desc')
                ->get();

            // Calculate total value
            $totalValue = $projects->sum('value');

            // Format projects
            $formattedProjects = $projects->map(function($project) {
                return [
                    'id' => $project->id,
                    'pr_number' => $project->pr_number ?? 'N/A',
                    'name' => $project->name ?? 'Untitled Project',
                    'customer_name' => $project->cust->name ?? 'N/A',
                    'value' => number_format($project->value ?? 0, 2),
                ];
            });

            return response()->json([
                'success' => true,
                'pm' => [
                    'id' => $pm->id,
                    'name' => $pm->name,
                ],
                'projects' => $formattedProjects,
                'total_projects' => $projects->count(),
                'total_value' => $totalValue
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error getting PM projects', [
                'pm_name' => $request->input('pm_name'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching PM projects. Please try again.'
            ], 500);
        }
    }

    /**
     * Get AM (Account Manager) projects via AJAX
     */
    public function getAMProjects(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'am_name' => 'required|string|max:255'
            ]);

            $amName = $request->input('am_name');

            // Get AM
            $am = aams::where('name', $amName)->first();

            if (!$am) {
                return response()->json([
                    'success' => false,
                    'message' => 'AM not found: ' . $amName
                ], 404);
            }

            // Get projects for this AM with customer info
            $projects = Project::where('aams_id', $am->id)
                ->with('cust:id,name')
                ->select('id', 'pr_number', 'name', 'value', 'cust_id')
                ->orderBy('created_at', 'desc')
                ->get();

            // Calculate total value
            $totalValue = $projects->sum('value');

            // Format projects
            $formattedProjects = $projects->map(function($project) {
                return [
                    'id' => $project->id,
                    'pr_number' => $project->pr_number ?? 'N/A',
                    'name' => $project->name ?? 'Untitled Project',
                    'customer_name' => $project->cust->name ?? 'N/A',
                    'value' => number_format($project->value ?? 0, 2),
                ];
            });

            return response()->json([
                'success' => true,
                'am' => [
                    'id' => $am->id,
                    'name' => $am->name,
                ],
                'projects' => $formattedProjects,
                'total_projects' => $projects->count(),
                'total_value' => $totalValue
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error getting AM projects', [
                'am_name' => $request->input('am_name'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching AM projects. Please try again.'
            ], 500);
        }
    }

    /**
     * Clear reports cache
     */
    public function clearCache()
    {
        try {
            $this->reportService->clearCache();

            return back()->with('success', 'Reports cache cleared successfully');
        } catch (\Exception $e) {
            Log::error('Error clearing cache: ' . $e->getMessage());
            return back()->with('error', 'Failed to clear cache');
        }
    }

    /**
     * Export filtered reports to CSV
     */
    public function export(ReportFilterRequest $request)
    {
        try {
            $filters = $request->input('filter', []);
            $data = $this->reportService->exportFilteredData($filters);

            $filename = 'reports_export_' . date('Y-m-d_His') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv; charset=utf-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            $callback = function() use ($data) {
                $file = fopen('php://output', 'w');

                // Add BOM for UTF-8
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

                // Add headers
                if (!empty($data)) {
                    fputcsv($file, array_keys($data[0]));
                }

                // Add data
                foreach ($data as $row) {
                    fputcsv($file, $row);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Error exporting reports: ' . $e->getMessage());
            return back()->with('error', 'Failed to export reports');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'pr_number' => 'nullable|string|max:255',
            'project_name' => 'nullable|string|max:255',
            'project_manager' => 'nullable|string|max:255',
            'technologies' => 'nullable|string',
            'customer_name' => 'nullable|string|max:255',
            'customer_po' => 'nullable|string|max:255',
            'value' => 'nullable|numeric|min:0',
            'invoice_total' => 'nullable|numeric|min:0',
            'customer_po_deadline' => 'nullable|date',
            'actual_completion_percentage' => 'nullable|numeric|min:0|max:100',
            'vendors' => 'nullable|string|max:255',
            'suppliers' => 'nullable|string|max:255',
            'am' => 'nullable|string|max:255',
        ]);

        Report::create($validatedData);

        session()->flash('Add', 'Report created successfully');
        return redirect()->route('reports.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        $validatedData = $request->validate([
            'pr_number' => 'nullable|string|max:255',
            'project_name' => 'nullable|string|max:255',
            'project_manager' => 'nullable|string|max:255',
            'technologies' => 'nullable|string',
            'customer_name' => 'nullable|string|max:255',
            'customer_po' => 'nullable|string|max:255',
            'value' => 'nullable|numeric|min:0',
            'invoice_total' => 'nullable|numeric|min:0',
            'customer_po_deadline' => 'nullable|date',
            'actual_completion_percentage' => 'nullable|numeric|min:0|max:100',
            'vendors' => 'nullable|string|max:255',
            'suppliers' => 'nullable|string|max:255',
            'am' => 'nullable|string|max:255',
        ]);

        $report->update($validatedData);

        session()->flash('edit', 'Report updated successfully');
        return redirect()->route('reports.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        $report->delete();
        session()->flash('delete', 'Report deleted successfully');
        return redirect()->route('reports.index');
    }
}
