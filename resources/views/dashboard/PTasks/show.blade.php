@extends('layouts.master')

@section('title')
    Task Details
@stop

@section('css')
    <style>
        .task-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 30px;
            border-radius: 10px 10px 0 0;
            margin-bottom: 0;
        }
        .task-header h2 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .task-header p {
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
        .status-badge {
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 14px;
            display: inline-block;
        }
        .status-completed {
            background: #0d6efd;
            color: #ffffff;
        }
        .status-pending {
            background: #6ea8fe;
            color: #ffffff;
        }
        .status-progress {
            background: #0dcaf0;
            color: #ffffff;
        }
        .status-hold {
            background: #adb5bd;
            color: #ffffff;
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
                <h4 class="content-title mb-0 my-auto">Project Tasks</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Details</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-9">
            <div class="card">
                <div class="task-header">
                    <h2>Task Details</h2>
                    <p><i class="fas fa-project-diagram"></i> Project: {{ $ptask->project->name ?? 'N/A' }}</p>
                </div>
                <div class="card-body">
                    <!-- Basic Information -->
                    <h5 class="section-title"><i class="fas fa-info-circle"></i> Basic Information</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-card">
                                <h5><i class="fas fa-hashtag"></i> PR Number</h5>
                                <p>{{ $ptask->project->pr_number ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-card">
                                <h5><i class="fas fa-calendar-alt"></i> Task Date</h5>
                                <p>{{ $ptask->task_date ? \Carbon\Carbon::parse($ptask->task_date)->format('d/m/Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-card">
                                <h5><i class="fas fa-user"></i> Assigned To</h5>
                                <p>{{ $ptask->assigned ?: 'Not assigned' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-card">
                                <h5><i class="fas fa-calendar-check"></i> Expected Completion</h5>
                                <p>{{ $ptask->expected_com_date ? \Carbon\Carbon::parse($ptask->expected_com_date)->format('d/m/Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <h5 class="section-title"><i class="fas fa-flag"></i> Status</h5>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="info-card">
                                @if($ptask->status == 'completed')
                                    <span class="status-badge status-completed"><i class="fas fa-check-circle"></i> Completed</span>
                                @elseif($ptask->status == 'progress')
                                    <span class="status-badge status-progress"><i class="fas fa-spinner"></i> Under Progress</span>
                                @elseif($ptask->status == 'pending')
                                    <span class="status-badge status-pending"><i class="fas fa-clock"></i> Pending</span>
                                @elseif($ptask->status == 'hold')
                                    <span class="status-badge status-hold"><i class="fas fa-pause-circle"></i> On Hold</span>
                                @else
                                    <span class="status-badge">{{ ucfirst($ptask->status) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Task Details -->
                    <h5 class="section-title"><i class="fas fa-clipboard-list"></i> Task Details</h5>
                    <div class="detail-box">
                        <h5><i class="fas fa-file-alt"></i> Description</h5>
                        <pre>{{ $ptask->details ?: 'No task details available' }}</pre>
                    </div>

                    <!-- Export Options -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="section-title"><i class="fas fa-download"></i> Export Options</h5>
                            <button onclick="exportToPDF()" class="btn btn-primary mr-2">
                                <i class="fas fa-file-pdf"></i> Export as PDF
                            </button>
                            <button onclick="exportToExcel()" class="btn btn-info mr-2">
                                <i class="fas fa-file-excel"></i> Export to Excel
                            </button>
                            <button onclick="printDetails()" class="btn btn-outline-primary">
                                <i class="fas fa-print"></i> Print
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="col-lg-3">
            <div class="quick-actions">
                <div class="card">
                    <div class="card-header" style="background: #007bff; color: white;">
                        <h6 class="mb-0"><i class="fas fa-bolt"></i> Quick Actions</h6>
                    </div>
                    <div class="card-body">
                        {{-- @can('Edit') --}}
                        <a href="{{ route('ptasks.edit', $ptask->id) }}" class="btn btn-primary action-btn">
                            <i class="las la-pen"></i> Edit Task
                        </a>
                        {{-- @endcan --}}
                        <a href="{{ route('ptasks.index') }}" class="btn btn-outline-primary action-btn">
                            <i class="las la-arrow-left"></i> Back to List
                        </a>
                        {{-- @can('Delete') --}}
                        <button type="button" class="btn btn-danger action-btn" data-toggle="modal" data-target="#deleteModal">
                            <i class="las la-trash"></i> Delete Task
                        </button>
                        {{-- @endcan --}}
                    </div>
                </div>

                <!-- Task Info Card -->
                <div class="card mt-3">
                    <div class="card-header" style="background: #007bff; color: white;">
                        <h6 class="mb-0"><i class="fas fa-info-circle"></i> Task Info</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>Created:</strong></p>
                        <p class="text-muted">{{ $ptask->created_at->format('d/m/Y H:i') }}</p>
                        <hr>
                        <p class="mb-2"><strong>Last Updated:</strong></p>
                        <p class="text-muted">{{ $ptask->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Confirm Delete</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('ptasks.destroy', $ptask->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" value="{{ $ptask->id }}">
                    <div class="modal-body">
                        <p>Are you sure you want to delete this task?</p>
                        <p><strong>Task:</strong> {{ Str::limit($ptask->details, 50) }}</p>
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
            doc.text('Task Details Report', 105, 20, { align: 'center' });
            doc.setFontSize(12);
            doc.text('{{ $ptask->project->name ?? "N/A" }}', 105, 30, { align: 'center' });

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
            doc.text('PR Number: {{ $ptask->project->pr_number ?? "N/A" }}', 14, yPos);
            yPos += 7;
            doc.text('Project Name: {{ $ptask->project->name ?? "N/A" }}', 14, yPos);
            yPos += 7;
            doc.text('Task Date: {{ $ptask->task_date ? \Carbon\Carbon::parse($ptask->task_date)->format("d/m/Y") : "N/A" }}', 14, yPos);
            yPos += 7;
            doc.text('Assigned To: {{ $ptask->assigned ?: "Not assigned" }}', 14, yPos);
            yPos += 7;
            doc.text('Expected Completion: {{ $ptask->expected_com_date ? \Carbon\Carbon::parse($ptask->expected_com_date)->format("d/m/Y") : "N/A" }}', 14, yPos);
            yPos += 10;

            // Status
            doc.setFontSize(16);
            doc.setTextColor(0, 123, 255);
            doc.text('Status', 14, yPos);
            yPos += 10;

            doc.setFontSize(11);
            doc.setTextColor(0, 0, 0);
            doc.text('Status: {{ ucfirst($ptask->status) }}', 14, yPos);
            yPos += 10;

            // Task Details
            doc.setFontSize(16);
            doc.setTextColor(0, 123, 255);
            doc.text('Task Details', 14, yPos);
            yPos += 10;

            doc.setFontSize(10);
            doc.setTextColor(0, 0, 0);
            const detailsText = `{{ $ptask->details ?: "No task details" }}`;
            const splitDetails = doc.splitTextToSize(detailsText, 180);
            doc.text(splitDetails, 14, yPos);

            // Footer
            const pageCount = doc.internal.getNumberOfPages();
            for (let i = 1; i <= pageCount; i++) {
                doc.setPage(i);
                doc.setFontSize(8);
                doc.setTextColor(128, 128, 128);
                doc.text(`Generated: ${new Date().toLocaleString()}`, 14, 290);
                doc.text(`Page ${i} of ${pageCount}`, 190, 290, { align: 'right' });
            }

            doc.save('task_{{ $ptask->id }}_' + new Date().getTime() + '.pdf');
        }

        // Export to Excel
        function exportToExcel() {
            const data = [
                ['Task Details Report'],
                [''],
                ['PR Number', '{{ $ptask->project->pr_number ?? "N/A" }}'],
                ['Project Name', '{{ $ptask->project->name ?? "N/A" }}'],
                ['Task Date', '{{ $ptask->task_date ? \Carbon\Carbon::parse($ptask->task_date)->format("d/m/Y") : "N/A" }}'],
                ['Assigned To', '{{ $ptask->assigned ?: "Not assigned" }}'],
                ['Expected Completion', '{{ $ptask->expected_com_date ? \Carbon\Carbon::parse($ptask->expected_com_date)->format("d/m/Y") : "N/A" }}'],
                [''],
                ['Status', '{{ ucfirst($ptask->status) }}'],
                [''],
                ['Task Details', '{{ str_replace(["\r\n", "\n", "\r"], " ", $ptask->details ?: "No details") }}']
            ];

            const ws = XLSX.utils.aoa_to_sheet(data);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Task Details');

            XLSX.writeFile(wb, 'task_{{ $ptask->id }}_' + new Date().getTime() + '.xlsx');
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
            .card-header {
                display: none !important;
            }
            .card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }
        }
    </style>
@endsection
