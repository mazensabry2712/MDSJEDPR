@extends('layouts.master')
@section('title')
    View PPO
@stop
@section('css')
    <style>
        .ppo-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 30px;
            border-radius: 10px 10px 0 0;
            margin: -20px -20px 20px -20px;
        }

        .info-card {
            border-left: 4px solid #007bff;
            padding: 15px;
            margin-bottom: 15px;
            background: #f8f9fa;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 8px rgba(0,123,255,0.2);
        }

        .info-label {
            font-weight: 600;
            color: #6c757d;
            font-size: 13px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 16px;
            color: #495057;
            font-weight: 500;
        }

        .export-buttons .btn {
            margin: 5px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .export-buttons .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,123,255,0.3);
        }

        .quick-actions {
            position: sticky;
            top: 20px;
        }

        .action-btn {
            width: 100%;
            margin-bottom: 10px;
            border-radius: 8px;
            padding: 12px;
            font-weight: 500;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            .ppo-header {
                background: #007bff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        .badge-custom {
            padding: 8px 15px;
            font-size: 14px;
            border-radius: 20px;
        }

        .detail-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #007bff;
            margin-bottom: 15px;
        }

        .detail-box pre {
            background: white;
            padding: 10px;
            border-radius: 5px;
            margin: 0;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between no-print">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Project Purchase Orders</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ View</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-9">
            <div class="card">
                <div class="ppo-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-2">
                                <i class="fas fa-file-invoice-dollar mr-2"></i>Purchase Order Details
                            </h3>
                            <p class="mb-0" style="opacity: 0.9;">
                                View complete PPO information and order details
                            </p>
                        </div>
                        <div class="text-right">
                            <i class="fas fa-shopping-cart" style="font-size: 60px; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Project Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">
                                    <i class="fas fa-hashtag mr-1"></i>PR Number
                                </div>
                                <div class="info-value">
                                    <span class="badge badge-primary badge-custom">
                                        {{ $ppo->project->pr_number ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">
                                    <i class="fas fa-project-diagram mr-1"></i>Project Name
                                </div>
                                <div class="info-value">
                                    {{ $ppo->project->name ?? 'No project assigned' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">
                                    <i class="fas fa-receipt mr-1"></i>PO Number
                                </div>
                                <div class="info-value text-primary">
                                    <strong>{{ $ppo->po_number }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">
                                    <i class="fas fa-tags mr-1"></i>Categories
                                </div>
                                <div class="info-value">
                                    @php
                                        // Get all categories for this PO Number
                                        $allPpoCategories = \App\Models\Ppos::where('po_number', $ppo->po_number)
                                            ->with('pepo:id,category')
                                            ->get();

                                        $categories = $allPpoCategories->pluck('pepo.category')->filter()->unique();
                                    @endphp

                                    @if($categories->count() > 0)
                                        @foreach($categories as $category)
                                            <span class="badge badge-primary mr-1 mb-1" style="font-size: 0.9rem; padding: 0.4rem 0.8rem;">
                                                {{ $category }}
                                            </span>
                                        @endforeach
                                        <br>
                                        <small class="text-muted">{{ $categories->count() }} categor{{ $categories->count() > 1 ? 'ies' : 'y' }}</small>
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">
                                    <i class="fas fa-truck mr-1"></i>Supplier Name
                                </div>
                                <div class="info-value">
                                    {{ $ppo->ds->dsname ?? 'N/A' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">
                                    <i class="fas fa-dollar-sign mr-1"></i>Value
                                </div>
                                <div class="info-value text-success">
                                    @if($ppo->value)
                                        {{ number_format($ppo->value, 2) }} SAR
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">
                                    <i class="fas fa-calendar-alt mr-1"></i>Date
                                </div>
                                <div class="info-value">
                                    {{ $ppo->date ? $ppo->date->format('Y-m-d') : 'N/A' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">
                                    <i class="fas fa-calendar-plus mr-1"></i>Created Date
                                </div>
                                <div class="info-value">
                                    {{ $ppo->created_at->format('Y-m-d H:i A') }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">
                                    <i class="fas fa-calendar-check mr-1"></i>Last Updated
                                </div>
                                <div class="info-value">
                                    {{ $ppo->updated_at->format('Y-m-d H:i A') }}
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-md-12">
                            <div class="detail-box">
                                <div class="info-label">
                                    <i class="fas fa-info-circle mr-1"></i>Status
                                </div>
                                <pre class="mb-0">{{ $ppo->status ?? 'No status information' }}</pre>
                            </div>
                        </div>

                        <!-- Updates -->
                        <div class="col-md-12">
                            <div class="detail-box">
                                <div class="info-label">
                                    <i class="fas fa-sync-alt mr-1"></i>Updates
                                </div>
                                <pre class="mb-0">{{ $ppo->updates ?? 'No updates available' }}</pre>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="col-md-12">
                            <div class="detail-box">
                                <div class="info-label">
                                    <i class="fas fa-sticky-note mr-1"></i>Notes
                                </div>
                                <pre class="mb-0">{{ $ppo->notes ?? 'No notes available' }}</pre>
                            </div>
                        </div>
                    </div>

                    <!-- Export Buttons -->
                    <div class="export-buttons mt-4 text-center no-print">
                        <button class="btn btn-danger" onclick="exportToPDF()">
                            <i class="fas fa-file-pdf mr-2"></i>Export PDF
                        </button>
                        <button class="btn btn-success" onclick="exportToExcel()">
                            <i class="fas fa-file-excel mr-2"></i>Export Excel
                        </button>
                        {{-- <button class="btn btn-info" onclick="exportToCSV()">
                            <i class="fas fa-file-csv mr-2"></i>Export CSV
                        </button> --}}
                        <button class="btn btn-secondary" onclick="window.print()">
                            <i class="fas fa-print mr-2"></i>Print
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Sidebar -->
        <div class="col-lg-3 no-print">
            <div class="card quick-actions">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-bolt mr-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    @can('Edit')
                        <a href="{{ route('ppos.edit', $ppo->id) }}" class="btn btn-info action-btn">
                            <i class="fas fa-edit mr-2"></i>Edit PPO
                        </a>
                    @endcan

                    <a href="{{ route('ppos.index') }}" class="btn btn-primary action-btn">
                        <i class="fas fa-list mr-2"></i>Back to List
                    </a>

                    @can('Delete')
                        <button type="button" class="btn btn-danger action-btn" data-toggle="modal" data-target="#deleteModal">
                            <i class="fas fa-trash mr-2"></i>Delete PPO
                        </button>
                    @endcan
                </div>
            </div>

            <!-- Order Summary Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-line mr-2"></i>Order Summary
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">PO Number</small>
                        <h6 class="text-primary mb-0">{{ $ppo->po_number }}</h6>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Total Value</small>
                        <h5 class="text-success mb-0">
                            @if($ppo->value)
                                {{ number_format($ppo->value, 2) }} SAR
                            @else
                                N/A
                            @endif
                        </h5>
                    </div>
                    <div>
                        <small class="text-muted d-block">Supplier</small>
                        <h6 class="mb-0">{{ $ppo->ds->dsname ?? 'N/A' }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    @can('Delete')
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle text-danger mr-2"></i>Confirm Delete
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('ppos/destroy') }}" method="post">
                    @csrf
                    @method('delete')
                    <div class="modal-body">
                        <p>Are you sure you want to delete this PPO record?</p>
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle mr-2"></i>
                            This action cannot be undone!
                        </div>
                        <input type="hidden" name="id" value="{{ $ppo->id }}">
                        <input type="hidden" name="name" value="{{ $ppo->po_number }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash mr-2"></i>Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan
@endsection

@section('js')
    <!-- jsPDF with autoTable -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>

    <!-- SheetJS for Excel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>
        // Export to PDF
        function exportToPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            doc.setFontSize(18);
            doc.text('PPO Details', 14, 20);
            doc.setFontSize(11);
            doc.text('Generated on: ' + new Date().toLocaleDateString(), 14, 28);

            @php
                $categoriesForExport = \App\Models\Ppos::where('po_number', $ppo->po_number)
                    ->with('pepo:id,category')
                    ->get()
                    ->pluck('pepo.category')
                    ->filter()
                    ->unique()
                    ->implode(', ');
            @endphp

            const data = [
                ['PR Number', '{{ $ppo->project->pr_number ?? "N/A" }}'],
                ['Project', '{{ $ppo->project->name ?? "N/A" }}'],
                ['PO Number', '{{ $ppo->po_number }}'],
                ['Categories', '{{ $categoriesForExport ?: "N/A" }}'],
                ['Supplier', '{{ $ppo->ds->dsname ?? "N/A" }}'],
                ['Value', '{{ $ppo->value ? number_format($ppo->value, 2) . " SAR" : "N/A" }}'],
                ['Date', '{{ $ppo->date ? $ppo->date->format("Y-m-d") : "N/A" }}'],
                ['Status', '{{ $ppo->status ?? "N/A" }}'],
                ['Updates', '{{ $ppo->updates ?? "No updates" }}'],
                ['Notes', '{{ $ppo->notes ?? "No notes" }}'],
                ['Created', '{{ $ppo->created_at->format("Y-m-d H:i A") }}'],
                ['Updated', '{{ $ppo->updated_at->format("Y-m-d H:i A") }}']
            ];

            doc.autoTable({
                head: [['Field', 'Value']],
                body: data,
                startY: 35,
                theme: 'grid',
                headStyles: {
                    fillColor: [0, 123, 255],
                    fontStyle: 'bold'
                }
            });

            doc.save('PPO_{{ $ppo->po_number }}_' + new Date().toISOString().slice(0,10) + '.pdf');
        }

        // Export to Excel
        function exportToExcel() {
            const data = [
                ['Field', 'Value'],
                ['PR Number', '{{ $ppo->project->pr_number ?? "N/A" }}'],
                ['Project', '{{ $ppo->project->name ?? "N/A" }}'],
                ['PO Number', '{{ $ppo->po_number }}'],
                ['Categories', '{{ $categoriesForExport ?: "N/A" }}'],
                ['Supplier', '{{ $ppo->ds->dsname ?? "N/A" }}'],
                ['Value', '{{ $ppo->value ? number_format($ppo->value, 2) : "N/A" }}'],
                ['Date', '{{ $ppo->date ? $ppo->date->format("Y-m-d") : "N/A" }}'],
                ['Status', '{{ $ppo->status ?? "N/A" }}'],
                ['Updates', '{{ $ppo->updates ?? "No updates" }}'],
                ['Notes', '{{ $ppo->notes ?? "No notes" }}'],
                ['Created', '{{ $ppo->created_at->format("Y-m-d H:i A") }}'],
                ['Updated', '{{ $ppo->updated_at->format("Y-m-d H:i A") }}']
            ];

            const ws = XLSX.utils.aoa_to_sheet(data);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'PPO Details');
            XLSX.writeFile(wb, 'PPO_{{ $ppo->po_number }}_' + new Date().toISOString().slice(0,10) + '.xlsx');
        }
    </script>
@endsection
