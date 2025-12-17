@extends('layouts.master')

@section('title')
    Project Status Details
@stop

@section('css')
    <style>
        .pstatus-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 30px;
            border-radius: 10px 10px 0 0;
            margin-bottom: 0;
        }
        .pstatus-header h2 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .pstatus-header p {
            margin: 5px 0 0 0;
            opacity: 0.9;
        }
        .info-card {
            border-left: 4px solid #007bff;
            padding: 20px;
            margin-bottom: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .info-card:hover {
            box-shadow: 0 4px 12px rgba(0,123,255,0.2);
            transform: translateY(-2px);
        }
        .info-card h5 {
            color: #007bff;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-card p {
            color: #495057;
            font-size: 16px;
            margin: 0;
            font-weight: 500;
        }
        .detail-box {
            background: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .detail-box h5 {
            color: #007bff;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .detail-box pre {
            background: white;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #dee2e6;
            white-space: pre-wrap;
            word-wrap: break-word;
            margin: 0;
            font-family: inherit;
            color: #495057;
        }
        .quick-actions {
            position: sticky;
            top: 20px;
        }
        .action-btn {
            width: 100%;
            margin-bottom: 10px;
            padding: 12px;
            font-weight: 500;
            border-radius: 6px;
        }
        .completion-progress {
            height: 30px;
            border-radius: 15px;
            background: #e3f2fd;
            overflow: hidden;
            position: relative;
        }
        .completion-bar {
            height: 100%;
            background: linear-gradient(90deg, #007bff 0%, #0056b3 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            transition: width 0.5s ease;
        }
        .badge-status {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 14px;
        }
        .badge-active {
            background: #cfe2ff;
            color: #084298;
        }
        .section-title {
            color: #007bff;
            font-weight: 600;
            font-size: 18px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #007bff;
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Project Status</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Details</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-9">
            <div class="card">
                <div class="pstatus-header">
                    <h2>{{ $pstatus->project->name ?? 'N/A' }}</h2>
                    <p><i class="fas fa-hashtag"></i> PR Number: {{ $pstatus->project->pr_number ?? 'N/A' }}</p>
                </div>
                <div class="card-body">
                    <h5 class="section-title"><i class="fas fa-info-circle"></i> Basic Information</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-card">
                                <h5><i class="fas fa-calendar-alt"></i> Date & Time</h5>
                                <p>{{ $pstatus->date_time ? \Carbon\Carbon::parse($pstatus->date_time)->format('d/m/Y H:i A') : 'N/A' }}</p> {{-- ⬅️ عرض الوقت --}}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-card">
                                <h5><i class="fas fa-user-tie"></i> Project Manager</h5>
                                <p>{{ $pstatus->ppm->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <h5 class="section-title"><i class="fas fa-chart-line"></i> Completion Status</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-card">
                                <h5><i class="fas fa-percentage"></i> Actual Completion</h5>
                                <div class="completion-progress mt-2">
                                    <div class="completion-bar" style="width: {{ $pstatus->actual_completion ?? 0 }}%">
                                        {{ number_format($pstatus->actual_completion ?? 0, 2) }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-card">
                                <h5><i class="fas fa-calendar-check"></i> Expected Completion Date</h5>
                                <p>{{ $pstatus->expected_completion ? \Carbon\Carbon::parse($pstatus->expected_completion)->format('d/m/Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <h5 class="section-title"><i class="fas fa-clipboard-list"></i> Status Details</h5>
                    <div class="detail-box">
                        <h5><i class="fas fa-flag"></i> Current Status</h5>
                        <pre>{{ $pstatus->status ?: 'No status information available' }}</pre>
                    </div>

                    <div class="detail-box">
                        <h5><i class="fas fa-money-bill-wave"></i> Pending Cost/Orders</h5>
                        <pre>{{ $pstatus->date_pending_cost_orders ?: 'No pending costs or orders' }}</pre>
                    </div>

                    <div class="detail-box">
                        <h5><i class="fas fa-sticky-note"></i> Notes</h5>
                        <pre>{{ $pstatus->notes ?: 'No additional notes' }}</pre>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="section-title"><i class="fas fa-download"></i> Export Options</h5>
                            <button onclick="exportToPDF()" class="btn btn-danger mr-2">
                                <i class="fas fa-file-pdf"></i> Export as PDF
                            </button>
                            <button onclick="exportToExcel()" class="btn btn-success mr-2">
                                <i class="fas fa-file-excel"></i> Export to Excel
                            </button>
                            <button onclick="printDetails()" class="btn btn-secondary">
                                <i class="fas fa-print"></i> Print
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="quick-actions">
                <div class="card">
                    <div class="card-header" style="background: #007bff; color: white;">
                        <h6 class="mb-0"><i class="fas fa-bolt"></i> Quick Actions</h6>
                    </div>
                    <div class="card-body">
                        {{-- @can('Edit') --}}
                        <a href="{{ route('pstatus.edit', $pstatus->id) }}" class="btn btn-primary action-btn">
                            <i class="las la-pen"></i> Edit Status
                        </a>
                        {{-- @endcan --}}
                        <a href="{{ route('pstatus.index') }}" class="btn btn-outline-primary action-btn">
                            <i class="las la-arrow-left"></i> Back to List
                        </a>
                        {{-- @can('Delete') --}}
                        <button type="button" class="btn btn-danger action-btn" data-toggle="modal" data-target="#deleteModal">
                            <i class="las la-trash"></i> Delete Status
                        </button>
                        {{-- @endcan --}}
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header" style="background: #007bff; color: white;">
                        <h6 class="mb-0"><i class="fas fa-project-diagram"></i> Project Info</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>PR Number:</strong></p>
                        <p class="text-muted">{{ $pstatus->project->pr_number ?? 'N/A' }}</p>
                        <hr>
                        <p class="mb-2"><strong>Project Name:</strong></p>
                        <p class="text-muted">{{ $pstatus->project->name ?? 'N/A' }}</p>
                        <hr>
                        <p class="mb-2"><strong>Created:</strong></p>
                        <p class="text-muted">{{ $pstatus->created_at->format('d/m/Y H:i') }}</p>
                        <hr>
                        <p class="mb-2"><strong>Last Updated:</strong></p>
                        <p class="text-muted">{{ $pstatus->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Confirm Delete</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('pstatus.destroy', $pstatus->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" value="{{ $pstatus->id }}">
                    <div class="modal-body">
                        <p>Are you sure you want to delete this project status?</p>
                        <p><strong>Project:</strong> {{ $pstatus->project->pr_number ?? 'N/A' }}</p>
                        <p class="text-danger"><small>This action cannot be undone.</small></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>
        // Export to PDF
        function exportToPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Header with blue background
            doc.setFillColor(0, 123, 255);
            doc.rect(0, 0, 210, 40, 'F');

            doc.setTextColor(255, 255, 255);
            doc.setFontSize(22);
            doc.text('Project Status Report', 105, 20, { align: 'center' });
            doc.setFontSize(12);
            doc.text('{{ $pstatus->project->name ?? "N/A" }}', 105, 30, { align: 'center' });

            // Reset text color
            doc.setTextColor(0, 0, 0);
            let yPos = 50;

            // Basic Information
            doc.setFontSize(16);
            doc.setTextColor(0, 123, 255);
            doc.text('Basic Information', 14, yPos);
            yPos += 10;

            doc.setFontSize(11);
            doc.setTextColor(0, 0, 0);
            doc.text('PR Number: {{ $pstatus->project->pr_number ?? "N/A" }}', 14, yPos);
            yPos += 7;
            doc.text('Date & Time: {{ $pstatus->date_time ? \Carbon\Carbon::parse($pstatus->date_time)->format("d/m/Y H:i A") : "N/A" }}', 14, yPos);
            yPos += 7;
            doc.text('Project Manager: {{ $pstatus->ppm->name ?? "N/A" }}', 14, yPos);
            yPos += 10;

            // Completion Status
            doc.setFontSize(16);
            doc.setTextColor(0, 123, 255);
            doc.text('Completion Status', 14, yPos);
            yPos += 10;

            doc.setFontSize(11);
            doc.setTextColor(0, 0, 0);
            doc.text('Actual Completion: {{ number_format($pstatus->actual_completion ?? 0, 2) }}%', 14, yPos);
            yPos += 7;
            doc.text('Expected Completion: {{ $pstatus->expected_completion ? \Carbon\Carbon::parse($pstatus->expected_completion)->format("d/m/Y") : "N/A" }}', 14, yPos);
            yPos += 10;

            // Status
            doc.setFontSize(16);
            doc.setTextColor(0, 123, 255);
            doc.text('Current Status', 14, yPos);
            yPos += 10;

            doc.setFontSize(10);
            doc.setTextColor(0, 0, 0);
            const statusText = `{{ $pstatus->status ?: "No status information" }}`;
            const splitStatus = doc.splitTextToSize(statusText, 180);
            doc.text(splitStatus, 14, yPos);
            yPos += splitStatus.length * 5 + 10;

            // Pending Cost
            if (yPos > 250) {
                doc.addPage();
                yPos = 20;
            }
            doc.setFontSize(16);
            doc.setTextColor(0, 123, 255);
            doc.text('Pending Cost/Orders', 14, yPos);
            yPos += 10;

            doc.setFontSize(10);
            doc.setTextColor(0, 0, 0);
            const pendingText = `{{ $pstatus->date_pending_cost_orders ?: "No pending costs" }}`;
            const splitPending = doc.splitTextToSize(pendingText, 180);
            doc.text(splitPending, 14, yPos);
            yPos += splitPending.length * 5 + 10;

            // Notes
            if (yPos > 250) {
                doc.addPage();
                yPos = 20;
            }
            doc.setFontSize(16);
            doc.setTextColor(0, 123, 255);
            doc.text('Notes', 14, yPos);
            yPos += 10;

            doc.setFontSize(10);
            doc.setTextColor(0, 0, 0);
            const notesText = `{{ $pstatus->notes ?: "No notes" }}`;
            const splitNotes = doc.splitTextToSize(notesText, 180);
            doc.text(splitNotes, 14, yPos);

            // Footer
            const pageCount = doc.internal.getNumberOfPages();
            for (let i = 1; i <= pageCount; i++) {
                doc.setPage(i);
                doc.setFontSize(8);
                doc.setTextColor(128, 128, 128);
                doc.text(`Generated: ${new Date().toLocaleString()}`, 14, 290);
                doc.text(`Page ${i} of ${pageCount}`, 190, 290, { align: 'right' });
            }

            doc.save('project_status_{{ $pstatus->id }}_' + new Date().getTime() + '.pdf');
        }

        // Export to Excel
        function exportToExcel() {
            const data = [
                ['Project Status Report'],
                [''],
                ['PR Number', '{{ $pstatus->project->pr_number ?? "N/A" }}'],
                ['Project Name', '{{ $pstatus->project->name ?? "N/A" }}'],
                ['Date & Time', '{{ $pstatus->date_time ? \Carbon\Carbon::parse($pstatus->date_time)->format("d/m/Y H:i A") : "N/A" }}'],
                ['Project Manager', '{{ $pstatus->ppm->name ?? "N/A" }}'],
                [''],
                ['Actual Completion', '{{ number_format($pstatus->actual_completion ?? 0, 2) }}%'],
                ['Expected Completion', '{{ $pstatus->expected_completion ? \Carbon\Carbon::parse($pstatus->expected_completion)->format("d/m/Y") : "N/A" }}'],
                [''],
                ['Status', '{{ str_replace(["\r\n", "\n", "\r"], " ", $pstatus->status ?: "No status") }}'],
                [''],
                ['Pending Cost/Orders', '{{ str_replace(["\r\n", "\n", "\r"], " ", $pstatus->date_pending_cost_orders ?: "No pending") }}'],
                [''],
                ['Notes', '{{ str_replace(["\r\n", "\n", "\r"], " ", $pstatus->notes ?: "No notes") }}']
            ];

            const ws = XLSX.utils.aoa_to_sheet(data);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Project Status');

            XLSX.writeFile(wb, 'project_status_{{ $pstatus->id }}_' + new Date().getTime() + '.xlsx');
        }

        // Print Details
        function printDetails() {
            window.print();
        }
    </script>

    <style>
        @media print {
            .breadcrumb-header,
            .quick-actions,
            .btn,
            .card-header,
            .export-options { /* إخفاء أزرار التصدير في صفحة التفاصيل */
                display: none !important;
            }
            .card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }
        }
    </style>
@endsection
