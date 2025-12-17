<?php

namespace App\Services;

use App\Models\Project;
use App\Models\vendors;
use App\Models\Cust;
use App\Models\ppms;
use App\Models\aams;
use App\Models\Ds;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class ReportService
{
    /**
     * Cache duration in minutes
     */
    private const CACHE_DURATION = 60;

    /**
     * Get filtered reports data
     */
    public function getFilteredReports(array $filters = []): object
    {
        try {
            // Generate cache key based on filters
            $cacheKey = $this->generateCacheKey($filters);

            // Try to get from cache
            return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($filters) {
                return $this->buildReportsQuery($filters);
            });
        } catch (\Exception $e) {
            Log::error('Error in getFilteredReports: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Build reports query with filters
     */
    private function buildReportsQuery(array $filters): object
    {
        $query = QueryBuilder::for(Project::class)
            ->with(['vendor', 'cust', 'ds', 'aams', 'ppms'])
            ->allowedFilters($this->getAllowedFilters())
            ->defaultSort('-created_at');

        return $query->get();
    }

    /**
     * Get all allowed filters configuration
     */
    private function getAllowedFilters(): array
    {
        return [
            AllowedFilter::callback('pr_number', function ($query, $value) {
                $query->where('pr_number', '=', $value);
            }),
            AllowedFilter::callback('name', function ($query, $value) {
                $query->where('name', '=', $value);
            }),
            AllowedFilter::partial('technologies'),
            AllowedFilter::callback('customer_po', function ($query, $value) {
                $query->where('customer_po', '=', $value);
            }),
            AllowedFilter::callback('project_manager', function ($query, $value) {
                $query->whereHas('ppms', function ($q) use ($value) {
                    $q->where('name', $value);
                });
            }),
            AllowedFilter::callback('customer_name', function ($query, $value) {
                $query->whereHas('cust', function ($q) use ($value) {
                    $q->where('name', $value);
                });
            }),
            AllowedFilter::callback('vendors', function ($query, $value) {
                $query->whereHas('vendor', function ($q) use ($value) {
                    $q->where('vendors', $value);
                });
            }),
            AllowedFilter::callback('suppliers', function ($query, $value) {
                $query->whereHas('ds', function ($q) use ($value) {
                    $q->where('dsname', $value);
                });
            }),
            AllowedFilter::callback('am', function ($query, $value) {
                $query->whereHas('aams', function ($q) use ($value) {
                    $q->where('name', $value);
                });
            }),
            AllowedFilter::callback('value_min', function ($query, $value) {
                $query->where('value', '>=', $value);
            }),
            AllowedFilter::callback('value_max', function ($query, $value) {
                $query->where('value', '<=', $value);
            }),
            AllowedFilter::callback('deadline_from', function ($query, $value) {
                $query->where('customer_po_deadline', '>=', $value);
            }),
            AllowedFilter::callback('deadline_to', function ($query, $value) {
                $query->where('customer_po_deadline', '<=', $value);
            }),
        ];
    }

    /**
     * Get all filter options (dropdown data)
     */
    public function getFilterOptions(): array
    {
        return Cache::remember('report_filter_options', self::CACHE_DURATION, function () {
            return [
                'prNumbers' => $this->getDistinctValues(Project::class, 'pr_number'),
                'projectNames' => $this->getDistinctValues(Project::class, 'name'),
                'technologies' => $this->getDistinctValues(Project::class, 'technologies'),
                'customerPos' => $this->getDistinctValues(Project::class, 'customer_po'),
                'projectManagers' => $this->getDistinctValues(ppms::class, 'name'),
                'customerNames' => $this->getDistinctValues(Cust::class, 'name'),
                'vendorsList' => $this->getDistinctValues(vendors::class, 'vendors'),
                'suppliers' => $this->getDistinctValues(Ds::class, 'dsname'),
                'ams' => $this->getDistinctValues(aams::class, 'name'),
            ];
        });
    }

    /**
     * Get all additional tables data
     */
    public function getAllTablesData(): array
    {
        return Cache::remember('report_all_tables_data', self::CACHE_DURATION, function () {
            return [
                'allVendors' => vendors::orderBy('created_at', 'desc')->get(),
                'allCustomers' => Cust::orderBy('created_at', 'desc')->get(),
                'allProjectManagers' => ppms::orderBy('created_at', 'desc')->get(),
                'allAccountManagers' => aams::orderBy('created_at', 'desc')->get(),
                'allDeliverySpecialists' => Ds::orderBy('created_at', 'desc')->get(),
                'projectCustomers' => $this->getProjectCustomers(),
                'projectVendors' => $this->getProjectVendors(),
                'projectDS' => $this->getProjectDS(),
            ];
        });
    }

    /**
     * Get project-customer relations
     */
    private function getProjectCustomers()
    {
        return DB::table('project_customers')
            ->join('projects', 'project_customers.project_id', '=', 'projects.id')
            ->join('custs', 'project_customers.customer_id', '=', 'custs.id')
            ->select('project_customers.*', 'projects.pr_number', 'projects.name as project_name', 'custs.name as customer_name')
            ->orderBy('project_customers.created_at', 'desc')
            ->get();
    }

    /**
     * Get project-vendor relations
     */
    private function getProjectVendors()
    {
        return DB::table('project_vendors')
            ->join('projects', 'project_vendors.project_id', '=', 'projects.id')
            ->join('vendors', 'project_vendors.vendor_id', '=', 'vendors.id')
            ->select('project_vendors.*', 'projects.pr_number', 'projects.name as project_name', 'vendors.vendors as vendor_name')
            ->orderBy('project_vendors.created_at', 'desc')
            ->get();
    }

    /**
     * Get project-DS relations
     */
    private function getProjectDS()
    {
        return DB::table('project_delivery_specialists')
            ->join('projects', 'project_delivery_specialists.project_id', '=', 'projects.id')
            ->join('ds', 'project_delivery_specialists.ds_id', '=', 'ds.id')
            ->select('project_delivery_specialists.*', 'projects.pr_number', 'projects.name as project_name', 'ds.dsname')
            ->orderBy('project_delivery_specialists.created_at', 'desc')
            ->get();
    }

    /**
     * Get distinct values from a table column
     */
    private function getDistinctValues(string $model, string $column)
    {
        return $model::distinct()
            ->whereNotNull($column)
            ->pluck($column)
            ->filter()
            ->sort()
            ->values();
    }

    /**
     * Generate cache key based on filters
     */
    private function generateCacheKey(array $filters): string
    {
        return 'reports_filtered_' . md5(json_encode($filters));
    }

    /**
     * Clear reports cache
     */
    public function clearCache(): void
    {
        Cache::forget('report_filter_options');
        Cache::forget('report_all_tables_data');
        // Clear all filtered reports cache (this will clear on next update)
        Cache::flush(); // Or use more specific cache tags if available
    }

    /**
     * Get reports statistics
     */
    public function getReportsStatistics(): array
    {
        return Cache::remember('report_statistics', self::CACHE_DURATION, function () {
            return [
                'total_projects' => Project::count(),
                'total_vendors' => vendors::count(),
                'total_customers' => Cust::count(),
                'total_value' => Project::sum('value'),
                'avg_value' => Project::avg('value'),
                'active_projects' => Project::whereNotNull('customer_po_deadline')
                    ->where('customer_po_deadline', '>=', now())
                    ->count(),
            ];
        });
    }

    /**
     * Export filtered data to array (for CSV/Excel)
     */
    public function exportFilteredData(array $filters = []): array
    {
        $reports = $this->getFilteredReports($filters);

        return $reports->map(function ($report) {
            return [
                'PR Number' => $report->pr_number,
                'Project Name' => $report->name,
                'Project Manager' => $report->ppms->name ?? '',
                'Technologies' => $report->technologies,
                'Customer' => $report->cust->name ?? '',
                'Customer PO' => $report->customer_po,
                'Value' => $report->value,
                'Deadline' => $report->customer_po_deadline,
                'Vendor' => $report->vendor->vendors ?? '',
                'Supplier' => $report->ds->dsname ?? '',
                'Account Manager' => $report->aams->name ?? '',
            ];
        })->toArray();
    }
}
