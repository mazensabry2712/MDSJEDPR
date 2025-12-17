@extends('layouts.master')
@section('title')
    View Epo
@stop
@section('css')
    <style>
        .epo-header {
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
            .epo-header {
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

        .margin-display {
            font-size: 24px;
            font-weight: bold;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            background: #f8f9fa;
            border: 2px solid #dee2e6;
        }
    </style>
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between no-print">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">External Purchase Orders</h4>
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
                <div class="epo-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-2">
                                <i class="fas fa-chart-line mr-2"></i>Execution Performance Overview
                            </h3>
                            <p class="mb-0" style="opacity: 0.9;">
                                View complete EPO details and financial metrics
                            </p>
                        </div>
                        <div class="text-right">
                            <i class="fas fa-calculator" style="font-size: 60px; opacity: 0.3;"></i>
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
                                        {{ $pepo->project->pr_number ?? 'N/A' }}
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
                                    {{ $pepo->project->name ?? 'No project assigned' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="info-card">
                                <div class="info-label">
                                    <i class="fas fa-tags mr-1"></i>Category
                                </div>
                                <div class="info-value">
                                    {{ $pepo->category ?? 'N/A' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">
                                    <i class="fas fa-dollar-sign mr-1"></i>Planned Cost
                                </div>
                                <div class="info-value text-info">
                                    {{ number_format($pepo->planned_cost, 2) }} SAR
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">
                                    <i class="fas fa-money-bill-wave mr-1"></i>Selling Price
                                </div>
                                <div class="info-value text-success">
                                    {{ number_format($pepo->selling_price, 2) }} SAR
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="info-card">
                                <div class="info-label">
                                    <i class="fas fa-percentage mr-1"></i>Profit Margin
                                </div>
                                <div class="margin-display">
                                    @if($pepo->margin !== null)
                                        <span class="badge badge-{{ $pepo->margin >= 0.2 ? 'success' : ($pepo->margin >= 0.1 ? 'warning' : 'danger') }}" style="font-size: 20px; padding: 10px 20px;">
                                            {{ number_format($pepo->margin * 100, 2) }}%
                                        </span>
                                        <div class="mt-2 text-muted" style="font-size: 14px;">
                                            @if($pepo->margin >= 0.2)
                                                <i class="fas fa-thumbs-up text-success"></i> Excellent Margin
                                            @elseif($pepo->margin >= 0.1)
                                                <i class="fas fa-check text-warning"></i> Acceptable Margin
                                            @else
                                                <i class="fas fa-exclamation-triangle text-danger"></i> Low Margin
                                            @endif
                                        </div>
                                    @else
                                        <span class="badge badge-secondary">N/A</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">
                                    <i class="fas fa-calendar-plus mr-1"></i>Created Date
                                </div>
                                <div class="info-value">
                                    {{ $pepo->created_at->format('Y-m-d H:i A') }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">
                                    <i class="fas fa-calendar-check mr-1"></i>Last Updated
                                </div>
                                <div class="info-value">
                                    {{ $pepo->updated_at->format('Y-m-d H:i A') }}
                                </div>
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
                        <a href="{{ route('epo.edit', $pepo->id) }}" class="btn btn-success action-btn">
                            <i class="fas fa-edit mr-2"></i>Edit EPO
                        </a>
                    @endcan

                    <a href="{{ route('epo.index') }}" class="btn btn-primary action-btn">
                        <i class="fas fa-list mr-2"></i>Back to List
                    </a>

                    @can('Delete')
                        <button type="button" class="btn btn-danger action-btn" data-toggle="modal" data-target="#deleteModal">
                            <i class="fas fa-trash mr-2"></i>Delete EPO
                        </button>
                    @endcan
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-pie mr-2"></i>Financial Summary
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Total Revenue</small>
                        <h5 class="text-success mb-0">{{ number_format($pepo->selling_price, 2) }} SAR</h5>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Total Cost</small>
                        <h5 class="text-info mb-0">{{ number_format($pepo->planned_cost, 2) }} SAR</h5>
                    </div>
                    <div>
                        <small class="text-muted d-block">Net Profit</small>
                        <h5 class="text-primary mb-0">{{ number_format($pepo->selling_price - $pepo->planned_cost, 2) }} SAR</h5>
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
                <form action="{{ url('epo/destroy') }}" method="post">
                    @csrf
                    @method('delete')
                    <div class="modal-body">
                        <p>Are you sure you want to delete this EPO record?</p>
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle mr-2"></i>
                            This action cannot be undone!
                        </div>
                        <input type="hidden" name="id" value="{{ $pepo->id }}">
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
            doc.text('EPO Details', 14, 20);
            doc.setFontSize(11);
            doc.text('Generated on: ' + new Date().toLocaleDateString(), 14, 28);

            const data = [
                ['PR Number', '{{ $pepo->project->pr_number ?? "N/A" }}'],
                ['Project', '{{ $pepo->project->name ?? "N/A" }}'],
                ['Category', '{{ $pepo->category ?? "N/A" }}'],
                ['Planned Cost', '{{ number_format($pepo->planned_cost, 2) }} SAR'],
                ['Selling Price', '{{ number_format($pepo->selling_price, 2) }} SAR'],
                ['Margin', '{{ $pepo->margin !== null ? number_format($pepo->margin * 100, 2) . "%" : "N/A" }}'],
                ['Created', '{{ $pepo->created_at->format("Y-m-d H:i A") }}'],
                ['Updated', '{{ $pepo->updated_at->format("Y-m-d H:i A") }}']
            ];

            doc.autoTable({
                head: [['Field', 'Value']],
                body: data,
                startY: 35,
                theme: 'grid',
                headStyles: {
                    fillColor: [41, 128, 185],
                    fontStyle: 'bold'
                }
            });

            doc.save('EPO_{{ $pepo->id }}_' + new Date().toISOString().slice(0,10) + '.pdf');
        }

        // Export to Excel
        function exportToExcel() {
            const data = [
                ['Field', 'Value'],
                ['PR Number', '{{ $pepo->project->pr_number ?? "N/A" }}'],
                ['Project', '{{ $pepo->project->name ?? "N/A" }}'],
                ['Category', '{{ $pepo->category ?? "N/A" }}'],
                ['Planned Cost', '{{ number_format($pepo->planned_cost, 2) }}'],
                ['Selling Price', '{{ number_format($pepo->selling_price, 2) }}'],
                ['Margin', '{{ $pepo->margin !== null ? number_format($pepo->margin * 100, 2) . "%" : "N/A" }}'],
                ['Created', '{{ $pepo->created_at->format("Y-m-d H:i A") }}'],
                ['Updated', '{{ $pepo->updated_at->format("Y-m-d H:i A") }}']
            ];

            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.aoa_to_sheet(data);

            ws['!cols'] = [{ wch: 20 }, { wch: 40 }];

            XLSX.utils.book_append_sheet(wb, ws, 'EPO Details');
            XLSX.writeFile(wb, 'EPO_{{ $pepo->id }}_' + new Date().toISOString().slice(0,10) + '.xlsx');
        }
    </script>
@endsection
