@extends('layouts.master')
@section('title')
  Reports | MDSJEDPR
@stop

@section('css')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Filter Sidebar Styles - Exact Dashboard Match */
    .filter-sidebar {
        width: 280px;
        flex-shrink: 0;
        height: fit-content;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 12px;
        padding: 18px;
        box-shadow: 0 5px 30px rgba(0, 123, 255, 0.15);
        border: 2px solid rgba(0, 123, 255, 0.15);
        position: relative;
        margin-bottom: 30px;
    }

    .filter-sidebar::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #007bff 0%, #0056b3 100%);
        border-radius: 15px 15px 0 0;
    }

    .sidebar-header {
        margin-bottom: 15px;
        padding-bottom: 12px;
        border-bottom: 3px solid #007bff;
        position: relative;
    }

    .sidebar-header::after {
        content: '';
        position: absolute;
        bottom: -3px;
        left: 0;
        width: 60px;
        height: 3px;
        background: #0056b3;
    }

    .sidebar-header h5 {
        color: #007bff;
        font-weight: 800;
        font-size: 16px;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .filter-card {
        background: white;
        border: none;
        border-radius: 10px;
        margin-bottom: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .filter-card:hover {
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.15);
    }

    .filter-card .card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: none;
        border-radius: 10px 10px 0 0;
        padding: 10px 12px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-card .card-header h6 {
        margin: 0;
        font-size: 13px;
        font-weight: 700;
        color: #495057;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-card .card-header h6 i {
        color: #007bff;
        font-size: 14px;
    }

    .filter-card .card-body {
        padding: 10px;
    }

    .filter-card label {
        font-size: 11px;
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 6px;
    }

    .select2-container--default .select2-selection--single {
        background: #f8f9fa;
        border: 2px solid transparent;
        border-radius: 8px;
        height: 38px;
        padding: 5px 10px;
        transition: all 0.3s ease;
    }

    .select2-container--default .select2-selection--single:hover {
        background: #ffffff;
        border-color: #e9ecef;
    }

    .select2-container--default.select2-container--focus .select2-selection--single {
        background: white;
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 26px;
        font-size: 13px;
        color: #495057;
    }

    .btn-filter-submit {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        border: none;
        padding: 10px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        width: 100%;
        transition: all 0.3s ease;
        box-shadow: 0 3px 10px rgba(0, 123, 255, 0.2);
    }

    .btn-filter-submit:hover {
        background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
    }

    .btn-filter-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .btn-reset-filters {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        color: white;
        border: none;
        padding: 10px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        width: 100%;
        transition: all 0.3s ease;
        box-shadow: 0 3px 10px rgba(108, 117, 125, 0.2);
    }

    .btn-reset-filters:hover {
        background: linear-gradient(135deg, #5a6268 0%, #545b62 100%);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
        color: white;
    }

    .info-card {
        background: white;
        border-radius: 12px;
        padding: 10px 15px;
        margin-bottom: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border-left: 4px solid #007bff;
    }

    .info-card.customer {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-left-color: #007bff;
    }

    .info-card.vendor {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-left-color: #28a745;
    }

    .info-card h3 {
        color: #007bff;
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .info-card.vendor h3 {
        color: #28a745;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 6px 8px;
        background: rgba(255, 255, 255, 0.6);
        border-radius: 8px;
        margin-bottom: 5px;
        font-size: 12px;
    }

    .info-item i {
        color: #007bff;
        font-size: 14px;
    }

    .info-card.vendor .info-item i {
        color: #28a745;
    }

    .stats-row {
        display: flex;
        gap: 8px;
        margin-bottom: 10px;
    }

    .stat-card {
        flex: 1;
        background: white;
        border-radius: 12px;
        padding: 8px 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }

    .stat-card.projects {
        border-left: 4px solid #007bff;
    }

    .stat-card.value {
        border-left: 4px solid #28a745;
    }

    .stat-card .icon {
        width: 35px;
        height: 35px;
        background: linear-gradient(135deg, #007bff, #0056b3);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-card.value .icon {
        background: linear-gradient(135deg, #28a745, #218838);
    }

    .stat-card .icon i {
        font-size: 16px;
        color: white;
    }

    .stat-card-content {
        flex: 1;
    }

    .stat-card h5 {
        color: #6c757d;
        font-size: 11px;
        font-weight: 600;
        margin-bottom: 3px;
        text-transform: uppercase;
    }

    .stat-card .number {
        font-size: 16px;
        font-weight: 700;
        color: #007bff;
        line-height: 1;
    }

    .stat-card.value .number {
        color: #28a745;
    }

    .projects-table-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        overflow: hidden;
        margin-bottom: 25px;
    }

    .projects-table-card .card-header {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        padding: 12px 20px;
        border: none;
    }

    .projects-table-card .card-header h4 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
    }

    .table-modern {
        margin: 0;
    }

    .table-modern thead th {
        background: #f8f9fa;
        color: #495057;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.3px;
        padding: 10px 12px;
        border: none;
        white-space: nowrap;
    }

    .table-modern tbody tr {
        transition: all 0.3s ease;
    }

    .table-modern tbody tr:hover {
        background-color: #f8f9fa;
    }

    .table-modern td {
        vertical-align: middle;
        padding: 10px 12px;
        border-top: 1px solid #e9ecef;
        font-size: 13px;
    }

    .empty-state {
        text-align: center;
        padding: 40px 30px;
        color: #6c757d;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 25px;
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 15px;
        color: #dee2e6;
    }

    .empty-state h4 {
        color: #495057;
        margin-bottom: 8px;
        font-size: 18px;
    }

    .empty-state p {
        font-size: 14px;
        margin: 0;
    }

    .loading-spinner {
        text-align: center;
        padding: 30px;
        display: none;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 20px;
    }

    .loading-spinner.active {
        display: block;
    }

    .loading-spinner i {
        font-size: 36px;
        color: #007bff;
        animation: spin 1s linear infinite;
    }

    .loading-spinner p {
        font-size: 14px;
        margin-top: 15px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Toast Notifications */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }

    .toast-message {
        background: white;
        border-radius: 10px;
        padding: 15px 20px;
        margin-bottom: 10px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        gap: 15px;
        min-width: 300px;
        animation: slideIn 0.3s ease;
    }

    .toast-message.success {
        border-left: 4px solid #28a745;
    }

    .toast-message.error {
        border-left: 4px solid #dc3545;
    }

    .toast-message.warning {
        border-left: 4px solid #ffc107;
    }

    .toast-message i {
        font-size: 24px;
    }

    .toast-message.success i {
        color: #28a745;
    }

    .toast-message.error i {
        color: #dc3545;
    }

    .toast-message.warning i {
        color: #ffc107;
    }

    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }

    /* Print Styles */
    @media print {
        .filter-sidebar,
        .btn-filter-submit,
        .btn-reset-filters,
        .toast-container,
        .breadcrumb-header {
            display: none !important;
        }

        .col-lg-9 {
            width: 100% !important;
            max-width: 100% !important;
        }

        .info-card,
        .projects-table-card,
        .stat-card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
            page-break-inside: avoid;
        }

        .table-modern {
            font-size: 11px !important;
        }

        .table-modern td,
        .table-modern th {
            padding: 6px 8px !important;
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .filter-sidebar {
            width: 100%;
            margin-bottom: 20px;
        }

        .toast-container {
            right: 10px;
            left: 10px;
        }

        .toast-message {
            min-width: auto;
        }

        .stats-row {
            flex-direction: column;
        }

        .stats-row {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">Dashboard</h4>
            <span class="text-muted mt-1 tx-13 ml-2 mb-0">/ Projects Filters</span>
        </div>
    </div>
</div>
@endsection

@section('content')
{{-- Toast Container --}}
<div class="toast-container" id="toastContainer"></div>

<div class="row">
    {{-- Filter Sidebar --}}
    <div class="col-lg-3 col-md-12 mb-4">
        <div class="filter-sidebar">
            <div class="sidebar-header">
                <h5><i class="fas fa-filter"></i> Filters</h5>
            </div>

            {{-- Customer Filter Card --}}
            <div class="filter-card">
                <div class="card-header">
                    <h6>
                        <i class="fas fa-building"></i> Customer
                    </h6>
                </div>
                <div class="card-body">
                    <label>Select Customer</label>
                    <select id="customerSelect" class="form-control select2" data-placeholder="-- Select Customer --">
                        <option></option>
                        @foreach($filterOptions['customerNames'] as $customerName)
                            <option value="{{ $customerName }}">{{ $customerName }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Vendor Filter Card --}}
            <div class="filter-card">
                <div class="card-header">
                    <h6>
                        <i class="fas fa-briefcase"></i> Vendor
                    </h6>
                </div>
                <div class="card-body">
                    <label>Select Vendor</label>
                    <select id="vendorSelect" class="form-control select2" data-placeholder="-- Select Vendor --">
                        <option></option>
                        @foreach($filterOptions['vendorsList'] as $vendorName)
                            <option value="{{ $vendorName }}">{{ $vendorName }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Supplier Filter Card --}}
            <div class="filter-card">
                <div class="card-header">
                    <h6>
                        <i class="fas fa-truck"></i> Supplier
                    </h6>
                </div>
                <div class="card-body">
                    <label>Select Supplier</label>
                    <select id="supplierSelect" class="form-control select2" data-placeholder="-- Select Supplier --">
                        <option></option>
                        @foreach($filterOptions['suppliers'] as $supplierName)
                            <option value="{{ $supplierName }}">{{ $supplierName }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- PM Filter Card --}}
            <div class="filter-card">
                <div class="card-header">
                    <h6>
                        <i class="fas fa-user-tie"></i> Project Manager
                    </h6>
                </div>
                <div class="card-body">
                    <label>Select PM</label>
                    <select id="pmSelect" class="form-control select2" data-placeholder="-- Select PM --">
                        <option></option>
                        @foreach($filterOptions['projectManagers'] as $pmName)
                            <option value="{{ $pmName }}">{{ $pmName }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- AM Filter Card --}}
            <div class="filter-card">
                <div class="card-header">
                    <h6>
                        <i class="fas fa-user-shield"></i> Account Manager
                    </h6>
                </div>
                <div class="card-body">
                    <label>Select AM</label>
                    <select id="amSelect" class="form-control select2" data-placeholder="-- Select AM --">
                        <option></option>
                        @foreach($filterOptions['ams'] as $amName)
                            <option value="{{ $amName }}">{{ $amName }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Unified Action Buttons --}}
            <div class="mt-4">
                <button id="btnApplyFilters" class="btn btn-filter-submit mb-2">
                    <i class="fas fa-search mr-1"></i> Apply Filters
                </button>
                <button id="btnResetFilters" class="btn btn-reset-filters">
                    <i class="fas fa-undo mr-1"></i> Reset Filters
                </button>
            </div>
        </div>
    </div>

    {{-- Results Column --}}
    <div class="col-lg-9 col-md-12">

        {{-- Welcome Message --}}
        <div class="alert alert-info" style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); border: none; border-radius: 12px; padding: 20px; margin-bottom: 25px; box-shadow: 0 3px 10px rgba(0, 123, 255, 0.1);">
            <h5 style="color: #0d47a1; font-weight: 700; margin-bottom: 10px;">
                <i class="fas fa-chart-bar mr-2"></i>Projects Reports & Analytics
            </h5>
            <p style="color: #1565c0; margin-bottom: 0; font-size: 14px;">
                <i class="fas fa-info-circle mr-1"></i>
                Use the filters on the left to view detailed project information, financial statistics, and delivery notes for customers or vendors.
            </p>
        </div>

<div class="row">
    <div class="col-12">

        {{-- Loading Spinner --}}
        <div id="loadingSpinner" class="loading-spinner">
            <i class="fas fa-spinner fa-spin"></i>
            <p class="mt-3">Loading customer projects...</p>
        </div>

        {{-- Customer Info Card --}}
        <div id="customerInfoCard" class="info-card customer" style="display: none;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3 id="customerName" style="margin: 0;"><i class="fas fa-building mr-2"></i></h3>
                <button id="btnPrintCustomer" class="btn btn-sm btn-primary" style="white-space: nowrap; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <i class="fas fa-print mr-1"></i> Print Report
                </button>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="info-item">
                        <i class="fas fa-tag"></i>
                        <span>Abbreviation: <strong id="customerAbb"></strong></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item">
                        <i class="fas fa-list"></i>
                        <span>Type: <strong id="customerType"></strong></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div id="statsRow" class="stats-row" style="display: none;">
            <div class="stat-card projects">
                <div class="icon">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <div class="stat-card-content">
                    <h5>Total Projects</h5>
                    <div class="number" id="totalProjects">0</div>
                </div>
            </div>
            <div class="stat-card value">
                <div class="icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-card-content">
                    <h5>Total Value</h5>
                    <div class="number" id="totalValue">0 SAR</div>
                </div>
            </div>
        </div>

        {{-- Projects Table --}}
        <div id="projectsTableCard" class="projects-table-card" style="display: none;">
            <div class="card-header" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); display: flex; justify-content: space-between; align-items: center;">
                <h4 style="margin: 0;"><i class="fas fa-list mr-2"></i>Customer Projects</h4>
                <button id="btnExportCustomer" class="btn btn-sm btn-light" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <i class="fas fa-file-excel mr-1"></i> Export to Excel
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-modern table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><i class="fas fa-hashtag mr-1"></i>PR Number</th>
                            <th><i class="fas fa-briefcase mr-1"></i>Project Name</th>
                            <th><i class="fas fa-dollar-sign mr-1"></i>Value</th>
                            <th><i class="fas fa-file-invoice mr-1"></i>Customer PO</th>
                            <th><i class="fas fa-calendar mr-1"></i>Deadline</th>
                        </tr>
                    </thead>
                    <tbody id="projectsTableBody">
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Empty State --}}
        <div id="emptyState" class="empty-state" style="display: none;">
            <i class="fas fa-folder-open"></i>
            <h4>No Projects Found</h4>
            <p>This customer doesn't have any projects yet.</p>
        </div>

        {{-- Separator between Customer and Vendor Results --}}
        <hr class="results-separator" style="display: none; margin: 30px 0; border: 0; height: 2px; background: linear-gradient(to right, transparent, #dee2e6, transparent);">

        {{-- Loading Spinner for Vendor --}}
        <div id="loadingSpinnerVendor" class="loading-spinner" style="display: none;">
            <i class="fas fa-spinner fa-spin"></i>
            <p class="mt-3">Loading vendor projects...</p>
        </div>

        {{-- Vendor Info Card --}}
        <div id="vendorInfoCard" class="info-card vendor" style="display: none;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3 id="vendorName" style="margin: 0;"><i class="fas fa-briefcase mr-2"></i></h3>
                <button id="btnPrintVendor" class="btn btn-sm btn-success" style="white-space: nowrap; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <i class="fas fa-print mr-1"></i> Print Report
                </button>
            </div>
        </div>

        {{-- Statistics Cards for Vendor --}}
        <div id="statsRowVendor" class="stats-row" style="display: none;">
            <div class="stat-card projects">
                <div class="icon">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <div class="stat-card-content">
                    <h5>Total Projects</h5>
                    <div class="number" id="totalProjectsVendor">0</div>
                </div>
            </div>
            <div class="stat-card value">
                <div class="icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-card-content">
                    <h5>Total Value</h5>
                    <div class="number" id="totalValueVendor">0 SAR</div>
                </div>
            </div>
        </div>

        {{-- Vendor Projects Table --}}
        <div id="projectsTableCardVendor" class="projects-table-card" style="display: none;">
            <div class="card-header" style="background: linear-gradient(135deg, #28a745 0%, #218838 100%); display: flex; justify-content: space-between; align-items: center;">
                <h4 style="margin: 0;"><i class="fas fa-list mr-2"></i>Vendor Projects</h4>
                <button id="btnExportVendor" class="btn btn-sm btn-light" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <i class="fas fa-file-excel mr-1"></i> Export to Excel
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-modern table-hover mb-0" id="vendorProjectsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><i class="fas fa-hashtag mr-1"></i>PR Number</th>
                            <th><i class="fas fa-briefcase mr-1"></i>Project Name</th>
                            <th><i class="fas fa-building mr-1"></i>Customer</th>
                        </tr>
                    </thead>
                    <tbody id="projectsTableBodyVendor">
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Empty State for Vendor --}}
        <div id="emptyStateVendor" class="empty-state" style="display: none;">
            <i class="fas fa-folder-open"></i>
            <h4>No Projects Found</h4>
            <p>This vendor doesn't have any projects yet.</p>
        </div>

        {{-- Separator before Supplier Results --}}
        <hr class="results-separator" style="display: none; margin: 30px 0; border: 0; height: 2px; background: linear-gradient(to right, transparent, #dee2e6, transparent);">

        {{-- Loading Spinner for Supplier --}}
        <div id="loadingSpinnerSupplier" class="loading-spinner" style="display: none;">
            <i class="fas fa-spinner fa-spin"></i>
            <p class="mt-3">Loading supplier projects...</p>
        </div>

        {{-- Supplier Info Card --}}
        <div id="supplierInfoCard" class="info-card supplier" style="display: none;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3 id="supplierName" style="margin: 0;"><i class="fas fa-truck mr-2"></i></h3>
                <button id="btnPrintSupplier" class="btn btn-sm btn-warning" style="white-space: nowrap; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <i class="fas fa-print mr-1"></i> Print Report
                </button>
            </div>
        </div>

        {{-- Statistics Cards for Supplier --}}
        <div id="statsRowSupplier" class="stats-row" style="display: none;">
            <div class="stat-card projects">
                <div class="icon">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <div class="stat-card-content">
                    <h5>Total Projects</h5>
                    <div class="number" id="totalProjectsSupplier">0</div>
                </div>
            </div>
            <div class="stat-card value">
                <div class="icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-card-content">
                    <h5>Total Value</h5>
                    <div class="number" id="totalValueSupplier">0 SAR</div>
                </div>
            </div>
        </div>

        {{-- Supplier Projects Table --}}
        <div id="projectsTableCardSupplier" class="projects-table-card" style="display: none;">
            <div class="card-header" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); display: flex; justify-content: space-between; align-items: center;">
                <h4 style="margin: 0;"><i class="fas fa-list mr-2"></i>Supplier Projects</h4>
                <button id="btnExportSupplier" class="btn btn-sm btn-light" style="box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                    <i class="fas fa-file-excel mr-1"></i> Export to Excel
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-modern table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><i class="fas fa-hashtag mr-1"></i>PR Number</th>
                            <th><i class="fas fa-briefcase mr-1"></i>Project Name</th>
                            <th><i class="fas fa-file-invoice mr-1"></i>Order Number</th>
                            <th><i class="fas fa-dollar-sign mr-1"></i>Order Value</th>
                        </tr>
                    </thead>
                    <tbody id="projectsTableBodySupplier">
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Empty State for Supplier --}}
        <div id="emptyStateSupplier" class="empty-state" style="display: none;">
            <i class="fas fa-folder-open"></i>
            <h4>No Projects Found</h4>
            <p>This supplier doesn't have any projects yet.</p>
        </div>

        {{-- Separator before PM Results --}}
        <hr class="results-separator" style="display: none; margin: 30px 0; border: 0; height: 2px; background: linear-gradient(to right, transparent, #dee2e6, transparent);">

        {{-- Loading Spinner for PM --}}
        <div id="loadingSpinnerPM" class="loading-spinner" style="display: none;">
            <i class="fas fa-spinner fa-spin"></i>
            <p class="mt-3">Loading PM projects...</p>
        </div>

        {{-- PM Info Card --}}
        <div id="pmInfoCard" class="info-card pm" style="display: none;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3 id="pmName" style="margin: 0;"><i class="fas fa-user-tie mr-2"></i></h3>
                <button id="btnPrintPM" class="btn btn-sm btn-warning" style="white-space: nowrap; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <i class="fas fa-print mr-1"></i> Print Report
                </button>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="info-item">
                        <i class="fas fa-list"></i>
                        <span>Role: <strong>Project Manager</strong></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistics Cards for PM --}}
        <div id="statsRowPM" class="stats-row" style="display: none;">
            <div class="stat-card projects">
                <div class="icon">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <div class="stat-card-content">
                    <h5>Total Projects</h5>
                    <div class="number" id="totalProjectsPM">0</div>
                </div>
            </div>
            <div class="stat-card value">
                <div class="icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-card-content">
                    <h5>Total Value</h5>
                    <div class="number" id="totalValuePM">0 SAR</div>
                </div>
            </div>
        </div>

        {{-- PM Projects Table --}}
        <div id="projectsTableCardPM" class="projects-table-card" style="display: none;">
            <div class="card-header" style="background: linear-gradient(135deg, #6f42c1 0%, #5a32a3 100%); display: flex; justify-content: space-between; align-items: center;">
                <h4 style="margin: 0;"><i class="fas fa-list mr-2"></i>PM Projects</h4>
                <button id="btnExportPM" class="btn btn-sm btn-light" style="box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                    <i class="fas fa-file-excel mr-1"></i> Export to Excel
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-modern table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><i class="fas fa-hashtag mr-1"></i>PR Number</th>
                            <th><i class="fas fa-briefcase mr-1"></i>Project Name</th>
                            <th><i class="fas fa-building mr-1"></i>Customer</th>
                            <th><i class="fas fa-dollar-sign mr-1"></i>Value</th>
                        </tr>
                    </thead>
                    <tbody id="projectsTableBodyPM">
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Empty State for PM --}}
        <div id="emptyStatePM" class="empty-state" style="display: none;">
            <i class="fas fa-folder-open"></i>
            <h4>No Projects Found</h4>
            <p>This PM doesn't have any projects yet.</p>
        </div>

        {{-- Separator before AM Results --}}
        <hr class="results-separator" style="display: none; margin: 30px 0; border: 0; height: 2px; background: linear-gradient(to right, transparent, #dee2e6, transparent);">

        {{-- Loading Spinner for AM --}}
        <div id="loadingSpinnerAM" class="loading-spinner" style="display: none;">
            <i class="fas fa-spinner fa-spin"></i>
            <p class="mt-3">Loading AM projects...</p>
        </div>

        {{-- AM Info Card --}}
        <div id="amInfoCard" class="info-card am" style="display: none;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3 id="amName" style="margin: 0;"><i class="fas fa-user-shield mr-2"></i></h3>
                <button id="btnPrintAM" class="btn btn-sm btn-warning" style="white-space: nowrap; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <i class="fas fa-print mr-1"></i> Print Report
                </button>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="info-item">
                        <i class="fas fa-list"></i>
                        <span>Role: <strong>Account Manager</strong></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistics Cards for AM --}}
        <div id="statsRowAM" class="stats-row" style="display: none;">
            <div class="stat-card projects">
                <div class="icon">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <div class="stat-card-content">
                    <h5>Total Projects</h5>
                    <div class="number" id="totalProjectsAM">0</div>
                </div>
            </div>
            <div class="stat-card value">
                <div class="icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-card-content">
                    <h5>Total Value</h5>
                    <div class="number" id="totalValueAM">0 SAR</div>
                </div>
            </div>
        </div>

        {{-- AM Projects Table --}}
        <div id="projectsTableCardAM" class="projects-table-card" style="display: none;">
            <div class="card-header" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); display: flex; justify-content: space-between; align-items: center;">
                <h4 style="margin: 0;"><i class="fas fa-list mr-2"></i>AM Projects</h4>
                <button id="btnExportAM" class="btn btn-sm btn-light" style="box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                    <i class="fas fa-file-excel mr-1"></i> Export to Excel
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-modern table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><i class="fas fa-hashtag mr-1"></i>PR Number</th>
                            <th><i class="fas fa-briefcase mr-1"></i>Project Name</th>
                            <th><i class="fas fa-building mr-1"></i>Customer</th>
                            <th><i class="fas fa-dollar-sign mr-1"></i>Value</th>
                        </tr>
                    </thead>
                    <tbody id="projectsTableBodyAM">
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Empty State for AM --}}
        <div id="emptyStateAM" class="empty-state" style="display: none;">
            <i class="fas fa-folder-open"></i>
            <h4>No Projects Found</h4>
            <p>This AM doesn't have any projects yet.</p>
        </div>
    </div> {{-- Close col-lg-9 --}}
</div> {{-- Close main row --}}
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Setup CSRF token for AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Toast notification function
    function showToast(message, type = 'success') {
        const toast = $(`
            <div class="toast-message ${type}">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'exclamation-triangle'}"></i>
                <div>
                    <strong>${type === 'success' ? 'Success' : type === 'error' ? 'Error' : 'Warning'}!</strong>
                    <p style="margin: 5px 0 0 0; font-size: 14px;">${message}</p>
                </div>
            </div>
        `);

        $('#toastContainer').append(toast);

        setTimeout(() => {
            toast.css('animation', 'slideOut 0.3s ease');
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }

    // Initialize Select2 for Customer
    $('#customerSelect').select2({
        theme: 'default',
        width: '100%',
        placeholder: '-- Select Customer --',
        allowClear: true
    });

    // Initialize Select2 for Vendor
    $('#vendorSelect').select2({
        theme: 'default',
        width: '100%',
        placeholder: '-- Select Vendor --',
        allowClear: true
    });

    // Initialize Select2 for Supplier
    $('#supplierSelect').select2({
        theme: 'default',
        width: '100%',
        placeholder: '-- Select Supplier --',
        allowClear: true
    });

    // Initialize Select2 for PM
    $('#pmSelect').select2({
        theme: 'default',
        width: '100%',
        placeholder: '-- Select PM --',
        allowClear: true
    });

    // Initialize Select2 for AM
    $('#amSelect').select2({
        theme: 'default',
        width: '100%',
        placeholder: '-- Select AM --',
        allowClear: true
    });

    // Apply Filters button
    $('#btnApplyFilters').on('click', function() {
        const customerName = $('#customerSelect').val();
        const vendorName = $('#vendorSelect').val();
        const supplierName = $('#supplierSelect').val();
        const pmName = $('#pmSelect').val();
        const amName = $('#amSelect').val();

        // Check if at least one filter is selected
        if (!customerName && !vendorName && !supplierName && !pmName && !amName) {
            showToast('Please select at least one filter', 'warning');
            return;
        }

        // Load based on selected filter(s)
        if (customerName) {
            loadCustomerProjects(customerName);
        }
        if (vendorName) {
            loadVendorProjects(vendorName);
        }
        if (supplierName) {
            loadSupplierProjects(supplierName);
        }
        if (pmName) {
            loadPMProjects(pmName);
        }
        if (amName) {
            loadAMProjects(amName);
        }
    });

    // Reset Filters button
    $('#btnResetFilters').on('click', function() {
        // Clear all selections
        $('#customerSelect').val(null).trigger('change');
        $('#vendorSelect').val(null).trigger('change');
        $('#supplierSelect').val(null).trigger('change');
        $('#pmSelect').val(null).trigger('change');
        $('#amSelect').val(null).trigger('change');

        // Hide all result sections
        $('#loadingSpinner, #customerInfoCard, #statsRow, #projectsTableCard, #emptyState').hide();
        $('#loadingSpinnerVendor, #vendorInfoCard, #statsRowVendor, #projectsTableCardVendor, #emptyStateVendor').hide();
        $('#loadingSpinnerSupplier, #supplierInfoCard, #statsRowSupplier, #projectsTableCardSupplier, #emptyStateSupplier').hide();
        $('#loadingSpinnerPM, #pmInfoCard, #statsRowPM, #projectsTableCardPM, #emptyStatePM').hide();
        $('#loadingSpinnerAM, #amInfoCard, #statsRowAM, #projectsTableCardAM, #emptyStateAM').hide();
        $('.results-separator').hide();

        showToast('Filters have been reset', 'success');
    });

    // Function to load customer projects
    function loadCustomerProjects(customerName) {
        // Disable button during loading
        $('#btnApplyFilters').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Loading...');

        // Show loading
        $('#loadingSpinner').addClass('active');
        $('#customerInfoCard, #statsRow, #projectsTableCard, #emptyState').hide();

        // Make AJAX request
        $.ajax({
            url: '{{ route("reports.customer.projects") }}',
            method: 'GET',
            data: { customer_name: customerName },
            success: function(response) {
                $('#loadingSpinner').removeClass('active');
                $('#btnApplyFilters').prop('disabled', false).html('<i class="fas fa-search mr-1"></i> Apply Filters');

                if (response.success) {
                    // Show success message based on project count
                    if (response.total_projects > 0) {
                        showToast(`Found ${response.total_projects} projects for ${customerName}`, 'success');
                    } else {
                        showToast(`No projects found for ${customerName}`, 'warning');
                    }

                    // Display customer info
                    $('#customerName').html('<i class="fas fa-building mr-2"></i>' + escapeHtml(response.customer.name));
                    $('#customerAbb').text(response.customer.abb || 'N/A');
                    $('#customerType').text(response.customer.type || 'N/A');
                    $('#customerInfoCard').fadeIn();

                    // Display statistics
                    $('#totalProjects').text(response.total_projects);
                    $('#totalValue').text(formatCurrency(response.total_value) + ' SAR');
                    $('#statsRow').fadeIn();

                    // Hide both table and empty state first
                    $('#projectsTableCard').hide();
                    $('#emptyState').hide();

                    // Display projects table or empty state
                    if (response.projects && response.projects.length > 0) {
                        let tableRows = '';
                        response.projects.forEach((project, index) => {
                            tableRows += `
                                <tr>
                                    <td><span class="badge badge-primary">${index + 1}</span></td>
                                    <td><strong>${escapeHtml(project.pr_number)}</strong></td>
                                    <td>${escapeHtml(project.name)}</td>
                                    <td><strong class="text-success">${escapeHtml(project.value)} SAR</strong></td>
                                    <td>${escapeHtml(project.customer_po || 'N/A')}</td>
                                    <td>${escapeHtml(project.deadline || 'N/A')}</td>
                                </tr>
                            `;
                        });
                        $('#projectsTableBody').html(tableRows);
                        $('#projectsTableCard').fadeIn(300);
                    } else {
                        // Show empty state for customers with no projects
                        $('#emptyState').fadeIn(300);
                    }

                    // Show separator if vendor results are visible
                    if ($('#vendorInfoCard').is(':visible') || $('#projectsTableCardVendor').is(':visible') || $('#emptyStateVendor').is(':visible')) {
                        $('.results-separator').fadeIn();
                    }
                } else {
                    showToast(response.message || 'Failed to load customer projects', 'error');
                }
            },
            error: function(xhr) {
                $('#loadingSpinner').removeClass('active');
                $('#btnApplyFilters').prop('disabled', false).html('<i class="fas fa-search mr-1"></i> Apply Filters');

                let errorMessage = 'An error occurred while loading customer projects';
                if (xhr.status === 404) {
                    errorMessage = 'Customer not found';
                } else if (xhr.status === 400) {
                    errorMessage = 'Invalid request';
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                showToast(errorMessage, 'error');
                console.error('AJAX Error:', xhr);
            }
        });
    }

    // Function to export AM projects to Excel
    function exportAmProjectsToExcel() {
        const amName = $('#amName').text().trim() || 'AM';
        const tableRows = $('#projectsTableBodyAM tr');

        if (tableRows.length === 0) {
            showToast('No projects to export', 'warning');
            return;
        }

        const projects = [];
        tableRows.each(function() {
            const row = $(this);
            const cells = row.find('td');

            projects.push({
                pr_number: cells.eq(1).text().trim(),
                name: cells.eq(2).text().trim(),
                customer: cells.eq(3).text().trim(),
                value: cells.eq(4).text().replace('SAR', '').trim()
            });
        });

        const form = $('<form>', {
            method: 'POST',
            action: '{{ route('reports.export.am.projects') }}',
            target: '_blank'
        });

        form.append($('<input>', { type: 'hidden', name: '_token', value: '{{ csrf_token() }}' }));
        form.append($('<input>', { type: 'hidden', name: 'am_name', value: amName }));
        form.append($('<input>', { type: 'hidden', name: 'projects', value: JSON.stringify(projects) }));
        form.appendTo('body').submit().remove();

        showToast('Exporting AM projects to Excel...', 'success');
    }

    // Helper function to escape HTML
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(text).replace(/[&<>"']/g, m => map[m]);
    }

    // Helper function to format currency
    function formatCurrency(value) {
        return Number(value).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }

    // Function to load vendor projects
    function loadVendorProjects(vendorName) {
        // Disable button during loading
        $('#btnApplyFilters').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Loading...');

        // Show loading
        $('#loadingSpinnerVendor').show();
        $('#vendorInfoCard, #statsRowVendor, #projectsTableCardVendor, #emptyStateVendor').hide();

        // Make AJAX request
        $.ajax({
            url: '{{ route("reports.vendor.projects") }}',
            method: 'GET',
            data: { vendor_name: vendorName },
            success: function(response) {
                $('#loadingSpinnerVendor').hide();
                $('#btnApplyFilters').prop('disabled', false).html('<i class="fas fa-search mr-1"></i> Apply Filters');

                if (response.success) {
                    // Show success message
                    if (response.total_projects > 0) {
                        showToast(`Found ${response.total_projects} projects for ${vendorName}`, 'success');
                    } else {
                        showToast(`No projects found for ${vendorName}`, 'warning');
                    }

                    // Display vendor info
                    $('#vendorName').html('<i class="fas fa-briefcase mr-2"></i>' + escapeHtml(response.vendor.name));
                    $('#vendorInfoCard').fadeIn();

                    // Display statistics
                    $('#totalProjectsVendor').text(response.total_projects);
                    $('#totalValueVendor').text(formatCurrency(response.total_value) + ' SAR');
                    $('#statsRowVendor').fadeIn();

                    // Hide both table and empty state first
                    $('#projectsTableCardVendor').hide();
                    $('#emptyStateVendor').hide();

                    // Display projects table or empty state
                    if (response.projects && response.projects.length > 0) {
                        let tableRows = '';
                        response.projects.forEach((project, index) => {
                            tableRows += `
                                <tr>
                                    <td><span class="badge badge-success">${index + 1}</span></td>
                                    <td><strong>${escapeHtml(project.pr_number)}</strong></td>
                                    <td>${escapeHtml(project.name)}</td>
                                    <td><span class="badge badge-info">${escapeHtml(project.customer_name || 'N/A')}</span></td>
                                </tr>
                            `;
                        });
                        $('#projectsTableBodyVendor').html(tableRows);
                        $('#projectsTableCardVendor').fadeIn(300);
                    } else {
                        $('#emptyStateVendor').fadeIn(300);
                    }

                    // Show separator if customer results are visible
                    if ($('#customerInfoCard').is(':visible') || $('#projectsTableCard').is(':visible') || $('#emptyState').is(':visible')) {
                        $('.results-separator').fadeIn();
                    }
                } else {
                    showToast(response.message || 'Failed to load vendor projects', 'error');
                }
            },
            error: function(xhr) {
                $('#loadingSpinnerVendor').hide();
                $('#btnApplyFilters').prop('disabled', false).html('<i class="fas fa-search mr-1"></i> Apply Filters');

                let errorMessage = 'An error occurred while loading vendor projects';
                if (xhr.status === 404) {
                    errorMessage = 'Vendor not found';
                } else if (xhr.status === 400) {
                    errorMessage = 'Invalid request';
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                showToast(errorMessage, 'error');
                console.error('AJAX Error:', xhr);
            }
        });
    }

    // Function to export vendor projects to Excel
    function exportVendorProjectsToExcel() {
        console.log('exportVendorProjectsToExcel called');

        const vendorSelectValue = $('#vendorSelect').val();
        console.log('Selected vendor:', vendorSelectValue);

        if (!vendorSelectValue) {
            showToast('Please select a vendor first', 'warning');
            return;
        }

        const tableRows = $('#projectsTableBodyVendor tr');
        if (tableRows.length === 0) {
            showToast('No vendor projects to export', 'warning');
            return;
        }

        // Disable button during export
        $('#btnExportVendor').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Exporting...');

        const form = $('<form>', {
            method: 'POST',
            action: '{{ route('reports.export.vendor.projects') }}',
            target: '_blank'
        });

        form.append($('<input>', { type: 'hidden', name: '_token', value: '{{ csrf_token() }}' }));
        form.append($('<input>', { type: 'hidden', name: 'vendor_name', value: vendorSelectValue }));

        console.log('Submitting form with vendor:', vendorSelectValue);
        form.appendTo('body').submit().remove();

        showToast('Exporting vendor projects to Excel...', 'success');

        // Re-enable button after a short delay
        setTimeout(() => {
            $('#btnExportVendor').prop('disabled', false).html('<i class="fas fa-file-excel mr-1"></i> Export to Excel');
        }, 2000);
    }

    // Function to print vendor projects
    function printVendorProjects() {
        const vendorName = $('#vendorName').text().replace(/.*?<\/i>/, '').trim() || 'Vendor';
        const totalProjects = $('#totalProjectsVendor').text().trim();
        const totalValue = $('#totalValueVendor').text().trim();
        const tableRows = $('#projectsTableBodyVendor tr');

        if (tableRows.length === 0) {
            showToast('No projects to print', 'warning');
            return;
        }

        // Disable button during print
        $('#btnPrintVendor').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Preparing...');

        let printContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Vendor Projects - ${vendorName}</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #28a745; padding-bottom: 20px; }
                    .header h1 { color: #28a745; margin: 0; font-size: 32px; }
                    .header h2 { color: #333; margin: 10px 0; }
                    .info-section { margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 5px; }
                    .stats { display: flex; justify-content: center; gap: 40px; margin: 20px 0; }
                    .stat-box { text-align: center; padding: 15px; background: #e9ecef; border-radius: 5px; min-width: 150px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th { background: #28a745; color: white; padding: 12px; text-align: left; border: 1px solid #218838; }
                    td { padding: 10px; border: 1px solid #ddd; }
                    tr:nth-child(even) { background: #f8f9fa; }
                    tr:hover { background: #e9ecef; }
                    @media print {
                        body { padding: 10px; }
                        .no-print { display: none; }
                        tr:hover { background: inherit; }
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>MDSJEDPR</h1>
                    <h2>Vendor Projects Report</h2>
                    <p>Generated: ${new Date().toLocaleString('en-US', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit',
                        hour: '2-digit',
                        minute: '2-digit'
                    })}</p>
                </div>
                <div class="info-section">
                    <h3 style="margin: 0 0 10px 0; color: #28a745;">${vendorName}</h3>
                </div>
                <div class="stats">
                    <div class="stat-box">
                        <h4 style="margin: 0 0 10px 0; color: #666;">Total Projects</h4>
                        <p style="font-size: 24px; font-weight: bold; margin: 5px 0; color: #28a745;">${totalProjects}</p>
                    </div>
                    <div class="stat-box">
                        <h4 style="margin: 0 0 10px 0; color: #666;">Total Value</h4>
                        <p style="font-size: 24px; font-weight: bold; color: #28a745; margin: 5px 0;">${totalValue}</p>
                    </div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>PR Number</th>
                            <th>Project Name</th>
                            <th>Customer</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        tableRows.each(function() {
            const row = $(this);
            const cells = row.find('td');
            printContent += `
                <tr>
                    <td style="text-align: center;">${cells.eq(0).text()}</td>
                    <td><strong>${cells.eq(1).text()}</strong></td>
                    <td>${cells.eq(2).text()}</td>
                    <td>${cells.eq(3).text()}</td>
                </tr>
            `;
        });

        printContent += `
                    </tbody>
                </table>
            </body>
            </html>
        `;

        const printWindow = window.open('', '_blank');
        printWindow.document.write(printContent);
        printWindow.document.close();
        printWindow.focus();

        setTimeout(() => {
            printWindow.print();
            $('#btnPrintVendor').prop('disabled', false).html('<i class="fas fa-print mr-1"></i> Print Report');
        }, 500);
    }

    // Bind button events
    $(document).on('click', '#btnExportVendor', function() {
        exportVendorProjectsToExcel();
    });

    $(document).on('click', '#btnPrintVendor', function() {
        printVendorProjects();
    });

    $(document).on('click', '#btnExportCustomer', function() {
        exportCustomerProjectsToExcel();
    });

    $(document).on('click', '#btnPrintCustomer', function() {
        printCustomerProjects();
    });

    $(document).on('click', '#btnExportSupplier', function() {
        exportSupplierProjectsToExcel();
    });

    $(document).on('click', '#btnPrintSupplier', function() {
        printSupplierProjects();
    });

    $(document).on('click', '#btnExportPM', function() {
        exportPMProjectsToExcel();
    });

    $(document).on('click', '#btnPrintPM', function() {
        printPMProjects();
    });

    $(document).on('click', '#btnExportAM', function() {
        exportAMProjectsToExcel();
    });

    $(document).on('click', '#btnPrintAM', function() {
        printAMProjects();
    });

    // Function to export customer projects to Excel
    function exportCustomerProjectsToExcel() {
        console.log('exportCustomerProjectsToExcel called');

        const customerSelectValue = $('#customerSelect').val();
        console.log('Selected customer:', customerSelectValue);

        if (!customerSelectValue) {
            showToast('Please select a customer first', 'warning');
            return;
        }

        const tableRows = $('#projectsTableBody tr');
        if (tableRows.length === 0) {
            showToast('No customer projects to export', 'warning');
            return;
        }

        // Disable button during export
        $('#btnExportCustomer').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Exporting...');

        const form = $('<form>', {
            method: 'POST',
            action: '{{ route('reports.export.customer.projects') }}',
            target: '_blank'
        });

        form.append($('<input>', { type: 'hidden', name: '_token', value: '{{ csrf_token() }}' }));
        form.append($('<input>', { type: 'hidden', name: 'customer_name', value: customerSelectValue }));

        console.log('Submitting form with customer:', customerSelectValue);
        form.appendTo('body').submit().remove();

        showToast('Exporting customer projects to Excel...', 'success');

        // Re-enable button after a short delay
        setTimeout(() => {
            $('#btnExportCustomer').prop('disabled', false).html('<i class="fas fa-file-excel mr-1"></i> Export to Excel');
        }, 2000);
    }

    // Function to print customer projects
    function printCustomerProjects() {
        const customerName = $('#customerName').text().replace(/.*?<\/i>/, '').trim() || 'Customer';
        const customerAbb = $('#customerAbb').text().trim();
        const customerType = $('#customerType').text().trim();
        const totalProjects = $('#totalProjects').text().trim();
        const totalValue = $('#totalValue').text().trim();
        const tableRows = $('#projectsTableBody tr');

        if (tableRows.length === 0) {
            showToast('No projects to print', 'warning');
            return;
        }

        // Disable button during print
        $('#btnPrintCustomer').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Preparing...');

        let printContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Customer Projects - ${customerName}</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #007bff; padding-bottom: 20px; }
                    .header h1 { color: #007bff; margin: 0; font-size: 32px; }
                    .header h2 { color: #333; margin: 10px 0; }
                    .info-section { margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 5px; }
                    .info-row { display: flex; gap: 30px; margin: 10px 0; }
                    .info-item { flex: 1; }
                    .stats { display: flex; justify-content: center; gap: 40px; margin: 20px 0; }
                    .stat-box { text-align: center; padding: 15px; background: #e9ecef; border-radius: 5px; min-width: 150px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th { background: #007bff; color: white; padding: 12px; text-align: left; border: 1px solid #0056b3; }
                    td { padding: 10px; border: 1px solid #ddd; }
                    tr:nth-child(even) { background: #f8f9fa; }
                    tr:hover { background: #e9ecef; }
                    @media print {
                        body { padding: 10px; }
                        .no-print { display: none; }
                        tr:hover { background: inherit; }
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>MDSJEDPR</h1>
                    <h2>Customer Projects Report</h2>
                    <p>Generated: ${new Date().toLocaleString('en-US', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit',
                        hour: '2-digit',
                        minute: '2-digit'
                    })}</p>
                </div>
                <div class="info-section">
                    <h3 style="margin: 0 0 10px 0; color: #007bff;">${customerName}</h3>
                    <div class="info-row">
                        <div class="info-item"><strong>Abbreviation:</strong> ${customerAbb}</div>
                        <div class="info-item"><strong>Type:</strong> ${customerType}</div>
                    </div>
                </div>
                <div class="stats">
                    <div class="stat-box">
                        <h4 style="margin: 0 0 10px 0; color: #666;">Total Projects</h4>
                        <p style="font-size: 24px; font-weight: bold; margin: 5px 0; color: #007bff;">${totalProjects}</p>
                    </div>
                    <div class="stat-box">
                        <h4 style="margin: 0 0 10px 0; color: #666;">Total Value</h4>
                        <p style="font-size: 24px; font-weight: bold; color: #007bff; margin: 5px 0;">${totalValue}</p>
                    </div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>PR Number</th>
                            <th>Project Name</th>
                            <th>Value</th>
                            <th>Customer PO</th>
                            <th>Deadline</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        tableRows.each(function() {
            const row = $(this);
            const cells = row.find('td');
            printContent += `
                <tr>
                    <td style="text-align: center;">${cells.eq(0).text()}</td>
                    <td><strong>${cells.eq(1).text()}</strong></td>
                    <td>${cells.eq(2).text()}</td>
                    <td>${cells.eq(3).text()}</td>
                    <td>${cells.eq(4).text()}</td>
                    <td>${cells.eq(5).text()}</td>
                </tr>
            `;
        });

        printContent += `
                    </tbody>
                </table>
            </body>
            </html>
        `;

        const printWindow = window.open('', '_blank');
        printWindow.document.write(printContent);
        printWindow.document.close();
        printWindow.focus();

        setTimeout(() => {
            printWindow.print();
            $('#btnPrintCustomer').prop('disabled', false).html('<i class="fas fa-print mr-1"></i> Print Report');
        }, 500);
    }

    // Function to export supplier projects to Excel
    function exportSupplierProjectsToExcel() {
        const supplierName = $('#supplierName').text().replace(/<\/?i[^>]*>/g, '').trim() || 'Supplier';
        const tableRows = $('#projectsTableBodySupplier tr');

        if (tableRows.length === 0) {
            showToast('No projects to export', 'warning');
            return;
        }

        const projects = [];
        tableRows.each(function() {
            const cells = $(this).find('td');
            projects.push({
                pr_number: cells.eq(1).text().trim(),
                name: cells.eq(2).text().trim(),
                po_number: cells.eq(3).text().trim(),
                value: cells.eq(4).text().replace('SAR', '').trim()
            });
        });

        const form = $('<form>', {
            method: 'POST',
            action: '{{ route("reports.export.supplier.projects") }}',
            target: '_blank'
        });

        form.append($('<input>', { type: 'hidden', name: '_token', value: '{{ csrf_token() }}' }));
        form.append($('<input>', { type: 'hidden', name: 'supplier_name', value: supplierName }));
        form.append($('<input>', { type: 'hidden', name: 'projects', value: JSON.stringify(projects) }));
        form.appendTo('body').submit().remove();

        showToast('Exporting supplier projects to Excel...', 'success');
    }

    // Function to print supplier projects
    function printSupplierProjects() {
        const supplierName = $('#supplierName').text().replace(/<\/?i[^>]*>/g, '').trim() || 'Supplier';
        const totalProjects = $('#totalProjectsSupplier').text().trim();
        const totalValue = $('#totalValueSupplier').text().trim();
        const tableRows = $('#projectsTableBodySupplier tr');

        if (tableRows.length === 0) {
            showToast('No projects to print', 'warning');
            return;
        }

        // Disable button during print
        $('#btnPrintSupplier').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Preparing...');

        let printContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Supplier Projects - ${supplierName}</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #ffc107; padding-bottom: 20px; }
                    .header h1 { color: #ffc107; margin: 0; font-size: 32px; }
                    .header h2 { color: #333; margin: 10px 0; }
                    .info-section { margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 5px; }
                    .stats { display: flex; justify-content: center; gap: 40px; margin: 20px 0; }
                    .stat-box { text-align: center; padding: 15px; background: #e9ecef; border-radius: 5px; min-width: 150px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th { background: #ffc107; color: white; padding: 12px; text-align: left; border: 1px solid #ff9800; }
                    td { padding: 10px; border: 1px solid #ddd; }
                    tr:nth-child(even) { background: #f8f9fa; }
                    tr:hover { background: #e9ecef; }
                    @media print {
                        body { padding: 10px; }
                        .no-print { display: none; }
                        tr:hover { background: inherit; }
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>MDSJEDPR</h1>
                    <h2>Supplier Projects Report</h2>
                    <p>Generated: ${new Date().toLocaleString('en-US', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit',
                        hour: '2-digit',
                        minute: '2-digit'
                    })}</p>
                </div>
                <div class="info-section">
                    <h3 style="margin: 0 0 10px 0; color: #ffc107;">${supplierName}</h3>
                </div>
                <div class="stats">
                    <div class="stat-box">
                        <h4 style="margin: 0 0 10px 0; color: #666;">Total Projects</h4>
                        <p style="font-size: 24px; font-weight: bold; margin: 5px 0; color: #ffc107;">${totalProjects}</p>
                    </div>
                    <div class="stat-box">
                        <h4 style="margin: 0 0 10px 0; color: #666;">Total Value</h4>
                        <p style="font-size: 24px; font-weight: bold; color: #ffc107; margin: 5px 0;">${totalValue}</p>
                    </div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>PR Number</th>
                            <th>Project Name</th>
                            <th>Order Number</th>
                            <th>Order Value</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        tableRows.each(function(index) {
            const cells = $(this).find('td');
            printContent += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${cells.eq(1).text().trim()}</td>
                    <td>${cells.eq(2).text().trim()}</td>
                    <td>${cells.eq(3).text().trim()}</td>
                    <td>${cells.eq(4).text().trim()}</td>
                </tr>
            `;
        });

        printContent += `
                    </tbody>
                </table>
            </body>
            </html>
        `;

        const printWindow = window.open('', '_blank');
        printWindow.document.write(printContent);
        printWindow.document.close();

        printWindow.onload = function() {
            printWindow.print();
            $('#btnPrintSupplier').prop('disabled', false).html('<i class="fas fa-print mr-1"></i> Print Report');
        };

        showToast('Preparing supplier report for printing...', 'success');
    }

    // Function to export PM projects to Excel
    function exportPMProjectsToExcel() {
        const pmName = $('#pmName').text().replace(/<\/?i[^>]*>/g, '').trim() || 'PM';
        const tableRows = $('#projectsTableBodyPM tr');

        if (tableRows.length === 0) {
            showToast('No projects to export', 'warning');
            return;
        }

        const projects = [];
        tableRows.each(function() {
            const cells = $(this).find('td');
            projects.push({
                pr_number: cells.eq(1).text().trim(),
                name: cells.eq(2).text().trim(),
                customer_name: cells.eq(3).text().trim(),
                value: cells.eq(4).text().replace('SAR', '').trim()
            });
        });

        const form = $('<form>', {
            method: 'POST',
            action: '{{ route("reports.export.pm.projects") }}',
            target: '_blank'
        });

        form.append($('<input>', { type: 'hidden', name: '_token', value: '{{ csrf_token() }}' }));
        form.append($('<input>', { type: 'hidden', name: 'pm_name', value: pmName }));
        form.append($('<input>', { type: 'hidden', name: 'projects', value: JSON.stringify(projects) }));
        form.appendTo('body').submit().remove();

        showToast('Exporting PM projects to Excel...', 'success');
    }

    // Function to print PM projects
    function printPMProjects() {
        const pmName = $('#pmName').text().replace(/<\/?i[^>]*>/g, '').trim() || 'PM';
        const totalProjects = $('#totalProjectsPM').text().trim();
        const totalValue = $('#totalValuePM').text().trim();
        const tableRows = $('#projectsTableBodyPM tr');

        if (tableRows.length === 0) {
            showToast('No projects to print', 'warning');
            return;
        }

        // Disable button during print
        $('#btnPrintPM').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Preparing...');

        let printContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>PM Projects - ${pmName}</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #6f42c1; padding-bottom: 20px; }
                    .header h1 { color: #6f42c1; margin: 0; font-size: 32px; }
                    .header h2 { color: #333; margin: 10px 0; }
                    .info-section { margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 5px; }
                    .stats { display: flex; justify-content: center; gap: 40px; margin: 20px 0; }
                    .stat-box { text-align: center; padding: 15px; background: #e9ecef; border-radius: 5px; min-width: 150px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th { background: #6f42c1; color: white; padding: 12px; text-align: left; border: 1px solid #5a32a3; }
                    td { padding: 10px; border: 1px solid #ddd; }
                    tr:nth-child(even) { background: #f8f9fa; }
                    tr:hover { background: #e9ecef; }
                    @media print {
                        body { padding: 10px; }
                        .no-print { display: none; }
                        tr:hover { background: inherit; }
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>MDSJEDPR</h1>
                    <h2>PM Projects Report</h2>
                    <p>Generated: ${new Date().toLocaleString('en-US', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit',
                        hour: '2-digit',
                        minute: '2-digit'
                    })}</p>
                </div>
                <div class="info-section">
                    <h3 style="margin: 0 0 10px 0; color: #6f42c1;">${pmName}</h3>
                    <p style="margin: 5px 0;"><strong>Role:</strong> Project Manager</p>
                </div>
                <div class="stats">
                    <div class="stat-box">
                        <h4 style="margin: 0 0 10px 0; color: #666;">Total Projects</h4>
                        <p style="font-size: 24px; font-weight: bold; margin: 5px 0; color: #6f42c1;">${totalProjects}</p>
                    </div>
                    <div class="stat-box">
                        <h4 style="margin: 0 0 10px 0; color: #666;">Total Value</h4>
                        <p style="font-size: 24px; font-weight: bold; color: #6f42c1; margin: 5px 0;">${totalValue}</p>
                    </div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>PR Number</th>
                            <th>Project Name</th>
                            <th>Customer</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        tableRows.each(function(index) {
            const cells = $(this).find('td');
            printContent += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${cells.eq(1).text().trim()}</td>
                    <td>${cells.eq(2).text().trim()}</td>
                    <td>${cells.eq(3).text().trim()}</td>
                    <td>${cells.eq(4).text().trim()}</td>
                </tr>
            `;
        });

        printContent += `
                    </tbody>
                </table>
            </body>
            </html>
        `;

        const printWindow = window.open('', '_blank');
        printWindow.document.write(printContent);
        printWindow.document.close();

        printWindow.onload = function() {
            printWindow.print();
            $('#btnPrintPM').prop('disabled', false).html('<i class="fas fa-print mr-1"></i> Print Report');
        };

        showToast('Preparing PM report for printing...', 'success');
    }

    // Function to export AM projects to Excel
    function exportAMProjectsToExcel() {
        const amName = $('#amName').text().replace(/<\/?i[^>]*>/g, '').trim() || 'AM';
        const tableRows = $('#projectsTableBodyAM tr');

        if (tableRows.length === 0) {
            showToast('No projects to export', 'warning');
            return;
        }

        const projects = [];
        tableRows.each(function() {
            const cells = $(this).find('td');
            projects.push({
                pr_number: cells.eq(1).text().trim(),
                name: cells.eq(2).text().trim(),
                customer_name: cells.eq(3).text().trim(),
                value: cells.eq(4).text().replace('SAR', '').trim()
            });
        });

        const form = $('<form>', {
            method: 'POST',
            action: '{{ route("reports.export.am.projects") }}',
            target: '_blank'
        });

        form.append($('<input>', { type: 'hidden', name: '_token', value: '{{ csrf_token() }}' }));
        form.append($('<input>', { type: 'hidden', name: 'am_name', value: amName }));
        form.append($('<input>', { type: 'hidden', name: 'projects', value: JSON.stringify(projects) }));
        form.appendTo('body').submit().remove();

        showToast('Exporting AM projects to Excel...', 'success');
    }

    // Function to print AM projects
    function printAMProjects() {
        const amName = $('#amName').text().replace(/<\/?i[^>]*>/g, '').trim() || 'AM';
        const totalProjects = $('#totalProjectsAM').text().trim();
        const totalValue = $('#totalValueAM').text().trim();
        const tableRows = $('#projectsTableBodyAM tr');

        if (tableRows.length === 0) {
            showToast('No projects to print', 'warning');
            return;
        }

        // Disable button during print
        $('#btnPrintAM').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Preparing...');

        let printContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>AM Projects - ${amName}</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #17a2b8; padding-bottom: 20px; }
                    .header h1 { color: #17a2b8; margin: 0; font-size: 32px; }
                    .header h2 { color: #333; margin: 10px 0; }
                    .info-section { margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 5px; }
                    .stats { display: flex; justify-content: center; gap: 40px; margin: 20px 0; }
                    .stat-box { text-align: center; padding: 15px; background: #e9ecef; border-radius: 5px; min-width: 150px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th { background: #17a2b8; color: white; padding: 12px; text-align: left; border: 1px solid #138496; }
                    td { padding: 10px; border: 1px solid #ddd; }
                    tr:nth-child(even) { background: #f8f9fa; }
                    tr:hover { background: #e9ecef; }
                    @media print {
                        body { padding: 10px; }
                        .no-print { display: none; }
                        tr:hover { background: inherit; }
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>MDSJEDPR</h1>
                    <h2>AM Projects Report</h2>
                    <p>Generated: ${new Date().toLocaleString('en-US', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit',
                        hour: '2-digit',
                        minute: '2-digit'
                    })}</p>
                </div>
                <div class="info-section">
                    <h3 style="margin: 0 0 10px 0; color: #17a2b8;">${amName}</h3>
                    <p style="margin: 5px 0;"><strong>Role:</strong> Account Manager</p>
                </div>
                <div class="stats">
                    <div class="stat-box">
                        <h4 style="margin: 0 0 10px 0; color: #666;">Total Projects</h4>
                        <p style="font-size: 24px; font-weight: bold; margin: 5px 0; color: #17a2b8;">${totalProjects}</p>
                    </div>
                    <div class="stat-box">
                        <h4 style="margin: 0 0 10px 0; color: #666;">Total Value</h4>
                        <p style="font-size: 24px; font-weight: bold; color: #17a2b8; margin: 5px 0;">${totalValue}</p>
                    </div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>PR Number</th>
                            <th>Project Name</th>
                            <th>Customer</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        tableRows.each(function(index) {
            const cells = $(this).find('td');
            printContent += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${cells.eq(1).text().trim()}</td>
                    <td>${cells.eq(2).text().trim()}</td>
                    <td>${cells.eq(3).text().trim()}</td>
                    <td>${cells.eq(4).text().trim()}</td>
                </tr>
            `;
        });

        printContent += `
                    </tbody>
                </table>
            </body>
            </html>
        `;

        const printWindow = window.open('', '_blank');
        printWindow.document.write(printContent);
        printWindow.document.close();

        printWindow.onload = function() {
            printWindow.print();
            $('#btnPrintAM').prop('disabled', false).html('<i class="fas fa-print mr-1"></i> Print Report');
        };

        showToast('Preparing AM report for printing...', 'success');
    }

    // Function to load supplier projects
    function loadSupplierProjects(supplierName) {
        // Disable button during loading
        $('#btnApplyFilters').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Loading...');

        // Show loading
        $('#loadingSpinnerSupplier').show();
        $('#supplierInfoCard, #statsRowSupplier, #projectsTableCardSupplier, #emptyStateSupplier').hide();

        // Make AJAX request
        $.ajax({
            url: '{{ route("reports.supplier.projects") }}',
            method: 'GET',
            data: { supplier_name: supplierName },
            success: function(response) {
                $('#loadingSpinnerSupplier').hide();
                $('#btnApplyFilters').prop('disabled', false).html('<i class="fas fa-search mr-1"></i> Apply Filters');

                if (response.success) {
                    // Show success message
                    if (response.total_projects > 0) {
                        showToast(`Found ${response.total_projects} projects for ${supplierName}`, 'success');
                    } else {
                        showToast(`No projects found for ${supplierName}`, 'warning');
                    }

                    // Display supplier info
                    $('#supplierName').html('<i class="fas fa-truck mr-2"></i>' + escapeHtml(response.supplier.name));
                    $('#supplierInfoCard').fadeIn();

                    // Display statistics
                    $('#totalProjectsSupplier').text(response.total_projects);
                    $('#totalValueSupplier').text(formatCurrency(response.total_value) + ' SAR');
                    $('#statsRowSupplier').fadeIn();

                    // Hide both table and empty state first
                    $('#projectsTableCardSupplier').hide();
                    $('#emptyStateSupplier').hide();

                    // Display projects table or empty state
                    if (response.projects && response.projects.length > 0) {
                        let tableRows = '';
                        response.projects.forEach((project, index) => {
                            tableRows += `
                                <tr>
                                    <td><span class="badge badge-warning">${index + 1}</span></td>
                                    <td><strong class="text-primary">${escapeHtml(project.pr_number)}</strong></td>
                                    <td><strong>${escapeHtml(project.name)}</strong></td>
                                    <td><span class="badge badge-info">${escapeHtml(project.po_number)}</span></td>
                                    <td><strong class="text-success" style="font-size: 1.1em;">${escapeHtml(project.value)} SAR</strong></td>
                                </tr>
                            `;
                        });
                        $('#projectsTableBodySupplier').html(tableRows);
                        $('#projectsTableCardSupplier').fadeIn(300);
                    } else {
                        $('#emptyStateSupplier').fadeIn(300);
                    }

                    // Show separator if other results are visible
                    if ($('#customerInfoCard').is(':visible') || $('#projectsTableCard').is(':visible') || $('#emptyState').is(':visible') ||
                        $('#vendorInfoCard').is(':visible') || $('#projectsTableCardVendor').is(':visible') || $('#emptyStateVendor').is(':visible')) {
                        $('.results-separator').fadeIn();
                    }
                } else {
                    showToast(response.message || 'Failed to load supplier projects', 'error');
                }
            },
            error: function(xhr) {
                $('#loadingSpinnerSupplier').hide();
                $('#btnApplyFilters').prop('disabled', false).html('<i class="fas fa-search mr-1"></i> Apply Filters');

                let errorMessage = 'An error occurred while loading supplier projects';
                if (xhr.status === 404) {
                    errorMessage = 'Supplier not found';
                } else if (xhr.status === 400) {
                    errorMessage = 'Invalid request';
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                showToast(errorMessage, 'error');
                console.error('AJAX Error:', xhr);
            }
        });
    }

    // Function to load PM projects
    function loadPMProjects(pmName) {
        // Disable button during loading
        $('#btnApplyFilters').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Loading...');

        // Show loading
        $('#loadingSpinnerPM').show();
        $('#pmInfoCard, #statsRowPM, #projectsTableCardPM, #emptyStatePM').hide();

        // Make AJAX request
        $.ajax({
            url: '{{ route("reports.pm.projects") }}',
            method: 'GET',
            data: { pm_name: pmName },
            success: function(response) {
                $('#loadingSpinnerPM').hide();
                $('#btnApplyFilters').prop('disabled', false).html('<i class="fas fa-search mr-1"></i> Apply Filters');

                if (response.success) {
                    // Show success message
                    if (response.total_projects > 0) {
                        showToast(`Found ${response.total_projects} projects for ${pmName}`, 'success');
                    } else {
                        showToast(`No projects found for ${pmName}`, 'warning');
                    }

                    // Display PM info
                    $('#pmName').html('<i class="fas fa-user-tie mr-2"></i>' + escapeHtml(response.pm.name));
                    $('#pmInfoCard').fadeIn();

                    // Display statistics
                    $('#totalProjectsPM').text(response.total_projects);
                    $('#totalValuePM').text(formatCurrency(response.total_value) + ' SAR');
                    $('#statsRowPM').fadeIn();

                    // Hide both table and empty state first
                    $('#projectsTableCardPM').hide();
                    $('#emptyStatePM').hide();

                    // Display projects table or empty state
                    if (response.projects && response.projects.length > 0) {
                        let tableRows = '';
                        response.projects.forEach((project, index) => {
                            tableRows += `
                                <tr>
                                    <td><span class="badge badge-secondary">${index + 1}</span></td>
                                    <td><strong class="text-primary">${escapeHtml(project.pr_number)}</strong></td>
                                    <td><strong>${escapeHtml(project.name)}</strong></td>
                                    <td><span class="badge badge-info">${escapeHtml(project.customer_name)}</span></td>
                                    <td><strong class="text-success" style="font-size: 1.1em;">${escapeHtml(project.value)} SAR</strong></td>
                                </tr>
                            `;
                        });
                        $('#projectsTableBodyPM').html(tableRows);
                        $('#projectsTableCardPM').fadeIn(300);
                    } else {
                        $('#emptyStatePM').fadeIn(300);
                    }

                    // Show separator if other results are visible
                    if ($('#customerInfoCard').is(':visible') || $('#projectsTableCard').is(':visible') || $('#emptyState').is(':visible') ||
                        $('#vendorInfoCard').is(':visible') || $('#projectsTableCardVendor').is(':visible') || $('#emptyStateVendor').is(':visible') ||
                        $('#supplierInfoCard').is(':visible') || $('#projectsTableCardSupplier').is(':visible') || $('#emptyStateSupplier').is(':visible')) {
                        $('.results-separator').fadeIn();
                    }
                } else {
                    showToast(response.message || 'Failed to load PM projects', 'error');
                }
            },
            error: function(xhr) {
                $('#loadingSpinnerPM').hide();
                $('#btnApplyFilters').prop('disabled', false).html('<i class="fas fa-search mr-1"></i> Apply Filters');

                let errorMessage = 'An error occurred while loading PM projects';
                if (xhr.status === 404) {
                    errorMessage = 'PM not found';
                } else if (xhr.status === 400) {
                    errorMessage = 'Invalid request';
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                showToast(errorMessage, 'error');
                console.error('AJAX Error:', xhr);
            }
        });
    }

    // Function to load AM projects
    function loadAMProjects(amName) {
        // Disable button during loading
        $('#btnApplyFilters').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Loading...');

        // Show loading
        $('#loadingSpinnerAM').show();
        $('#amInfoCard, #statsRowAM, #projectsTableCardAM, #emptyStateAM').hide();

        // Make AJAX request
        $.ajax({
            url: '{{ route("reports.am.projects") }}',
            method: 'GET',
            data: { am_name: amName },
            success: function(response) {
                $('#loadingSpinnerAM').hide();
                $('#btnApplyFilters').prop('disabled', false).html('<i class="fas fa-search mr-1"></i> Apply Filters');

                if (response.success) {
                    // Show success message
                    if (response.total_projects > 0) {
                        showToast(`Found ${response.total_projects} projects for ${amName}`, 'success');
                    } else {
                        showToast(`No projects found for ${amName}`, 'warning');
                    }

                    // Display AM info
                    $('#amName').html('<i class="fas fa-user-shield mr-2"></i>' + escapeHtml(response.am.name));
                    $('#amInfoCard').fadeIn();

                    // Display statistics
                    $('#totalProjectsAM').text(response.total_projects);
                    $('#totalValueAM').text(formatCurrency(response.total_value) + ' SAR');
                    $('#statsRowAM').fadeIn();

                    // Hide both table and empty state first
                    $('#projectsTableCardAM').hide();
                    $('#emptyStateAM').hide();

                    // Display projects table or empty state
                    if (response.projects && response.projects.length > 0) {
                        let tableRows = '';
                        response.projects.forEach((project, index) => {
                            tableRows += `
                                <tr>
                                    <td><span class="badge badge-info">${index + 1}</span></td>
                                    <td><strong class="text-primary">${escapeHtml(project.pr_number)}</strong></td>
                                    <td><strong>${escapeHtml(project.name)}</strong></td>
                                    <td><span class="badge badge-info">${escapeHtml(project.customer_name)}</span></td>
                                    <td><strong class="text-success" style="font-size: 1.1em;">${escapeHtml(project.value)} SAR</strong></td>
                                </tr>
                            `;
                        });
                        $('#projectsTableBodyAM').html(tableRows);
                        $('#projectsTableCardAM').fadeIn(300);
                    } else {
                        $('#emptyStateAM').fadeIn(300);
                    }

                    // Show separator if other results are visible
                    if ($('#customerInfoCard').is(':visible') || $('#projectsTableCard').is(':visible') || $('#emptyState').is(':visible') ||
                        $('#vendorInfoCard').is(':visible') || $('#projectsTableCardVendor').is(':visible') || $('#emptyStateVendor').is(':visible') ||
                        $('#supplierInfoCard').is(':visible') || $('#projectsTableCardSupplier').is(':visible') || $('#emptyStateSupplier').is(':visible') ||
                        $('#pmInfoCard').is(':visible') || $('#projectsTableCardPM').is(':visible') || $('#emptyStatePM').is(':visible')) {
                        $('.results-separator').fadeIn();
                    }
                } else {
                    showToast(response.message || 'Failed to load AM projects', 'error');
                }
            },
            error: function(xhr) {
                $('#loadingSpinnerAM').hide();
                $('#btnApplyFilters').prop('disabled', false).html('<i class="fas fa-search mr-1"></i> Apply Filters');

                let errorMessage = 'An error occurred while loading AM projects';
                if (xhr.status === 404) {
                    errorMessage = 'AM not found';
                } else if (xhr.status === 400) {
                    errorMessage = 'Invalid request';
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                showToast(errorMessage, 'error');
                console.error('AJAX Error:', xhr);
            }
        });
    }
});
</script>
@endsection
