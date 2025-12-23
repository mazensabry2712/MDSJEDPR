@extends('layouts.master')
@section('title')
    View CoC
@stop
@section('css')
    <!-- Lightbox CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">

    <style>
        .coc-header {
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

        .file-preview {
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }

        .file-preview:hover {
            border-color: #007bff;
            background: #fff;
        }

        .file-preview img {
            max-width: 100%;
            max-height: 400px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,123,255,0.3);
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
            .coc-header {
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
    </style>
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between no-print">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Certificate of Compilation</h4>
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
                <div class="coc-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-2">
                                <i class="fas fa-certificate mr-2"></i>Certificate of Compilation
                            </h3>
                            <p class="mb-0" style="opacity: 0.9;">
                                View complete details and certificate file
                            </p>
                        </div>
                        <div class="text-right">
                            @if($coc->coc_copy)
                                @php
                                    $fileExtension = pathinfo($coc->coc_copy, PATHINFO_EXTENSION);
                                    $isImage = in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                @endphp
                                @if($isImage)
                                    <i class="fas fa-image" style="font-size: 60px; opacity: 0.3;"></i>
                                @else
                                    <i class="fas fa-file-pdf" style="font-size: 60px; opacity: 0.3;"></i>
                                @endif
                            @endif
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
                                        {{ $coc->project->pr_number ?? 'N/A' }}
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
                                    {{ $coc->project->name ?? 'No project assigned' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">
                                    <i class="fas fa-calendar-plus mr-1"></i>Created Date
                                </div>
                                <div class="info-value">
                                    {{ $coc->created_at->format('Y-m-d H:i A') }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-label">
                                    <i class="fas fa-calendar-check mr-1"></i>Last Updated
                                </div>
                                <div class="info-value">
                                    {{ $coc->updated_at->format('Y-m-d H:i A') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Certificate File Preview -->
                    <div class="mt-4">
                        <h5 class="mb-3">
                            <i class="fas fa-file-alt mr-2"></i>Certificate File
                        </h5>

                        @if($coc->coc_copy && file_exists(public_path('../storge/' . $coc->coc_copy)))
                            <div class="file-preview">
                                @if($isImage)
                                    <a href="{{ asset('../storge/' . $coc->coc_copy) }}" data-lightbox="coc-file" data-title="Certificate of Compilation">
                                        <img src="{{ asset('../storge/' . $coc->coc_copy) }}" alt="CoC File">
                                    </a>
                                    <p class="mt-3 text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>Click image to view full size
                                    </p>
                                @else
                                    <i class="fas fa-file-pdf text-danger" style="font-size: 80px;"></i>
                                    <h5 class="mt-3">{{ basename($coc->coc_copy) }}</h5>
                                    <a href="{{ asset('../storge/' . $coc->coc_copy) }}" target="_blank" class="btn btn-primary mt-3">
                                        <i class="fas fa-eye mr-2"></i>View PDF File
                                    </a>
                                    <a href="{{ asset('../storge/' . $coc->coc_copy) }}" download class="btn btn-success mt-3">
                                        <i class="fas fa-download mr-2"></i>Download File
                                    </a>
                                @endif
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                No file uploaded for this certificate.
                            </div>
                        @endif
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
                        <button class="btn btn-dark" onclick="shareContent()">
                            <i class="fas fa-share-alt mr-2"></i>Share
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Sidebar -->
        <div class="col-lg-3">
            <div class="quick-actions no-print">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-bolt mr-2"></i>Quick Actions
                        </h6>
                    </div>
                    <div class="card-body">
                        @can('Edit')
                            <a href="{{ route('coc.edit', $coc->id) }}" class="btn btn-info action-btn">
                                <i class="fas fa-edit mr-2"></i>Edit Certificate
                            </a>
                        @endcan

                        <a href="{{ route('coc.index') }}" class="btn btn-secondary action-btn">
                            <i class="fas fa-arrow-left mr-2"></i>Back to List
                        </a>

                        @if($coc->project)
                            <a href="#" class="btn btn-primary action-btn">
                                <i class="fas fa-eye mr-2"></i>View Project
                            </a>
                        @endif

                        @can('Delete')
                            <button class="btn btn-danger action-btn" data-toggle="modal" data-target="#deleteModal">
                                <i class="fas fa-trash mr-2"></i>Delete Certificate
                            </button>
                        @endcan
                    </div>
                </div>

                <!-- Project Quick Info -->
                @if($coc->project)
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-info-circle mr-2"></i>Project Info
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-2">
                                <strong>PR Number:</strong><br>
                                <span class="badge badge-primary">{{ $coc->project->pr_number }}</span>
                            </p>
                            <p class="mb-0">
                                <strong>Name:</strong><br>
                                {{ $coc->project->name }}
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Confirm Delete
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('coc.destroy', $coc->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" value="{{ $coc->id }}">
                    <div class="modal-body">
                        <p>Are you sure you want to delete this Certificate of Compilation?</p>
                        <div class="alert alert-warning">
                            <strong>Project:</strong> {{ $coc->project->name ?? 'N/A' }}
                        </div>
                        <p class="text-danger">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            This action cannot be undone!
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete Certificate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- Lightbox JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

    <!-- jsPDF & html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <!-- SheetJS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>
        // Export to PDF
        function exportToPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            doc.setFontSize(18);
            doc.text('Certificate of Compilation', 15, 15);

            doc.setFontSize(12);
            let y = 30;

            doc.text('PR Number: {{ $coc->project->pr_number ?? "N/A" }}', 15, y);
            y += 10;
            doc.text('Project: {{ $coc->project->name ?? "N/A" }}', 15, y);
            y += 10;
            doc.text('Created: {{ $coc->created_at->format("Y-m-d H:i") }}', 15, y);
            y += 10;
            doc.text('Updated: {{ $coc->updated_at->format("Y-m-d H:i") }}', 15, y);

            doc.save('CoC_{{ $coc->id }}_{{ date("Y-m-d") }}.pdf');
        }

        // Export to Excel
        function exportToExcel() {
            const wb = XLSX.utils.book_new();
            const data = [
                ['Certificate of Compilation Details'],
                [],
                ['PR Number', '{{ $coc->project->pr_number ?? "N/A" }}'],
                ['Project Name', '{{ $coc->project->name ?? "N/A" }}'],
                ['Created Date', '{{ $coc->created_at->format("Y-m-d H:i") }}'],
                ['Updated Date', '{{ $coc->updated_at->format("Y-m-d H:i") }}']
            ];

            const ws = XLSX.utils.aoa_to_sheet(data);
            XLSX.utils.book_append_sheet(wb, ws, 'CoC Details');
            XLSX.writeFile(wb, 'CoC_{{ $coc->id }}_{{ date("Y-m-d") }}.xlsx');
        }

        // Share Content
        function shareContent() {
            if (navigator.share) {
                navigator.share({
                    title: 'Certificate of Compilation',
                    text: 'CoC for {{ $coc->project->name ?? "Project" }}',
                    url: window.location.href
                }).catch(err => console.log('Error sharing:', err));
            } else {
                alert('Sharing not supported on this browser');
            }
        }

        // Configure Lightbox
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true
        });
    </script>
@endsection
