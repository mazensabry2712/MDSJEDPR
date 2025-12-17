@extends('layouts.master')
@section('css')
    <!--- Internal Select2 css-->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <!---Internal Fileupload css-->
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
    <!---Internal Fancy uploader css-->
    <link href="{{ URL::asset('assets/plugins/fancyuploder/fancy_fileupload.css') }}" rel="stylesheet" />
    <!--Internal Sumoselect css-->
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/sumoselect/sumoselect-rtl.css') }}">
    <!--Internal  TelephoneInput css-->
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/telephoneinput/telephoneinput-rtl.css') }}">

    <!-- Lightbox CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">

    <style>
        .customer-header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            margin-bottom: 20px;
        }

        .customer-logo {
            width: 120px;
            height: 120px;
            border-radius: 8px;
            border: 2px solid #dee2e6;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .no-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 120px;
            height: 120px;
            border-radius: 8px;
            border: 2px dashed #dee2e6;
            background-color: #f8f9fa;
            color: #6c757d;
            font-size: 14px;
            text-align: center;
        }

        .info-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border: 1px solid #e9ecef;
        }

        .info-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f5f5f5;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-icon {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 16px;
            color: white;
            background-color: #007bff;
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .info-value {
            font-size: 16px;
            color: #495057;
            font-weight: 500;
        }

        .empty-value {
            color: #ccc;
            font-style: italic;
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 15px;
        }

        .type-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .type-active {
            background-color: #28a745;
            color: white;
        }

        .type-pending {
            background-color: #ffc107;
            color: #212529;
        }

        .type-default {
            background-color: #6c757d;
            color: white;
        }

        .customer-name {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
            color: #495057;
        }

        .customer-type {
            margin-top: 10px;
        }

        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        /* Export Button Styles */
        .btn-group .btn {
            border-radius: 0;
            border-right: 1px solid rgba(255,255,255,0.2);
        }

        .btn-group .btn:first-child {
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
        }

        .btn-group .btn:last-child {
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
            border-right: none;
        }

        .btn-loading {
            position: relative;
            pointer-events: none;
        }

        @media (max-width: 768px) {
            .btn-group {
                flex-direction: column;
                width: 100%;
            }

            .btn-group .btn {
                border-radius: 4px !important;
                margin-bottom: 5px;
                border-right: none;
            }

            .d-flex.my-xl-auto {
                flex-direction: column;
                align-items: stretch;
            }
        }

        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .card-body {
            padding: 2rem;
        }

        .control-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }

        @media (max-width: 768px) {
            .customer-header {
                text-align: center;
                padding: 15px;
            }

            .customer-name {
                font-size: 1.5rem;
            }

            .info-card {
                padding: 15px;
            }
        }
    </style>
@endsection

@section('title')
    DN Details - {{ $dn->dn_number }}
@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">DN</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Details</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <!-- Export/Print Buttons -->
            <div class="btn-group mr-3 mb-3 mb-xl-0" role="group" aria-label="Export Options">
                <button type="button" class="btn btn-success btn-sm" onclick="exportToPDF()" title="Export to PDF">
                    <i class="fas fa-file-pdf"></i> PDF
                </button>
                <button type="button" class="btn btn-primary btn-sm" onclick="exportToExcel()" title="Export to Excel">
                    <i class="fas fa-file-excel"></i> Excel
                </button>
                {{-- <button type="button" class="btn btn-info btn-sm" onclick="exportToCSV()" title="Export to CSV">
                    <i class="fas fa-file-csv"></i> CSV
                </button> --}}
                <button type="button" class="btn btn-warning btn-sm" onclick="printDN()" title="Print">
                    <i class="fas fa-print"></i> Print
                </button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="shareDN()" title="Share">
                    <i class="fas fa-share-alt"></i> Share
                </button>
            </div>

            <div class="pr-1 mb-3 mb-xl-0">
                <a href="{{ route('dn.edit', $dn->id) }}" class="btn btn-info btn-icon ml-2">
                    <i class="mdi mdi-pencil"></i>
                </a>
            </div>
            <div class="pr-1 mb-3 mb-xl-0">
                <a href="{{ route('dn.index') }}" class="btn btn-warning btn-icon ml-2">
                    <i class="mdi mdi-arrow-left"></i>
                </a>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    @if (session()->has('delete'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('delete') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- row -->
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <!-- DN Header -->
                    <div class="customer-header">
                        <div class="row align-items-center">
                            <div class="col-md-2 text-center mb-3 mb-md-0">
                                @if($dn->dn_copy && file_exists(public_path($dn->dn_copy)))
                                    @php
                                        $fileExtension = pathinfo($dn->dn_copy, PATHINFO_EXTENSION);
                                        $isImage = in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    @endphp

                                    @if($isImage)
                                        <a href="{{ asset($dn->dn_copy) }}" data-lightbox="dn-file" data-title="DN Copy - {{ $dn->dn_number }}">
                                            <img src="{{ asset($dn->dn_copy) }}" alt="DN Copy" class="customer-logo">
                                        </a>
                                    @else
                                        <div class="no-logo">
                                            <div>
                                                <i class="fas fa-file-pdf fa-2x mb-2"></i>
                                                <div>PDF File</div>
                                                <a href="{{ asset($dn->dn_copy) }}" target="_blank" class="btn btn-sm btn-primary mt-2">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="no-logo">
                                        <div>
                                            <i class="fas fa-file-times fa-2x mb-2"></i>
                                            <div>No File</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-10">
                                <h1 class="customer-name">DN #{{ $dn->dn_number }}</h1>
                                <div class="customer-type">
                                    @if($dn->status)
                                        <span class="type-badge type-active">
                                            {{ $dn->status }}
                                        </span>
                                    @else
                                        <span class="type-badge type-default">No Status</span>
                                    @endif
                                </div>
                                @if($dn->project && $dn->project->pr_number)
                                    <div class="mt-2">
                                        <span class="badge badge-light" style="font-size: 1rem; padding: 8px 12px;">
                                            Project: {{ $dn->project->pr_number }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- DN Information -->
                        <div class="col-lg-8">
                            <!-- Basic Information -->
                            <div class="info-card">
                                <h3 class="section-title">
                                    <i class="fas fa-info-circle mr-2"></i>DN Information
                                </h3>

                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-hashtag"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">DN Number</div>
                                        <div class="info-value">{{ $dn->dn_number }}</div>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-icon" style="background-color: #17a2b8;">
                                        <i class="fas fa-project-diagram"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Project Number</div>
                                        <div class="info-value">{{ $dn->project->pr_number ?? 'Not assigned' }}</div>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-icon" style="background-color: #6f42c1;">
                                        <i class="fas fa-tag"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Project Name</div>
                                        <div class="info-value">{{ $dn->project->name ?? 'No name available' }}</div>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-icon" style="background-color: #28a745;">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Status</div>
                                        <div class="info-value">{{ $dn->status ?: 'No status provided' }}</div>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-icon" style="background-color: #6f42c1;">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Created Date</div>
                                        <div class="info-value">{{ $dn->created_at ? $dn->created_at->format('d M Y, H:i') : 'Not available' }}</div>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-icon" style="background-color: #fd7e14;">
                                        <i class="fas fa-edit"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-label">Last Updated</div>
                                        <div class="info-value">{{ $dn->updated_at ? $dn->updated_at->format('d M Y, H:i') : 'Not available' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- File Information -->
                        <div class="col-lg-4">
                            <div class="info-card">
                                <h3 class="section-title">
                                    <i class="fas fa-file-alt mr-2"></i>Attachment Information
                                </h3>

                                @if ($dn->dn_copy && file_exists(public_path($dn->dn_copy)))
                                    @php
                                        $fileExtension = pathinfo($dn->dn_copy, PATHINFO_EXTENSION);
                                        $isImage = in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                        $fileSize = file_exists(public_path($dn->dn_copy)) ? filesize(public_path($dn->dn_copy)) : 0;
                                        $fileSizeFormatted = $fileSize > 0 ? number_format($fileSize / 1024, 2) . ' KB' : 'Unknown';
                                    @endphp

                                    <div class="info-item">
                                        <div class="info-icon" style="background-color: #28a745;">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="info-content">
                                            <div class="info-label">File Status</div>
                                            <div class="info-value">Available</div>
                                        </div>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-icon" style="background-color: #17a2b8;">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                        <div class="info-content">
                                            <div class="info-label">File Type</div>
                                            <div class="info-value">{{ strtoupper($fileExtension) }}</div>
                                        </div>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-icon" style="background-color: #6f42c1;">
                                            <i class="fas fa-weight-hanging"></i>
                                        </div>
                                        <div class="info-content">
                                            <div class="info-label">File Size</div>
                                            <div class="info-value">{{ $fileSizeFormatted }}</div>
                                        </div>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-icon" style="background-color: #fd7e14;">
                                            <i class="fas fa-download"></i>
                                        </div>
                                        <div class="info-content">
                                            <div class="info-label">Actions</div>
                                            <div class="info-value">
                                                <a href="{{ asset($dn->dn_copy) }}" target="_blank" class="btn btn-sm btn-primary mr-2">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                <a href="{{ asset($dn->dn_copy) }}" download class="btn btn-sm btn-success">
                                                    <i class="fas fa-download"></i> Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="info-item">
                                        <div class="info-icon" style="background-color: #dc3545;">
                                            <i class="fas fa-times"></i>
                                        </div>
                                        <div class="info-content">
                                            <div class="info-label">File Status</div>
                                            <div class="info-value empty-value">No file uploaded</div>
                                        </div>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-icon" style="background-color: #6c757d;">
                                            <i class="fas fa-upload"></i>
                                        </div>
                                        <div class="info-content">
                                            <div class="info-label">Action</div>
                                            <div class="info-value">
                                                <a href="{{ route('dn.edit', $dn->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-upload"></i> Upload File
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@section('js')
    <!-- Lightbox JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

    <!-- Export/Print Functions -->
    <script>
        // Initialize Lightbox
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'fadeDuration': 300,
            'imageFadeDuration': 300
        });

        // Export to PDF
        function exportToPDF() {
            const button = event.target.closest('button');
            showLoading(button);

            // Create PDF content
            const content = generatePrintContent();
            const printWindow = window.open('', '_blank');
            printWindow.document.write(content);
            printWindow.document.close();

            setTimeout(() => {
                printWindow.print();
                printWindow.close();
                hideLoading(button);
            }, 500);
        }

        // Export to Excel
        function exportToExcel() {
            const button = event.target.closest('button');
            showLoading(button);

            const data = [
                ['Field', 'Value'],
                ['DN Number', '{{ $dn->dn_number }}'],
                ['Project Number', '{{ $dn->project->pr_number ?? "Not assigned" }}'],
                ['Project Name', '{{ $dn->project->name ?? "No name available" }}'],
                ['Status', '{{ $dn->status ?: "No status" }}'],
                ['Created Date', '{{ $dn->created_at ? $dn->created_at->format("d M Y, H:i") : "Not available" }}'],
                ['Last Updated', '{{ $dn->updated_at ? $dn->updated_at->format("d M Y, H:i") : "Not available" }}']
            ];

            const csv = data.map(row => row.join(',')).join('\n');
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.setAttribute('hidden', '');
            a.setAttribute('href', url);
            a.setAttribute('download', 'dn_{{ $dn->dn_number }}.csv');
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);

            hideLoading(button);
        }

        // Print DN
        function printDN() {
            const button = event.target.closest('button');
            showLoading(button);

            const content = generatePrintContent();
            const printWindow = window.open('', '_blank');
            printWindow.document.write(content);
            printWindow.document.close();

            setTimeout(() => {
                printWindow.print();
                hideLoading(button);
            }, 500);
        }

        // Share DN
        function shareDN() {
            const button = event.target.closest('button');
            showLoading(button);

            if (navigator.share) {
                navigator.share({
                    title: 'DN #{{ $dn->dn_number }}',
                    text: 'DN Details - Project: {{ $dn->project->pr_number ?? "Not assigned" }}',
                    url: window.location.href,
                });
            } else {
                // Fallback to copy URL
                navigator.clipboard.writeText(window.location.href).then(() => {
                    alert('DN link copied to clipboard!');
                });
            }

            hideLoading(button);
        }

        // Generate print content
        function generatePrintContent() {
            return `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>DN #{{ $dn->dn_number }}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .header { text-align: center; margin-bottom: 30px; }
                        .info-table { width: 100%; border-collapse: collapse; }
                        .info-table th, .info-table td {
                            border: 1px solid #ddd;
                            padding: 8px;
                            text-align: left;
                        }
                        .info-table th { background-color: #f2f2f2; }
                        @media print {
                            body { margin: 0; }
                            .no-print { display: none; }
                        }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>Delivery Note #{{ $dn->dn_number }}</h1>
                        <p>Generated on ${new Date().toLocaleDateString()}</p>
                    </div>

                    <table class="info-table">
                        <tr><th>DN Number</th><td>{{ $dn->dn_number }}</td></tr>
                        <tr><th>Project Number</th><td>{{ $dn->project->pr_number ?? "Not assigned" }}</td></tr>
                        <tr><th>Status</th><td>{{ $dn->status ?: "No status provided" }}</td></tr>
                        <tr><th>Created Date</th><td>{{ $dn->created_at ? $dn->created_at->format('d M Y, H:i') : "Not available" }}</td></tr>
                        <tr><th>Last Updated</th><td>{{ $dn->updated_at ? $dn->updated_at->format('d M Y, H:i') : "Not available" }}</td></tr>
                        <tr><th>File Attachment</th><td>{{ $dn->dn_copy && file_exists(public_path($dn->dn_copy)) ? "Available" : "No file uploaded" }}</td></tr>
                    </table>
                </body>
                </html>
            `;
        }

        // Show loading state
        function showLoading(button) {
            button.classList.add('btn-loading');
            const originalText = button.innerHTML;
            button.setAttribute('data-original-text', originalText);
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            button.disabled = true;
        }

        // Hide loading state
        function hideLoading(button) {
            button.classList.remove('btn-loading');
            button.innerHTML = button.getAttribute('data-original-text');
            button.disabled = false;
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    </script>
@endsection
<style>
    .empty-value {
        color: #6c757d;
        font-style: italic;
    }

        .action-buttons {
            text-align: center;
            margin-top: 20px;
        }

        .btn-action {
            margin: 0 10px;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 500;
        }
    </style>
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">DN</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ View DN Details</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <div class="pr-1 mb-3 mb-xl-0">
                <a href="{{ route('dn.edit', $dn->id) }}" class="btn btn-success btn-icon ml-2">
                    <i class="mdi mdi-pencil"></i>
                </a>
            </div>
            <div class="pr-1 mb-3 mb-xl-0">
                <a href="{{ route('dn.index') }}" class="btn btn-primary btn-icon ml-2">
                    <i class="mdi mdi-arrow-left"></i>
                </a>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- Row -->
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <!-- DN Header -->
            <div class="dn-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h2 class="mb-1">Delivery Note #{{ $dn->dn_number }}</h2>
                        <p class="mb-0 opacity-75">Project: {{ $dn->project->pr_number ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 text-md-right">
                        <!-- DN Copy Display -->
                        @if ($dn->dn_copy && file_exists(public_path($dn->dn_copy)))
                            @php
                                $fileExtension = pathinfo($dn->dn_copy, PATHINFO_EXTENSION);
                                $isImage = in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            @endphp

                            @if($isImage)
                                <a href="{{ asset($dn->dn_copy) }}" data-lightbox="dn-file"
                                   data-title="DN Copy - {{ $dn->dn_number }}">
                                    <img src="{{ asset($dn->dn_copy) }}" alt="DN Copy" class="dn-file">
                                </a>
                            @else
                                <div class="no-file">
                                    <i class="fas fa-file-pdf fa-2x mb-2"></i>
                                    <span>PDF File</span>
                                    <a href="{{ asset($dn->dn_copy) }}" target="_blank"
                                       class="btn btn-sm btn-light mt-2">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="no-file">
                                <i class="fas fa-file-times fa-2x mb-2"></i>
                                <span>No File Uploaded</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- DN Information -->
            <div class="info-card">
                <h5 class="card-title mb-4"><i class="fas fa-info-circle text-primary mr-2"></i>DN Information</h5>

                <div class="info-item">
                    <div class="info-icon" style="background-color: #28a745;">
                        <i class="fas fa-hashtag"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">DN Number</div>
                        <div class="info-value">{{ $dn->dn_number }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon" style="background-color: #17a2b8;">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Project Number</div>
                        <div class="info-value">{{ $dn->project->pr_number ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon" style="background-color: #ffc107;">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Status</div>
                        <div class="info-value">{{ $dn->status ?: 'No status provided' }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon" style="background-color: #6f42c1;">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Attachment</div>
                        <div class="info-value">
                            @if ($dn->dn_copy && file_exists(public_path($dn->dn_copy)))
                                <a href="{{ asset($dn->dn_copy) }}" target="_blank" class="text-primary">
                                    <i class="fas fa-external-link-alt mr-1"></i>View File
                                </a>
                            @else
                                <span class="empty-value">No file uploaded</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('dn.edit', $dn->id) }}" class="btn btn-success btn-action">
                    <i class="fas fa-edit mr-2"></i>Edit DN
                </a>
                <a href="{{ route('dn.index') }}" class="btn btn-secondary btn-action">
                    <i class="fas fa-list mr-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>
    <!-- /Row -->
@endsection

@section('js')
    <!-- Lightbox JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

    <script>
        // Initialize Lightbox
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'fadeDuration': 300,
            'imageFadeDuration': 300
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    </script>
@endsection
