@extends('layouts.master')

@section('css')
    <style>
        .info-card {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        .info-card h2 {
            font-size: 2rem;
            margin-bottom: 10px;
            font-weight: 700;
        }
        .info-card p {
            font-size: 1.1rem;
            margin: 0;
            opacity: 0.95;
        }
        .detail-box {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #007bff;
        }
        .detail-box h5 {
            color: #007bff;
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 1.2rem;
        }
        .detail-item {
            margin-bottom: 15px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .detail-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }
        .detail-label {
            font-weight: 600;
            color: #495057;
            display: inline-block;
            min-width: 200px;
        }
        .detail-value {
            color: #212529;
        }
        .status-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
        }
        .status-on-track {
            background: #0d6efd;
            color: #fff;
        }
        .status-delayed {
            background: #6ea8fe;
            color: #fff;
        }
        .action-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .action-card h5 {
            color: #495057;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .btn-action {
            width: 100%;
            margin-bottom: 10px;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .milestone-content {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            white-space: pre-wrap;
            word-wrap: break-word;
            line-height: 1.8;
        }
        @media print {
            .no-print {
                display: none;
            }
            .info-card {
                background: #007bff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Milestones</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Milestone Details</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <!-- Main Content -->
        <div class="col-xl-9 col-lg-8">
            <!-- Info Card -->
            <div class="info-card">
                <h2><i class="fas fa-flag-checkered"></i> {{ $milestones->milestone }}</h2>
                <p><i class="fas fa-project-diagram"></i> {{ $milestones->project->name ?? 'N/A' }}</p>
                <p><i class="fas fa-hashtag"></i> PR Number: {{ $milestones->project->pr_number ?? 'N/A' }}</p>
            </div>

            <!-- Basic Information -->
            <div class="detail-box">
                <h5><i class="fas fa-info-circle"></i> Basic Information</h5>
                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-flag-checkered"></i> Milestone:</span>
                    <span class="detail-value">{{ $milestones->milestone }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-hashtag"></i> PR Number:</span>
                    <span class="detail-value">{{ $milestones->project->pr_number ?? 'N/A' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-project-diagram"></i> Project Name:</span>
                    <span class="detail-value">{{ $milestones->project->name ?? 'N/A' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-info-circle"></i> Status:</span>
                    <span class="detail-value">
                        @if($milestones->status == 'on track')
                            <span class="status-badge status-on-track"><i class="fas fa-check-circle"></i> On Track</span>
                        @else
                            <span class="status-badge status-delayed"><i class="fas fa-exclamation-circle"></i> Delayed</span>
                        @endif
                    </span>
                </div>
            </div>

            <!-- Timeline Information -->
            <div class="detail-box">
                <h5><i class="fas fa-calendar-alt"></i> Timeline Information</h5>
                <div class="detail-item">
                    <span class="detail-label"><i class="far fa-calendar"></i> Planned Completion Date:</span>
                    <span class="detail-value">{{ $milestones->planned_com ? date('F d, Y', strtotime($milestones->planned_com)) : 'Not set' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><i class="far fa-calendar-check"></i> Actual Completion Date:</span>
                    <span class="detail-value">{{ $milestones->actual_com ? date('F d, Y', strtotime($milestones->actual_com)) : 'Not completed yet' }}</span>
                </div>
                @if($milestones->planned_com && $milestones->actual_com)
                    <div class="detail-item">
                        <span class="detail-label"><i class="fas fa-clock"></i> Days Difference:</span>
                        <span class="detail-value">
                            @php
                                $planned = \Carbon\Carbon::parse($milestones->planned_com);
                                $actual = \Carbon\Carbon::parse($milestones->actual_com);
                                $diff = $planned->diffInDays($actual);
                                $status = $actual->gt($planned) ? 'delayed by' : 'completed early by';
                            @endphp
                            {{ $diff }} days ({{ $status }})
                        </span>
                    </div>
                @endif
            </div>

            <!-- Comments -->
            @if($milestones->comments)
            <div class="detail-box">
                <h5><i class="fas fa-comments"></i> Comments</h5>
                <div class="milestone-content">{{ $milestones->comments }}</div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-xl-3 col-lg-4">
            <!-- Quick Actions -->
            <div class="action-card no-print">
                <h5><i class="fas fa-bolt"></i> Quick Actions</h5>
                {{-- @can('edit') --}}
                <a href="{{ route('milestones.edit', $milestones->id) }}" class="btn btn-primary btn-action">
                    <i class="fas fa-edit"></i> Edit Milestone
                </a>
                {{-- @endcan --}}
                <a href="{{ route('milestones.index') }}" class="btn btn-outline-primary btn-action">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
                {{-- @can('delete') --}}
                <button type="button" class="btn btn-danger btn-action" data-toggle="modal" data-target="#deleteModal">
                    <i class="fas fa-trash"></i> Delete Milestone
                </button>
                {{-- @endcan --}}
            </div>

            <!-- Export Options -->
            <div class="action-card no-print">
                <h5><i class="fas fa-download"></i> Export</h5>
                <button onclick="exportToPDF()" class="btn btn-primary btn-action">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </button>
                <button onclick="exportToExcel()" class="btn btn-info btn-action">
                    <i class="fas fa-file-excel"></i> Export Excel
                </button>
                <button onclick="window.print()" class="btn btn-outline-primary btn-action">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Confirm Deletion</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure you want to delete this milestone?</p>
                    <p class="text-muted mb-0">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form action="{{ route('milestones.destroy', $milestones->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="id" value="{{ $milestones->id }}">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- jsPDF for PDF export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
    <!-- SheetJS for Excel export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>
        function exportToPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Add title with blue header
            doc.setFillColor(0, 123, 255);
            doc.rect(0, 0, 210, 40, 'F');
            doc.setTextColor(255, 255, 255);
            doc.setFontSize(20);
            doc.text('Milestone Details', 105, 20, { align: 'center' });
            doc.setFontSize(12);
            doc.text('{{ $milestones->milestone }}', 105, 30, { align: 'center' });

            // Reset text color
            doc.setTextColor(0, 0, 0);

            // Add content
            const data = [
                ['Milestone', '{{ $milestones->milestone }}'],
                ['PR Number', '{{ $milestones->project->pr_number ?? "N/A" }}'],
                ['Project Name', '{{ $milestones->project->name ?? "N/A" }}'],
                ['Status', '{{ ucfirst($milestones->status) }}'],
                ['Planned Completion', '{{ $milestones->planned_com ? date("F d, Y", strtotime($milestones->planned_com)) : "Not set" }}'],
                ['Actual Completion', '{{ $milestones->actual_com ? date("F d, Y", strtotime($milestones->actual_com)) : "Not completed yet" }}'],
                ['Comments', '{{ $milestones->comments ?? "-" }}']
            ];

            doc.autoTable({
                startY: 45,
                head: [['Field', 'Value']],
                body: data,
                theme: 'grid',
                headStyles: {
                    fillColor: [0, 123, 255],
                    textColor: [255, 255, 255]
                },
                styles: { fontSize: 10 }
            });

            doc.save('milestone_{{ $milestones->id }}_{{ date("Y-m-d") }}.pdf');
        }

        function exportToExcel() {
            const data = [
                ['Field', 'Value'],
                ['Milestone', '{{ $milestones->milestone }}'],
                ['PR Number', '{{ $milestones->project->pr_number ?? "N/A" }}'],
                ['Project Name', '{{ $milestones->project->name ?? "N/A" }}'],
                ['Status', '{{ ucfirst($milestones->status) }}'],
                ['Planned Completion', '{{ $milestones->planned_com ? date("F d, Y", strtotime($milestones->planned_com)) : "Not set" }}'],
                ['Actual Completion', '{{ $milestones->actual_com ? date("F d, Y", strtotime($milestones->actual_com)) : "Not completed yet" }}'],
                ['Comments', '{{ $milestones->comments ?? "-" }}']
            ];

            const ws = XLSX.utils.aoa_to_sheet(data);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Milestone Details');
            XLSX.writeFile(wb, 'milestone_{{ $milestones->id }}_{{ date("Y-m-d") }}.xlsx');
        }
    </script>
@endsection
